<?php
// app/Http/Controllers/DashboardController.php

class DashboardController extends Controller {
  public function __construct() {
    session_start();
    if (!isset($_SESSION['user'])) {
      header("Location:?c=auth&m=login");
      exit();
    }
  }

  public function index() {
    $title = 'Dashboard';
    $username = $_SESSION['user']['name'] ?? 'Pengguna';
    $user_id = $_SESSION['user']['id'];

    $error = $_SESSION['error_message'] ?? null;
    $success = $_SESSION['success_message'] ?? null;

    unset($_SESSION['error_message']);
    unset($_SESSION['success_message']);
    unset($_SESSION['old_input']); // Clear old input from task forms

    $taskModel = $this->loadModel('Tugas');
    $categoryModel = $this->loadModel('Category');
    $priorityModel = $this->loadModel('Priority');

    $categories = $categoryModel->getAllCategories();
    $reminderModel = $this->loadModel('Reminder'); //muat model reminder
    $priorities = $priorityModel->getAllPriorities();

    // ambil data upcoming reminders untuk user yang sudah login
    $upcomingReminders = $reminderModel->getUpcomingReminders($user_id); 

    $categoryFilter = $_GET['category_id'] ?? null;
    $priorityFilter = $_GET['priority_id'] ?? null;
    $searchTerm = $_GET['search'] ?? null; // Get search term from URL

    $tasks = []; // Initialize tasks as an empty array

    // Logika utama untuk menampilkan tasks saat halaman dimuat pertama kali
    // (Berdasarkan filter yang ada di URL atau default semua tugas)
    if ($categoryFilter && $categoryFilter !== '') {
        $tasks = $taskModel->getByCategory((int)$categoryFilter, $user_id);
    } elseif ($priorityFilter && $priorityFilter !== '') {
        $tasks = $taskModel->getByPriority((int)$priorityFilter, $user_id);
    } elseif ($searchTerm && $searchTerm !== '') {
        $tasks = $taskModel->searchTasks($searchTerm, $user_id);
    } else {
        $tasks = $taskModel->getAllTasksWithFavoriteStatus($user_id);
    }

    $this->loadView(
      "dashboard/index",
      [
        'title' => $title,
        'username' => $username,
        'tasks' => $tasks,
        'categories' => $categories,
        'priorities' => $priorities,
        'selectedCategory' => $categoryFilter,
        'selectedPriority' => $priorityFilter,
        'searchTerm' => $searchTerm, // Pass search term to the view
        'error' => $error,
        'success' => $success,
        'upcomingReminders' => $upcomingReminders
      ],
      'main'
    );
  }

  // New method to handle AJAX search/filter requests for tasks
  public function searchTasks() {
      header('Content-Type: application/json'); // Set header for JSON response
      $user_id = $_SESSION['user']['id'];
      $searchTerm = $_GET['search'] ?? '';
      $categoryId = $_GET['category_id'] ?? null;
      $priorityId = $_GET['priority_id'] ?? null;

      $taskModel = $this->loadModel('Tugas');
      $tasks = [];

      // Logic untuk menggabungkan semua filter.
      // Jika searchTerm ada, utamakan itu. Kemudian filter lebih lanjut dengan kategori/prioritas.
      // Jika tidak ada searchTerm, filter berdasarkan kategori/prioritas.
      // Jika tidak ada filter sama sekali, tampilkan semua tugas.

      if (!empty($searchTerm)) {
          // Jika ada pencarian, mulai dengan hasil pencarian berdasarkan judul
          $tasks = $taskModel->searchTasks($searchTerm, $user_id);

          // Kemudian filter hasil pencarian berdasarkan kategori dan/atau prioritas
          if ($categoryId && $categoryId !== '') {
              // Filter hasil $tasks yang sudah ada berdasarkan category_id
              $tasks = array_filter($tasks, function($task) use ($categoryId) {
                  return $task['category_id'] == $categoryId;
              });
          }
          if ($priorityId && $priorityId !== '') {
              // Filter hasil $tasks yang sudah ada berdasarkan priority_id
              $tasks = array_filter($tasks, function($task) use ($priorityId) {
                  return $task['priority_id'] == $priorityId;
              });
          }
      } elseif ($categoryId && $categoryId !== '') {
          // Jika tidak ada pencarian, filter berdasarkan kategori
          $tasks = $taskModel->getByCategory((int)$categoryId, $user_id);
          // Dan filter lebih lanjut berdasarkan prioritas jika ada
          if ($priorityId && $priorityId !== '') {
              $tasks = array_filter($tasks, function($task) use ($priorityId) {
                  return $task['priority_id'] == $priorityId;
              });
          }
      } elseif ($priorityId && $priorityId !== '') {
          // Jika tidak ada pencarian atau kategori, filter berdasarkan prioritas
          $tasks = $taskModel->getByPriority((int)$priorityId, $user_id);
      } else {
          // Jika tidak ada filter sama sekali, tampilkan semua tugas
          $tasks = $taskModel->getAllTasksWithFavoriteStatus($user_id);
      }

      // Pastikan array tasks di-re-index setelah array_filter jika diperlukan
      $tasks = array_values($tasks);

      echo json_encode($tasks);
      exit();
  }
}
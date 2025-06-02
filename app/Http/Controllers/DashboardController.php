<?php

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
    $user_id = $_SESSION['user']['id']; // Ambil user_id dari session

    $error = $_SESSION['error_message'] ?? null;
    $success = $_SESSION['success_message'] ?? null;

    unset($_SESSION['error_message']); // Hapus setelah dibaca
    unset($_SESSION['success_message']); // Hapus setelah dibaca

    $taskModel = $this->loadModel('Tugas');
    $categoryModel = $this->loadModel('Category');

    $categories = $categoryModel->getAllCategories();

    $categoryFilter = $_GET['category_id'] ?? null;

    if ($categoryFilter && $categoryFilter !== '') { // Pastikan filter tidak kosong
        // Jika ada filter kategori, ambil tugas berdasarkan kategori dan user_id
        $tasks = $taskModel->getByCategory($categoryFilter, $user_id); 
    } else {
        // Jika tidak ada filter, ambil semua tugas milik user_id tersebut
        $tasks = $taskModel->getAllTasksWithFavoriteStatus($user_id);
    }

    $this->loadView(
      "dashboard/index", // Pastikan path view ini benar
      [
        'title' => $title,
        'username' => $username,
        'tasks' => $tasks,
        'categories' => $categories,
        'selectedCategory' => $categoryFilter, 
        'error' => $error,
        'success' => $success
      ],
      'main' // Layout yang digunakan
    );
  }
}

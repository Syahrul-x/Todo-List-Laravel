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
    $priorityModel = $this->loadModel('Priority'); // Load Priority Model

    $categories = $categoryModel->getAllCategories();
    $priorities = $priorityModel->getAllPriorities(); // Get all priorities

    $categoryFilter = $_GET['category_id'] ?? null;
    $priorityFilter = $_GET['priority_id'] ?? null; // Get priority filter from URL

    if ($categoryFilter && $categoryFilter !== '') {
        $tasks = $taskModel->getByCategory((int)$categoryFilter, $user_id);
    } elseif ($priorityFilter && $priorityFilter !== '') {
        $tasks = $taskModel->getByPriority((int)$priorityFilter, $user_id); // Use new getByPriority method
    }
    else {
        $tasks = $taskModel->getAllTasksWithFavoriteStatus($user_id);
    }

    $this->loadView(
      "dashboard/index",
      [
        'title' => $title,
        'username' => $username,
        'tasks' => $tasks,
        'categories' => $categories,
        'priorities' => $priorities, // Pass priorities to the view
        'selectedCategory' => $categoryFilter,
        'selectedPriority' => $priorityFilter, // Pass selected priority for filter dropdown
        'error' => $error,
        'success' => $success
      ],
      'main'
    );
  }
}
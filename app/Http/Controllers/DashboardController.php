<?php

class DashboardController extends Controller {
  public function __construct() {
    session_start(); // Pastikan sesi dimulai di sini
    if (!isset($_SESSION['user'])) {
      header("Location:?c=auth&m=login");
      exit();
    }
  }
  
  public function index() {
    $title = 'Dashboard';
    $username = $_SESSION['user']['name'] ?? 'Pengguna';

    $error = $_SESSION['error_message'] ?? null;
    $success = $_SESSION['success_message'] ?? null;

    unset($_SESSION['error_message']);
    unset($_SESSION['success_message']);

    // FITUR SERLY JUGA ADA DI DALAM SINI DARI VIEWS/DASHBOARD/INDEX
    // Load model tugas dan kategori
    $taskModel = $this->loadModel('Tugas');
    $categoryModel = $this->loadModel('Category');

    // Ambil semua kategori untuk dropdown filter
    $categories = $categoryModel->getAllCategories();

    // Ambil filter kategori dari query string (GET)
    $categoryFilter = $_GET['category_id'] ?? null;

    if ($categoryFilter) {
        // Jika ada filter kategori, ambil tugas berdasarkan kategori
        $tasks = $taskModel->getByCategory($categoryFilter);
    } else {
        // Jika tidak ada filter, ambil semua tugas
        $tasks = $taskModel->getAllTasksWithFavoriteStatus();
    }

    // Load view dashboard dengan data lengkap
    $this->loadView(
      "dashboard/index",
      [
        'title' => $title,
        'username' => $username,
        'tasks' => $tasks,
        'categories' => $categories,       // Data kategori untuk filter dropdown
        'selectedCategory' => $categoryFilter, // Untuk menandai pilihan dropdown
        'error' => $error,
        'success' => $success
      ],
      'main'
    );
  }
}
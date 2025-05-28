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
    $username = $_SESSION['user']['name'] ?? 'Pengguna'; // Pastikan username tersedia
    $user_id = $_SESSION['user']['id']; // Ambil user_id dari session

    // Ambil pesan sukses atau error dari session
    $error = $_SESSION['error_message'] ?? null;
    $success = $_SESSION['success_message'] ?? null;

    // Hapus pesan dari session setelah diambil agar tidak muncul lagi di refresh berikutnya
    unset($_SESSION['error_message']);
    unset($_SESSION['success_message']);

    // Ambil daftar tugas terbaru dari model Tugas
    $taskModel = $this->loadModel('Tugas'); // Pastikan model Tugas bisa di-load
    
    // Ambil tugas berdasarkan user_id yang sedang login
    $tasks = $taskModel->getTasksByUserId($user_id); 

    // --- DEBUGGING: Cek isi variabel $tasks di sini ---
    // Anda bisa menghapus baris ini setelah data tampil.
    // echo '<pre>';
    // var_dump($tasks);
    // echo '</pre>';
    // ---------------------------------------------------

    // Load view dashboard/index dan teruskan semua data yang diperlukan
    $this->loadView(
      "dashboard/index",
      [
        'title' => $title,
        'username' => $username,
        'tasks' => $tasks, // Teruskan data tugas
        'error' => $error, // Teruskan pesan error
        'success' => $success // Teruskan pesan sukses
      ],
      'main' // Asumsi 'main' adalah layout utama Anda
    );
  }
}
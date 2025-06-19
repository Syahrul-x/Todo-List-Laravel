<?php
// app/Http/Controllers/TugasController.php

class TugasController extends Controller {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']['id'])) {
            $_SESSION['error_message'] = "Anda harus login untuk mengakses halaman ini.";
            header("Location: ?c=auth&m=login");
            exit();
        }
    }

    public function create() {
        $title = "Buat Tugas Baru";

        $categoryModel = $this->loadModel('Category');
        $categories = $categoryModel->getAllCategories();

        // Load Priority Model and get all priorities
        $priorityModel = $this->loadModel('Priority');
        $priorities = $priorityModel->getAllPriorities();

        $this->loadView("tugas/create", [
            'title' => $title,
            'categories' => $categories,
            'priorities' => $priorities, // Pass priorities to the view
            'username' => $_SESSION['user']['name'] ?? ''
        ],'main');
    }

    public function store() {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'Pending';
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $priority_id = !empty($_POST['priority_id']) ? (int)$_POST['priority_id'] : null; // Get priority_id
        $user_id = $_SESSION['user']['id'];

        if (empty($title)) {
            $_SESSION['error_message'] = "Judul tugas tidak boleh kosong.";
            // Store old input to re-populate the form
            $_SESSION['old_input'] = $_POST;
            header("Location: ?c=tugas&m=create");
            exit();
        }

        $taskModel = $this->loadModel('Tugas');
        // Pass priority_id to createTask
        $success = $taskModel->createTask($title, $description, $status, $category_id, $priority_id, $user_id);

        if ($success) {
            $_SESSION['success_message'] = "Tugas '{$title}' berhasil ditambahkan!";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan tugas. Silakan coba lagi.";
        }

        header("Location: ?c=dashboard&m=index");
        exit();
    }

    public function update($id = null) {
        if ($id === null) {
            $id = $_GET['id'] ?? null;
        }

        if (!$id) {
            $_SESSION['error_message'] = "ID tugas tidak ditemukan.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }

        $taskModel = $this->loadModel('Tugas');
        $user_id = $_SESSION['user']['id'];
        $task = $taskModel->getTaskByIdAndUserId($id, $user_id);

        if (!$task) {
            $_SESSION['error_message'] = "Tugas tidak ditemukan atau Anda tidak memiliki hak akses.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }

        $categoryModel = $this->loadModel('Category');
        $categories = $categoryModel->getAllCategories();

        // Load Priority Model and get all priorities
        $priorityModel = $this->loadModel('Priority');
        $priorities = $priorityModel->getAllPriorities();

        $this->loadView("tugas/edit", [
            'task' => $task,
            'categories' => $categories,
            'priorities' => $priorities, // Pass priorities to the edit view
            'title' => 'Edit Tugas',
            'username' => $_SESSION['user']['name'] ?? ''
        ],'main');
    }

    public function saveUpdate($id = null) {
        if ($id === null) {
            $id = $_POST['id'] ?? $_GET['id'] ?? null;
        }

        if (!$id) {
            $_SESSION['error_message'] = "ID tugas tidak ditemukan.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'Pending';
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $priority_id = !empty($_POST['priority_id']) ? (int)$_POST['priority_id'] : null; // Get priority_id
        $user_id_pemilik = $_SESSION['user']['id'];

        if (empty($title)) {
            $_SESSION['error_message'] = "Judul tugas tidak boleh kosong.";
            $_SESSION['old_input'] = $_POST; // Store old input
            header("Location: ?c=tugas&m=update&id=" . $id);
            exit();
        }

        $taskModel = $this->loadModel('Tugas');
        // Pass priority_id to updateTaskByUser
        $success = $taskModel->updateTaskByUser($id, $title, $description, $status, $category_id, $priority_id, $user_id_pemilik);

        if ($success) {
            $_SESSION['success_message'] = "Tugas '{$title}' berhasil diperbarui!";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui tugas atau Anda tidak memiliki hak akses.";
        }

        header("Location: ?c=dashboard&m=index");
        exit();
    }

    public function delete($id = null) {
        if ($id === null) {
            $id = $_POST['id'] ?? $_GET['id'] ?? null;
        }

        if (!$id) {
            $_SESSION['error_message'] = "ID tugas tidak ditemukan.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }

        $taskModel = $this->loadModel('Tugas');
        $user_id = $_SESSION['user']['id'];

        $success = $taskModel->deleteTaskByUser($id, $user_id);

        if ($success) {
            $_SESSION['success_message'] = "Tugas berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus tugas atau Anda tidak memiliki hak akses.";
        }

        header("Location: ?c=dashboard&m=index");
        exit();
    }
}
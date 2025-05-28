<?php
class TugasController extends Controller {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function create() {
        $title = "Buat Tugas Baru";
        
        // Load kategori jika ada model Category
        $categories = [];
        if (file_exists('../app/Models/Category.php')) {
            $categoryModel = $this->loadModel('Category');
            $categories = $categoryModel->getAllCategories();
        }
        
        $this->loadView("tugas/create", [
            'title' => $title,
            'categories' => $categories
        ],'main');
    }

    public function store() {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'Pending';
        $category_id = $_POST['category_id'] ?? null;
        $user_id = $_SESSION['user']['id'] ?? null;

        if (empty($title)) {
            $_SESSION['error_message'] = "Judul tugas tidak boleh kosong.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }

        $taskModel = $this->loadModel('Tugas');
        $success = $taskModel->createTask($title, $description, $status, $category_id, $user_id);
        
        if ($success) {
            $_SESSION['success_message'] = "Tugas '{$title}' berhasil ditambahkan!";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan tugas. Silakan coba lagi.";
        }
        
        header("Location: ?c=dashboard&m=index");
        exit();
    }

    public function index() {
        $taskModel = $this->loadModel('Tugas');
        $user_id = $_SESSION['user']['id'] ?? null;
        
        // Get tasks with favorite status if user is logged in
        if ($user_id) {
            $tasks = $taskModel->getAllTasksWithFavoriteStatus($user_id);
        } else {
            $tasks = $taskModel->getAllTasks();
        }
        
        $this->loadView("tugas/index", ['tasks' => $tasks],'main');
    }

    public function update($id = null) {
        // Cek apakah ID ada di GET jika tidak diberikan sebagai parameter
        if ($id === null) {
            $id = $_GET['id'] ?? null;
        }
        
        if (!$id) {
            $_SESSION['error_message'] = "ID tugas tidak ditemukan.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }
        
        $taskModel = $this->loadModel('Tugas');
        $task = $taskModel->getTaskById($id);
        
        if (!$task) {
            $_SESSION['error_message'] = "Tugas tidak ditemukan.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }
        
        // Load kategori jika ada model Category
        $categories = [];
        if (file_exists('../app/Models/Category.php')) {
            $categoryModel = $this->loadModel('Category');
            $categories = $categoryModel->getAllCategories();
        }
        
        $this->loadView("tugas/edit", [
            'task' => $task,
            'categories' => $categories
        ],'main');
    }

    public function saveUpdate($id = null) {
        // Cek apakah ID ada di GET atau POST jika tidak diberikan sebagai parameter
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
        $category_id = $_POST['category_id'] ?? null;
        $user_id = $_SESSION['user']['id'] ?? null;

        if (empty($title)) {
            $_SESSION['error_message'] = "Judul tugas tidak boleh kosong.";
            header("Location: ?c=tugas&m=update&id=" . $id);
            exit();
        }

        $taskModel = $this->loadModel('Tugas');
        $success = $taskModel->updateTask($id, $title, $description, $status, $category_id, $user_id);
        
        if ($success) {
            $_SESSION['success_message'] = "Tugas '{$title}' berhasil diperbarui!";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui tugas. Silakan coba lagi.";
        }

        header("Location: ?c=dashboard&m=index");
        exit();
    }

    public function delete($id = null) {
        // Cek apakah ID ada di GET atau POST jika tidak diberikan sebagai parameter
        if ($id === null) {
            $id = $_POST['id'] ?? $_GET['id'] ?? null;
        }
        
        if (!$id) {
            $_SESSION['error_message'] = "ID tugas tidak ditemukan.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }
        
        $taskModel = $this->loadModel('Tugas');
        
        // Optional: Cek apakah tugas exists sebelum delete
        $task = $taskModel->getTaskById($id);
        if (!$task) {
            $_SESSION['error_message'] = "Tugas tidak ditemukan.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }
        
        $success = $taskModel->deleteTask($id);
        
        if ($success) {
            $_SESSION['success_message'] = "Tugas berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus tugas. Silakan coba lagi.";
        }
        
        header("Location: ?c=dashboard&m=index");
        exit();
    }
}
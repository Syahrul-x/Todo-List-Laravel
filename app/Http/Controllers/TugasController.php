<?php
class TugasController extends Controller {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Pastikan user login untuk semua aksi di controller ini
        if (!isset($_SESSION['user']['id'])) {
            $_SESSION['error_message'] = "Anda harus login untuk mengakses halaman ini.";
            header("Location: ?c=auth&m=login");
            exit();
        }
    }
    
    public function create() {
        $title = "Buat Tugas Baru";
        
        $categories = [];
        if (file_exists('../app/Models/Category.php')) { // Sesuaikan path jika perlu
            $categoryModel = $this->loadModel('Category');
            $categories = $categoryModel->getAllCategories();
        }
        
        $this->loadView("tugas/create", [ // Pastikan path view ini benar
            'title' => $title,
            'categories' => $categories,
            'username' => $_SESSION['user']['name'] ?? ''
        ],'main');
    }

    public function store() {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'Pending';
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null; // Handle kategori kosong
        $user_id = $_SESSION['user']['id'];

        if (empty($title)) {
            $_SESSION['error_message'] = "Judul tugas tidak boleh kosong.";
            // Arahkan kembali ke form create dengan data yang sudah diisi jika memungkinkan
            // (Memerlukan penanganan tambahan untuk mengisi ulang form)
            header("Location: ?c=tugas&m=create"); 
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

    // Method index di TugasController mungkin tidak banyak digunakan jika dashboard adalah tampilan utama.
    // Namun, jika ada, pastikan juga user-specific.
    public function index() {
        $taskModel = $this->loadModel('Tugas');
        $user_id = $_SESSION['user']['id'];
        
        $tasks = $taskModel->getAllTasksWithFavoriteStatus($user_id);
        
        $this->loadView("tugas/index", [ // Pastikan path view ini benar
            'tasks' => $tasks,
            'username' => $_SESSION['user']['name'] ?? ''
            // Anda mungkin perlu categories juga di sini jika ada filter di view tugas/index
        ],'main');
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
        $task = $taskModel->getTaskByIdAndUserId($id, $user_id); // Gunakan metode aman
        
        if (!$task) {
            $_SESSION['error_message'] = "Tugas tidak ditemukan atau Anda tidak memiliki hak akses.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }
        
        $categories = [];
        if (file_exists('../app/Models/Category.php')) { // Sesuaikan path jika perlu
            $categoryModel = $this->loadModel('Category');
            $categories = $categoryModel->getAllCategories();
        }
        
        $this->loadView("tugas/edit", [ // Pastikan path view ini benar
            'task' => $task,
            'categories' => $categories,
            'title' => 'Edit Tugas',
            'username' => $_SESSION['user']['name'] ?? ''
        ],'main');
    }

    public function saveUpdate($id = null) {
        if ($id === null) {
            $id = $_POST['id'] ?? $_GET['id'] ?? null; // Ambil ID dari POST karena form edit menggunakan POST
        }
        
        if (!$id) {
            $_SESSION['error_message'] = "ID tugas tidak ditemukan.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }
        
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'Pending';
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
        $user_id_pemilik = $_SESSION['user']['id'];

        if (empty($title)) {
            $_SESSION['error_message'] = "Judul tugas tidak boleh kosong.";
            header("Location: ?c=tugas&m=update&id=" . $id); // Kembali ke form edit
            exit();
        }

        $taskModel = $this->loadModel('Tugas');
        // Pastikan $id adalah ID tugas yang ingin diupdate dan $user_id_pemilik adalah pemiliknya
        $success = $taskModel->updateTaskByUser($id, $title, $description, $status, $category_id, $user_id_pemilik); // Gunakan metode aman
        
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
            $id = $_POST['id'] ?? $_GET['id'] ?? null; // ID bisa dari GET (link) atau POST (jika form)
        }
        
        if (!$id) {
            $_SESSION['error_message'] = "ID tugas tidak ditemukan.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }
        
        $taskModel = $this->loadModel('Tugas');
        $user_id = $_SESSION['user']['id'];
        
        // Optional: Ambil dulu tugasnya untuk konfirmasi nama atau logging
        // $task = $taskModel->getTaskByIdAndUserId($id, $user_id);
        // if (!$task) {
        //     $_SESSION['error_message'] = "Tugas tidak ditemukan atau Anda tidak memiliki hak akses.";
        //     header("Location: ?c=dashboard&m=index");
        //     exit();
        // }
        
        $success = $taskModel->deleteTaskByUser($id, $user_id); // Gunakan metode aman
        
        if ($success) {
            // Untuk memastikan sesuatu benar-benar terhapus, beberapa orang memeriksa affected_rows dari statement execute.
            // Jika $taskModel->deleteTaskByUser mengembalikan true/false berdasarkan execute():
            $_SESSION['success_message'] = "Tugas berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus tugas atau Anda tidak memiliki hak akses.";
        }
        
        header("Location: ?c=dashboard&m=index");
        exit();
    }
}
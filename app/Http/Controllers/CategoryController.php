<?php

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryModel;

    public function __construct()
    {
        // Load model Category
        $this->categoryModel = $this->loadModel("Category");
    }

    // Menampilkan daftar kategori
    public function index()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location:?c=auth&m=login");
            exit();
        }

        $categories = $this->categoryModel->getAllCategories();
        // Memanggil method getAllCategories() dari model kategori (categoryModel) untuk mengambil semua data kategori dari database.
        $this->loadView("category/index", ['categories' => $categories, 'title' => 'Category Management'], "main");
        // Memanggil fungsi loadView untuk menampilkan view category/index dan mengirim data
    }

    // Menampilkan form tambah kategori
    public function create()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location:?c=auth&m=login");
            exit();
        }
        // sudah tidak bisa di akses jika belum login karena session selalu di cek 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $this->loadView("category/create", [
                    'error' => "Nama kategori tidak boleh kosong",
                    'name' => $name,
                    'description' => $description,
                    'title' => 'Tambah Kategori'
                ]
                ,"main"
                );
                return;
            }

            $this->categoryModel->create($name, $description);
            header("Location:?c=category&m=index");
            exit();
        }

        $this->loadView("category/create", ['title' => 'Tambah Kategori'], "main");
    }

    // Menampilkan form edit kategori dan proses update
    public function edit()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location:?c=auth&m=login");
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location:?c=category&m=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $category = $this->categoryModel->getById($id);
                $this->loadView("category/edit", [
                    'error' => "Nama kategori tidak boleh kosong",
                    'category' => $category,
                    'title' => 'Edit Kategori'
                ]
                ,"main"
                );
                return;
            }

            $this->categoryModel->update($id, $name, $description);
            header("Location:?c=category&m=index");
            exit();
        }

        $category = $this->categoryModel->getById($id);
        if (!$category) {
            header("Location:?c=category&m=index");
            exit();
        }

        $this->loadView("category/edit", ['category' => $category, 'title' => 'Edit Kategori'], "main");
    }

    // Menghapus kategori
    public function delete()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location:?c=auth&m=login");
            exit();
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            // Periksa apakah kategori digunakan di tabel 'tasks' (atau tabel lain yang relevan)
            $categoryInUse = $this->categoryModel->checkIfCategoryInUse($id);
            
            if ($categoryInUse) {
                // Jika kategori digunakan, beri peringatan kepada pengguna
                $this->loadView("category/index", [
                    'error' => "Kategori sedang digunakan di tugas, jadi tidak dapat dihapus.",
                    'categories' => $this->categoryModel->getAllCategories(),
                    'title' => 'Category Management'
                ], "main");
                return;
            }

            // Jika tidak digunakan, hapus kategori
            $this->categoryModel->delete($id);
            header("Location:?c=category&m=index");
            exit();
        }
    }


    public function search()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location:?c=auth&m=login");
            exit();
        }

        // Ambil kata kunci pencarian dari parameter GET
        $searchTerm = $_GET['search'] ?? '';
        
        // Pastikan pencarian tidak kosong dan kemudian cari kategori
        if (!empty($searchTerm)) {
            $categories = $this->categoryModel->searchCategories($searchTerm);
        } else {
            // Jika tidak ada input pencarian, tampilkan semua kategori
            $categories = $this->categoryModel->getAllCategories();
        }

        // Kembalikan hasil pencarian dalam format JSON
        echo json_encode($categories);
    }


}

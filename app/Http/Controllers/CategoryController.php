<?php

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
            $this->categoryModel->delete($id);
        }
        header("Location:?c=category&m=index");
        exit();
    }

}

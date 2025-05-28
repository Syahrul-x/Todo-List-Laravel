<?php

class AuthController extends Controller {

    // Menampilkan halaman login
    public function login() {
        session_start();
        if (isset($_SESSION['user'])) {
            header("Location:?c=dashboard&m=index");
            exit();
        }
        $this->loadView("auth/login", ['title' => 'Login'], "auth");
    }

    // Memproses login
    public function loginProcess() {
        session_start();

        $title = 'Login';

        $name = $_POST['name'] ?? '';
        $password = $_POST['password'] ?? '';

        // Menggunakan model User untuk mencari user berdasarkan nama
        $userModel = $this->loadModel("User");
        $user = $userModel->getByName($name);

        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user'] = [
                'id' => $user->id,
                'name' => $user->name,
                'created_at' => $user->created_at,
                'updated_at'=> $user->updated_at
            ];
            header("Location:?c=dashboard&m=index");
            exit();
        } else {
            $this->loadView(
                "auth/login", 
                [
                    'title' => $title,
                    'error' => 'Login gagal, cek username/password'
                ],
                'auth'
            );
        }
    }

    // Menampilkan halaman register
    public function register() {
        session_start();
        if (isset($_SESSION['user'])) {
            header("Location:?c=dashboard&m=index");
            exit();
        }

        $this->loadView("auth/register", ['title' => 'Register'], "auth");
    }

    // Memproses register
    public function registerProcess() {
        session_start();

        $title = 'Register';

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Cek kecocokan password dan konfirmasi password
        if ($password !== $confirmPassword) {
            $this->loadView(
                "auth/register",
                [
                    'title' => $title,
                    'error' => 'Password dan konfirmasi password tidak sama',
                    'name' => $name,
                    'email' => $email
                ],
                'auth'
            );
            return;
        }

        // Menggunakan model User untuk mengecek apakah username sudah ada
        $userModel = $this->loadModel("User");
        $existingUser = $userModel->getByName($name);

        if ($existingUser) {
            $this->loadView(
                "auth/register",
                [
                    'title' => $title,
                    'error' => 'Username sudah terdaftar',
                    'name' => $name,
                    'email' => $email
                ],
                'auth'
            );
            return;
        }

        // Simpan user baru dengan password yang telah di-hash
        $userModel->create($name, $email, $password);

        // Setelah berhasil register, arahkan ke halaman login
        header("Location:?c=auth&m=login");
        exit();
    }

    // Logout
    public function logout() {
        session_start();
        session_destroy();
        header("Location:?c=auth&m=login");
        exit();
    }
}

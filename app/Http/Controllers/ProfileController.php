<?php

class ProfileController extends Controller {
    public function __construct() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location:?c=auth&m=login");
            exit();
        }
    }

    // Read profil (tampilkan data user)
    public function index() {
        $userModel = $this->loadModel("User");
        $user = $userModel->getById($_SESSION['user']['id']);

        $this->loadView(
            "profile/index",
            [
                "title" => "Profile",
                "user" => $user,
                "error" => $_SESSION['error'] ?? null,
                "success" => $_SESSION['success'] ?? null,
            ],
            "main"
        );

        unset($_SESSION['error'], $_SESSION['success']);
    }

    // Update profil user
    public function update() {
        $id = $_SESSION['user']['id'];
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Nama dan email harus valid!";
            header("Location:?c=profile&m=index");
            exit();
        }

        if ($newPassword !== '' && $newPassword !== $confirmPassword) {
            $_SESSION['error'] = "Password baru dan konfirmasi tidak cocok!";
            header("Location:?c=profile&m=index");
            exit();
        }

        $userModel = $this->loadModel("User");
        $updated = $userModel->updateProfile(
            $id,
            $name,
            $email,
            $newPassword !== '' ? $newPassword : null
        );

        if ($updated) {
            $_SESSION['success'] = "Profil berhasil diperbarui!";
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
        } else {
            $_SESSION['error'] = "Gagal memperbarui profil.";
        }

        header("Location:?c=profile&m=index");
        exit();
    }

    // Delete akun user
    public function delete() {
        $id = $_SESSION['user']['id'];
        $userModel = $this->loadModel("User");
        $deleted = $userModel->deleteUser($id);

        if ($deleted) {
            session_destroy();
            header("Location:?c=auth&m=login");
            exit();
        } else {
            $_SESSION['error'] = "Gagal menghapus akun.";
            header("Location:?c=profile&m=index");
            exit();
        }
    }
}

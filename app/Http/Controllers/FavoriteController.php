<?php
class FavoriteController extends Controller {
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user']['id'])) {
            header("Location: ?c=auth&m=login");
            exit();
        }
    }
    
    // Menampilkan daftar tugas favorit
    public function index() {
        $favoriteModel = $this->loadModel('Favorite');
        $user_id = $_SESSION['user']['id'];
        
        $favorites = $favoriteModel->getUserFavorites($user_id);
        
        $this->loadView("favorite/index", [
            'favorites' => $favorites,
            'title' => 'Tugas Favorit'
        ],'main');
    }
    
    // Toggle favorite (add/remove)
    public function toggle() {
        $task_id = $_POST['task_id'] ?? $_GET['task_id'] ?? null;
        $redirect = $_GET['redirect'] ?? 'dashboard';
        
        if (!$task_id) {
            $_SESSION['error_message'] = "ID tugas tidak ditemukan.";
            header("Location: ?c={$redirect}&m=index");
            exit();
        }
        
        $favoriteModel = $this->loadModel('Favorite');
        $user_id = $_SESSION['user']['id'];
        
        // Toggle favorite status
        $success = $favoriteModel->toggleFavorite($user_id, $task_id);
        
        if ($success) {
            $isFavorited = $favoriteModel->isFavorited($user_id, $task_id);
            if ($isFavorited) {
                $_SESSION['success_message'] = "Tugas berhasil ditambahkan ke favorit!";
            } else {
                $_SESSION['success_message'] = "Tugas berhasil dihapus dari favorit!";
            }
        } else {
            $_SESSION['error_message'] = "Gagal mengubah status favorit.";
        }
        
        // Redirect back
        if ($redirect == 'favorite') {
            header("Location: ?c=favorite&m=index");
        } else {
            header("Location: ?c=dashboard&m=index");
        }
        exit();
    }
    
    // Update urutan favorit (AJAX endpoint)
    public function updateOrder() {
        header('Content-Type: application/json');
        
        $task_id = $_POST['task_id'] ?? null;
        $new_position = $_POST['position'] ?? null;
        
        if (!$task_id || $new_position === null) {
            echo json_encode(['success' => false, 'message' => 'Parameter tidak lengkap']);
            exit();
        }
        
        $favoriteModel = $this->loadModel('Favorite');
        $user_id = $_SESSION['user']['id'];
        
        $success = $favoriteModel->updateFavoriteOrder($user_id, $task_id, $new_position);
        
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Urutan berhasil diperbarui' : 'Gagal memperbarui urutan'
        ]);
        exit();
    }
    
    // Reorder favorites page
    public function reorder() {
        $favoriteModel = $this->loadModel('Favorite');
        $user_id = $_SESSION['user']['id'];
        
        $favorites = $favoriteModel->getUserFavorites($user_id);
        
        $this->loadView("favorite/reorder", [
            'favorites' => $favorites,
            'title' => 'Atur Urutan Favorit'
        ],'main');
    }
}
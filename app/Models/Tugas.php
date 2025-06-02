<?php
class Tugas extends Model {
    
    // Membuat tugas baru
    public function createTask($title, $description, $status, $category_id, $user_id) {
        $stmt = $this->db->prepare("INSERT INTO tasks (title, description, status, category_id, user_id) 
                                    VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $title, $description, $status, $category_id, $user_id);
        return $stmt->execute(); // Return true on success, false on failure.
    }

    // Mendapatkan semua tugas dengan status favorit untuk user tertentu
    public function getAllTasksWithFavoriteStatus($user_id = null) {
        if ($user_id) {
            $stmt = $this->db->prepare("
                SELECT t.*, c.name as category_name,
                       CASE WHEN f.id IS NOT NULL THEN 1 ELSE 0 END as is_favorited
                FROM tasks t
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN favorites f ON t.id = f.task_id AND f.user_id = ?
                WHERE t.user_id = ?
                ORDER BY t.created_at DESC
            ");
            $stmt->bind_param("ii", $user_id, $user_id);
        } else {
            // Fallback jika user_id tidak diberikan (sebaiknya dihindari jika akses tugas selalu memerlukan login)
            $stmt = $this->db->prepare("
                SELECT t.*, c.name as category_name, 0 as is_favorited
                FROM tasks t
                LEFT JOIN categories c ON t.category_id = c.id
                ORDER BY t.created_at DESC
            ");
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Mendapatkan tugas berdasarkan ID dan user_id (untuk keamanan)
    public function getTaskByIdAndUserId($id, $user_id) {
        $stmt = $this->db->prepare("SELECT t.*, c.name as category_name
                                    FROM tasks t
                                    LEFT JOIN categories c ON t.category_id = c.id
                                    WHERE t.id = ? AND t.user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Mengupdate tugas dengan validasi user_id di klausa WHERE
    public function updateTaskByUser($id, $title, $description, $status, $category_id, $user_id_pemilik) {
        $stmt = $this->db->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, 
                                    category_id = ?, updated_at = current_timestamp() 
                                    WHERE id = ? AND user_id = ?"); // Validasi user_id di WHERE
        $stmt->bind_param("sssiii", $title, $description, $status, $category_id, $id, $user_id_pemilik);
        return $stmt->execute();
    }

    // Menghapus tugas dengan validasi user_id
    public function deleteTaskByUser($id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        return $stmt->execute();
    }

    // Ambil tugas berdasarkan kategori dan user_id (MODIFIKASI UTAMA)
    public function getByCategory($categoryId, $user_id) {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name as category_name,
                   CASE WHEN f.id IS NOT NULL THEN 1 ELSE 0 END as is_favorited
            FROM tasks t
            LEFT JOIN categories c ON t.category_id = c.id
            LEFT JOIN favorites f ON t.id = f.task_id AND f.user_id = ? 
            WHERE t.category_id = ? AND t.user_id = ? 
            ORDER BY t.created_at DESC
        ");
        // Urutan bind_param: user_id (untuk join is_favorited), categoryId, user_id (untuk filter utama)
        $stmt->bind_param("iii", $user_id, $categoryId, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
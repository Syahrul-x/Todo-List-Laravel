<?php
class Tugas extends Model {
    
    // Membuat tugas baru
    public function createTask($title, $description, $status, $category_id, $user_id) {
        $stmt = $this->db->prepare("INSERT INTO tasks (title, description, status, category_id, user_id) 
                                    VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $title, $description, $status, $category_id, $user_id);
        $stmt->execute();
    }

    // Mendapatkan semua tugas
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
    // Mendapatkan tugas berdasarkan user_id
    public function getTasksByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Mendapatkan tugas berdasarkan ID
    public function getTaskById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Mendapatkan tugas berdasarkan ID dan user_id (untuk keamanan)
    public function getTaskByIdAndUserId($id, $user_id) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Mengupdate tugas
    public function updateTask($id, $title, $description, $status, $category_id, $user_id) {
        $stmt = $this->db->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, 
                                    category_id = ?, user_id = ?, updated_at = current_timestamp() WHERE id = ?");
        $stmt->bind_param("sssiii", $title, $description, $status, $category_id, $user_id, $id);
        $stmt->execute();
    }

    // Mengupdate tugas dengan validasi user_id
    public function updateTaskByUser($id, $title, $description, $status, $category_id, $user_id) {
        $stmt = $this->db->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, 
                                    category_id = ?, updated_at = current_timestamp() 
                                    WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sssiii", $title, $description, $status, $category_id, $id, $user_id);
        return $stmt->execute();
    }

    // Menghapus tugas
    public function deleteTask($id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // Menghapus tugas dengan validasi user_id
    public function deleteTaskByUser($id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        return $stmt->execute();
    }

    // SERLY: Ambil tugas berdasarkan kategori
    public function getByCategory($categoryId) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE category_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
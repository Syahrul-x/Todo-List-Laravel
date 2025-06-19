<?php
// app/Models/Tugas.php

class Tugas extends Model {

    // Membuat tugas baru
    public function createTask($title, $description, $status, $category_id, $priority_id, $user_id) {
        $stmt = $this->db->prepare("INSERT INTO tasks (title, description, status, category_id, priority_id, user_id)
                                    VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiii", $title, $description, $status, $category_id, $priority_id, $user_id);
        return $stmt->execute();
    }

    // Mendapatkan semua tugas dengan status favorit, category_name, dan priority_name untuk user tertentu
    public function getAllTasksWithFavoriteStatus($user_id = null) {
        if ($user_id) {
            $stmt = $this->db->prepare("
                SELECT t.*, c.name as category_name, p.name as priority_name,
                       CASE WHEN f.id IS NOT NULL THEN 1 ELSE 0 END as is_favorited
                FROM tasks t
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN priorities p ON t.priority_id = p.id
                LEFT JOIN favorites f ON t.id = f.task_id AND f.user_id = ?
                WHERE t.user_id = ?
                ORDER BY t.created_at DESC
            ");
            $stmt->bind_param("ii", $user_id, $user_id);
        } else {
            // Fallback jika user_id tidak diberikan
            $stmt = $this->db->prepare("
                SELECT t.*, c.name as category_name, p.name as priority_name, 0 as is_favorited
                FROM tasks t
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN priorities p ON t.priority_id = p.id
                ORDER BY t.created_at DESC
            ");
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Mendapatkan tugas berdasarkan ID dan user_id (untuk keamanan)
    public function getTaskByIdAndUserId($id, $user_id) {
        $stmt = $this->db->prepare("SELECT t.*, c.name as category_name, p.name as priority_name
                                    FROM tasks t
                                    LEFT JOIN categories c ON t.category_id = c.id
                                    LEFT JOIN priorities p ON t.priority_id = p.id
                                    WHERE t.id = ? AND t.user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Mengupdate tugas dengan validasi user_id di klausa WHERE
    public function updateTaskByUser($id, $title, $description, $status, $category_id, $priority_id, $user_id_pemilik) {
        $stmt = $this->db->prepare("UPDATE tasks SET title = ?, description = ?, status = ?,
                                    category_id = ?, priority_id = ?, updated_at = current_timestamp()
                                    WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sssiiii", $title, $description, $status, $category_id, $priority_id, $id, $user_id_pemilik);
        return $stmt->execute();
    }

    // Menghapus tugas dengan validasi user_id (no changes needed for this method)
    public function deleteTaskByUser($id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        return $stmt->execute();
    }

    // Ambil tugas berdasarkan kategori dan user_id
    public function getByCategory($categoryId, $user_id) {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name as category_name, p.name as priority_name,
                   CASE WHEN f.id IS NOT NULL THEN 1 ELSE 0 END as is_favorited
            FROM tasks t
            LEFT JOIN categories c ON t.category_id = c.id
            LEFT JOIN priorities p ON t.priority_id = p.id
            LEFT JOIN favorites f ON t.id = f.task_id AND f.user_id = ?
            WHERE t.category_id = ? AND t.user_id = ?
            ORDER BY t.created_at DESC
        ");
        $stmt->bind_param("iii", $user_id, $categoryId, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // New method: Get tasks by priority and user_id
    public function getByPriority($priorityId, $user_id) {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name as category_name, p.name as priority_name,
                   CASE WHEN f.id IS NOT NULL THEN 1 ELSE 0 END as is_favorited
            FROM tasks t
            LEFT JOIN categories c ON t.category_id = c.id
            LEFT JOIN priorities p ON t.priority_id = p.id
            LEFT JOIN favorites f ON t.id = f.task_id AND f.user_id = ?
            WHERE t.priority_id = ? AND t.user_id = ?
            ORDER BY t.created_at DESC
        ");
        $stmt->bind_param("iii", $user_id, $priorityId, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // New method: Search tasks by title and user_id
    public function searchTasks($searchTerm, $user_id) {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name as category_name, p.name as priority_name,
                   CASE WHEN f.id IS NOT NULL THEN 1 ELSE 0 END as is_favorited
            FROM tasks t
            LEFT JOIN categories c ON t.category_id = c.id
            LEFT JOIN priorities p ON t.priority_id = p.id
            LEFT JOIN favorites f ON t.id = f.task_id AND f.user_id = ?
            WHERE t.user_id = ? AND t.title LIKE ?
            ORDER BY t.created_at DESC
        ");
        $searchTerm = "%" . $searchTerm . "%"; // Add wildcards for LIKE search
        $stmt->bind_param("iis", $user_id, $user_id, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
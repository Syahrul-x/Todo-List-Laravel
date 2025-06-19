<?php
class Favorite extends Model {
    
    // Menambahkan tugas ke favorit
    public function addToFavorite($user_id, $task_id) {
        // Cek apakah sudah ada di favorit
        $checkStmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id = ? AND task_id = ?");
        $checkStmt->bind_param("ii", $user_id, $task_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            return false; // Sudah ada di favorit
        }
        
        // Get max order position for user
        $maxOrderStmt = $this->db->prepare("SELECT MAX(order_position) as max_order FROM favorites WHERE user_id = ?");
        $maxOrderStmt->bind_param("i", $user_id);
        $maxOrderStmt->execute();
        $maxResult = $maxOrderStmt->get_result()->fetch_assoc();
        $newOrder = ($maxResult['max_order'] ?? 0) + 1;
        
        // Insert ke favorit
        $stmt = $this->db->prepare("INSERT INTO favorites (user_id, task_id, order_position) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $task_id, $newOrder);
        return $stmt->execute();
    }
    
    // Mendapatkan semua tugas favorit user
    public function getUserFavorites($user_id) {
        $stmt = $this->db->prepare("
            SELECT f.*, t.title, t.description, t.status, t.category_id, t.created_at as task_created_at,
                   c.name as category_name
            FROM favorites f
            JOIN tasks t ON f.task_id = t.id
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE f.user_id = ?
            ORDER BY f.order_position ASC, f.created_at DESC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Cek apakah tugas sudah difavoritkan
    public function isFavorited($user_id, $task_id) {
        $stmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id = ? AND task_id = ?");
        $stmt->bind_param("ii", $user_id, $task_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
    
    // Hapus dari favorit
    public function removeFromFavorite($user_id, $task_id) {
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND task_id = ?");
        $stmt->bind_param("ii", $user_id, $task_id);
        return $stmt->execute();
    }
    
    // Update urutan favorit
    public function updateFavoriteOrder($user_id, $task_id, $new_position) {
        // Get current position
        $getCurrentStmt = $this->db->prepare("SELECT order_position FROM favorites WHERE user_id = ? AND task_id = ?");
        $getCurrentStmt->bind_param("ii", $user_id, $task_id);
        $getCurrentStmt->execute();
        $current = $getCurrentStmt->get_result()->fetch_assoc();
        
        if (!$current) {
            return false;
        }
        
        $current_position = $current['order_position'];
        
        // Reorder other favorites
        if ($new_position < $current_position) {
            // Moving up
            $stmt = $this->db->prepare("
                UPDATE favorites 
                SET order_position = order_position + 1 
                WHERE user_id = ? 
                AND order_position >= ? 
                AND order_position < ?
                AND task_id != ?
            ");
            $stmt->bind_param("iiii", $user_id, $new_position, $current_position, $task_id);
            $stmt->execute();
        } else if ($new_position > $current_position) {
            // Moving down
            $stmt = $this->db->prepare("
                UPDATE favorites 
                SET order_position = order_position - 1 
                WHERE user_id = ? 
                AND order_position > ? 
                AND order_position <= ?
                AND task_id != ?
            ");
            $stmt->bind_param("iiii", $user_id, $current_position, $new_position, $task_id);
            $stmt->execute();
        }
        
        // Update the target favorite
        $updateStmt = $this->db->prepare("UPDATE favorites SET order_position = ? WHERE user_id = ? AND task_id = ?");
        $updateStmt->bind_param("iii", $new_position, $user_id, $task_id);
        return $updateStmt->execute();
    }
    
    // Toggle favorite status
    public function toggleFavorite($user_id, $task_id) {
        if ($this->isFavorited($user_id, $task_id)) {
            return $this->removeFromFavorite($user_id, $task_id);
        } else {
            return $this->addToFavorite($user_id, $task_id);
        }
    }
}
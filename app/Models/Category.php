<?php

class Category extends Model {

    // Mendapatkan semua kategori
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name";
        $result = $this->db->query($sql);
        $categories = [];
        while ($obj = $result->fetch_object()) {
            $categories[] = $obj;
        }
        return $categories;
    }

    // Mendapatkan kategori berdasarkan id
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object();
    }

    // Membuat kategori baru
    public function create($name, $description) {
        $stmt = $this->db->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }

    // Mengupdate kategori berdasarkan id
    public function update($id, $name, $description) {
        $stmt = $this->db->prepare("UPDATE categories SET name = ?, description = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }

    // Menghapus kategori berdasarkan id
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

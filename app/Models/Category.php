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
    public function delete($categoryId)
    {
        // Hapus kategori berdasarkan id
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        return $stmt->execute();
    }

    public function checkIfCategoryInUse($categoryId)
    {
        // Query untuk memeriksa apakah kategori digunakan di tabel 'tasks'
        $sql = "SELECT COUNT(*) FROM tasks WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;  // Jika kategori digunakan, return true
    }

    public function searchCategories($searchTerm)
    {
        // Query SQL untuk mencari kategori berdasarkan nama yang sesuai dengan pencarian
        $sql = "SELECT * FROM categories WHERE name LIKE ?";
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%" . $searchTerm . "%";  // Membuat wildcard untuk pencarian
        $stmt->bind_param("s", $searchTerm); // Binding parameter string
        $stmt->execute();
        $result = $stmt->get_result();

        // Mengambil semua kategori hasil pencarian dan mengembalikannya
        $categories = [];
        while ($row = $result->fetch_object()) {
            $categories[] = $row;
        }

        return $categories;
    }

}

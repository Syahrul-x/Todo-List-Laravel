<?php

class User extends Model {

    // Mendapatkan user berdasarkan nama
    public function getByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE name = ?");
        $stmt->bind_param("s", $name);  
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object(); 
    }

    // Mendapatkan user berdasarkan email
    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email); 
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object();
    }

    // Membuat user baru
    public function create($name, $email, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);  
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $passwordHash);  
        return $stmt->execute();
    }

    // Mendapatkan user berdasarkan id
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object();
    }

    //Update profil
    public function updateProfile($id, $name, $email, $password = null) {
    $fields = "name = ?, email = ?, updated_at = NOW()";
    $types = "ssi";
    $params = [$name, $email, $id];

    if ($password !== null && $password !== '') {
        $fields = "name = ?, email = ?, password = ?, updated_at = NOW()";
        $types = "sssi";
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $params = [$name, $email, $passwordHash, $id];
    }
        $sql = "UPDATE users SET {$fields} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }

// Delete user berdasarkan id
public function deleteUser($id) {
    $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
}

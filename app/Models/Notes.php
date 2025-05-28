<?php
include_once __DIR__ . '/Model.php';

class Notes extends Model
{
    protected $table = 'notes';

    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");

        $notes = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $notes[] = $row;
            }
        }

        return $notes;
    }

    public function getAllByUser($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $notes = [];
        while ($row = $result->fetch_assoc()) {
            $notes[] = $row;
        }

        return $notes;
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (user_id, title, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $data['user_id'], $data['title'], $data['description']);
        return $stmt->execute();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $data['title'], $data['description'], $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
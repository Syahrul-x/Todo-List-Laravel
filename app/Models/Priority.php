<?php
// app/Models/Priority.php

// Ensure Model.php is included so Priority can extend it
include_once __DIR__ . '/Model.php';

class Priority extends Model {

    protected $table = 'priorities'; // Table name for priorities

    // Fetches all priorities from the database, ordered by name.
    public function getAllPriorities() {
        $sql = "SELECT * FROM {$this->table} ORDER BY name";
        $result = $this->db->query($sql);
        $priorities = [];
        if ($result) {
            while ($obj = $result->fetch_object()) {
                $priorities[] = $obj;
            }
        }
        return $priorities;
    }

    // Fetches a single priority by its ID.
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object();
    }

    // Creates a new priority record in the database.
    public function create($name, $description) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }

    // Updates an existing priority record by its ID.
    public function update($id, $name, $description) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET name = ?, description = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }

    // Deletes a priority record by its ID, but only if it's not currently in use by any tasks.
    public function delete($priorityId) {
        // Check if the priority is linked to any tasks
        if ($this->checkIfPriorityInUse($priorityId)) {
            return false; // Return false if it's in use, preventing deletion
        }
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $priorityId);
        return $stmt->execute();
    }

    // Checks if any tasks are currently assigned to this priority.
    public function checkIfPriorityInUse($priorityId) {
        $sql = "SELECT COUNT(*) FROM tasks WHERE priority_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $priorityId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    // Searches for priorities based on a given search term, matching against the 'name' column.
    public function searchPriorities($searchTerm) {
        $sql = "SELECT * FROM {$this->table} WHERE name LIKE ?";
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%" . $searchTerm . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $priorities = [];
        if ($result) {
            while ($row = $result->fetch_object()) {
                $priorities[] = $row;
            }
        }
        return $priorities;
    }
}
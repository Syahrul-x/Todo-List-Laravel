<?php
include_once __DIR__ . '/Model.php'; 

class Event extends Model
{
    protected $table = 'events'; 
    public function getAllByUser($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY start_time ASC"); //
        $stmt->bind_param("i", $userId); 
        $stmt->execute();

        $result = $stmt->get_result(); 
        $events = []; //
        while ($row = $result->fetch_assoc()) { //
            $events[] = $row; //
        }
        return $events; 
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
        
        $columns = 'user_id, event_name, description, start_time, location';
        $placeholders = '?, ?, ?, ?, ?';
        $types = 'issss';
        $params = [$data['user_id'], $data['event_name'], $data['description'], $data['start_time'], $data['location']];

        if (isset($data['end_time']) && !empty($data['end_time'])) {
            $columns .= ', end_time';
            $placeholders .= ', ?';
            $types .= 's';
            $params[] = $data['end_time'];
        }

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql); 

        $stmt->bind_param($types, ...$params); 
        return $stmt->execute(); 
    }

    public function update($id, $data)
    {
        $setClauses = 'event_name = ?, description = ?, start_time = ?, location = ?, updated_at = NOW()';
        $types = 'ssssi'; 
        $params = [$data['event_name'], $data['description'], $data['start_time'], $data['location'], $id];

        if (isset($data['end_time']) && !empty($data['end_time'])) { 
            $setClauses = 'event_name = ?, description = ?, start_time = ?, end_time = ?, location = ?, updated_at = NOW()'; 
            $types = 'sssssi';
            $params = [$data['event_name'], $data['description'], $data['start_time'], $data['end_time'], $data['location'], $id]; 
        } else {
            $setClauses = 'event_name = ?, description = ?, start_time = ?, end_time = NULL, location = ?, updated_at = NOW()'; 
            $types = 'sssi';
            $params = [$data['event_name'], $data['description'], $data['start_time'], $data['location'], $id]; 
        }

        $sql = "UPDATE {$this->table} SET {$setClauses} WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param($types, ...$params); 
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id); 
        $result = $stmt->execute(); 

        return $result;
    }
}
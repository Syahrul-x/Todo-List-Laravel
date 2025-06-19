<?php
// app/Models/Reminder.php

include_once __DIR__ . '/Model.php';

class Reminder extends Model
{
    protected $table = 'reminders';

    /**
     * Get all reminders for a specific user, joined with task and event details.
     * This is used to populate the calendar.
     *
     * @param int $userId
     * @return array
     */
    public function getCalendarEntriesForUser($userId)
    {
        $sql = "
            SELECT
                r.id,
                r.reminder_time,
                r.message,
                'task' AS entry_type,
                t.id AS entry_id,
                t.title,
                t.description,
                t.status
            FROM reminders r
            JOIN tasks t ON r.task_id = t.id
            WHERE r.user_id = ?
            
            UNION ALL
            
            SELECT
                r.id,
                r.reminder_time,
                r.message,
                'event' AS entry_type,
                e.id AS entry_id,
                e.event_name AS title,
                e.description,
                e.location
            FROM reminders r
            JOIN events e ON r.event_id = e.id
            WHERE r.user_id = ?
            
            ORDER BY reminder_time ASC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    

    /**
     * Get upcoming reminders for the dashboard.
     * Diubah untuk menampilkan 5 reminder terdekat (baik masa lalu maupun masa depan).
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getUpcomingReminders($userId, $limit = 5) {
        // Query ini mengambil start_time dari tabel events dan menamakannya 'event_start_time'
        $sql = "
            SELECT 
                e.event_name as title,
                e.start_time as event_start_time  -- Ini adalah kunci yang akan dikirim ke view
            FROM 
                reminders r
            JOIN 
                events e ON r.event_id = e.id
            WHERE 
                r.user_id = ? 
                AND e.start_time >= NOW()
            ORDER BY 
                e.start_time ASC
            LIMIT ?
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Create a new reminder.
     *
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, task_id, event_id, reminder_time, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "iiiss",
            $data['user_id'],
            $data['task_id'],
            $data['event_id'],
            $data['reminder_time'],
            $data['message']
        );
        return $stmt->execute();
    }

    /**
     * Update an existing reminder.
     *
     * @param int $id
     * @param array $data
     * @param int $userId
     * @return bool
     */
    public function update($id, array $data, $userId)
    {
        $sql = "UPDATE {$this->table} SET reminder_time = ?, message = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "ssii",
            $data['reminder_time'],
            $data['message'],
            $id,
            $userId
        );
        return $stmt->execute();
    }

    /**
     * Delete a reminder by its ID, ensuring it belongs to the logged-in user.
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function delete($id, $userId)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }
    
    /**
     * Get a single reminder by its ID, joined with task/event details.
     *
     * @param int $id The reminder ID
     * @param int $userId
     * @return array|null
     */
    public function getReminderDetailsById($id, $userId)
    {
        // This is a complex query to get a specific reminder and its related task or event
        $sql = "
            SELECT r.*, 'task' as entry_type, t.title, t.description 
            FROM reminders r JOIN tasks t ON r.task_id = t.id 
            WHERE r.id = ? AND r.user_id = ?
            UNION
            SELECT r.*, 'event' as entry_type, e.event_name as title, e.description
            FROM reminders r JOIN events e ON r.event_id = e.id
            WHERE r.id = ? AND r.user_id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $id, $userId, $id, $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();

    
    }

    /**
     * Mengambil detail reminder beserta event/task induknya.
     *
     * @param int $reminderId ID dari reminder.
     * @param int $userId ID dari user.
     * @return array|null
     */
    public function getReminderWithDetails($reminderId, $userId)
    {
        // Query ini akan mencari reminder berdasarkan ID, lalu menggabungkannya
        // dengan detail dari event atau task yang terhubung.
        $sql = "
            (SELECT 
                r.id, r.reminder_time, r.message, r.event_id, r.task_id,
                'event' as entry_type, e.event_name as title
            FROM reminders r
            JOIN events e ON r.event_id = e.id
            WHERE r.id = ? AND r.user_id = ?)
            
            UNION ALL

            (SELECT 
                r.id, r.reminder_time, r.message, r.event_id, r.task_id,
                'task' as entry_type, t.title
            FROM reminders r
            JOIN tasks t ON r.task_id = t.id
            WHERE r.id = ? AND r.user_id = ?)
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $reminderId, $userId, $reminderId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
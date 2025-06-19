<?php
// app/Models/Event.php

// Pastikan file Model.php di-include agar kelas Event dapat mewarisi properti dan method dari Model.
include_once __DIR__ . '/Model.php'; //

class Event extends Model
{
    // Properti protected untuk nama tabel di database.
    protected $table = 'events'; //

    /**
     * Mengambil semua event yang terkait dengan user tertentu.
     * Event diurutkan berdasarkan start_time secara descending (terbaru dulu).
     *
     * @param int $userId ID dari user yang event-nya ingin diambil.
     * @return array Array asosiatif dari event-event.
     */
    public function getAllByUser($userId)
    {
        // Query SQL menggunakan prepared statement untuk mencegah SQL injection.
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY start_time DESC"); //
        // Bind parameter 'i' (integer) untuk user_id.
        $stmt->bind_param("i", $userId); //
        // Jalankan statement.
        $stmt->execute(); //

        // Ambil hasil query.
        $result = $stmt->get_result(); //
        $events = []; //
        // Loop melalui setiap baris hasil dan tambahkan ke array $events.
        while ($row = $result->fetch_assoc()) { //
            $events[] = $row; //
        }

        // Kembalikan array event.
        return $events; //
    }

    /**
     * Mengambil event berdasarkan ID-nya.
     *
     * @param int $id ID dari event yang ingin diambil.
     * @return array|null Array asosiatif event jika ditemukan, atau null jika tidak.
     */
    public function getById($id)
    {
        // Query SQL menggunakan prepared statement.
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?"); //
        // Bind parameter 'i' (integer) untuk id.
        $stmt->bind_param("i", $id); //
        // Jalankan statement.
        $stmt->execute(); //

        // Ambil hasil query sebagai array asosiatif.
        $result = $stmt->get_result(); //
        return $result->fetch_assoc(); //
    }

    /**
     * Membuat event baru di database.
     *
     * @param array $data Array asosiatif berisi data event (user_id, event_name, description, start_time, end_time, location).
     * @return bool True jika berhasil, false jika gagal.
     */
    public function create($data)
    {
        // Tentukan kolom dan placeholder berdasarkan apakah end_time ada atau tidak.
        $columns = 'user_id, event_name, description, start_time, location'; //
        $placeholders = '?, ?, ?, ?, ?'; //
        $types = 'issss'; // Integer, string, string, string, string (untuk user_id, name, desc, start_time, location)
        $params = [$data['user_id'], $data['event_name'], $data['description'], $data['start_time'], $data['location']]; //

        // Jika end_time disediakan dan tidak kosong, tambahkan ke query.
        if (isset($data['end_time']) && !empty($data['end_time'])) { //
            $columns .= ', end_time'; //
            $placeholders .= ', ?'; //
            $types .= 's'; // Tambahkan 's' untuk end_time (string)
            $params[] = $data['end_time']; //
        }

        // Siapkan statement SQL untuk insert.
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})"; //
        $stmt = $this->db->prepare($sql); //

        // Bind parameter. Operator '...' adalah splat operator (PHP 5.6+) untuk membongkar array menjadi argumen individual.
        $stmt->bind_param($types, ...$params); //
        // Jalankan statement dan kembalikan hasilnya.
        return $stmt->execute(); //
    }

    /**
     * Memperbarui event yang sudah ada di database.
     *
     * @param int $id ID dari event yang akan diperbarui.
     * @param array $data Array asosiatif berisi data event yang akan diperbarui.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function update($id, $data)
    {
        // Mulai dengan kolom yang selalu ada.
        $setClauses = 'event_name = ?, description = ?, start_time = ?, location = ?, updated_at = NOW()'; //
        $types = 'ssssi'; // String, string, string, string, integer (untuk name, desc, start_time, location, id)
        $params = [$data['event_name'], $data['description'], $data['start_time'], $data['location'], $id]; //

        // Jika end_time disediakan dan tidak kosong, tambahkan ke klausa SET.
        // Jika end_time kosong atau tidak ada, kita set null di database.
        if (isset($data['end_time']) && !empty($data['end_time'])) { //
            $setClauses = 'event_name = ?, description = ?, start_time = ?, end_time = ?, location = ?, updated_at = NOW()'; //
            $types = 'sssssi'; // String, string, string, string, string, integer
            $params = [$data['event_name'], $data['description'], $data['start_time'], $data['end_time'], $data['location'], $id]; //
        } else {
            // Jika end_time tidak disediakan atau kosong, kita pastikan kolom end_time di database diatur ke NULL
            $setClauses = 'event_name = ?, description = ?, start_time = ?, end_time = NULL, location = ?, updated_at = NOW()'; //
            $types = 'sssi'; // String, string, string, integer (untuk name, desc, start_time, location, id)
            $params = [$data['event_name'], $data['description'], $data['start_time'], $data['location'], $id]; //
        }

        // Siapkan statement SQL untuk update.
        $sql = "UPDATE {$this->table} SET {$setClauses} WHERE id = ?"; //
        $stmt = $this->db->prepare($sql); //

        // Bind parameter.
        $stmt->bind_param($types, ...$params); //
        // Jalankan statement dan kembalikan hasilnya.
        return $stmt->execute(); //
    }

    /**
     * Menghapus event dari database berdasarkan ID-nya.
     *
     * @param int $id ID dari event yang akan dihapus.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function delete($id)
    {
        // Siapkan statement SQL untuk delete.
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?"); //
        // Bind parameter 'i' (integer) untuk id.
        $stmt->bind_param("i", $id); //
        // Jalankan statement dan kembalikan hasilnya.
        $result = $stmt->execute(); //

        return $result; //
    }
}
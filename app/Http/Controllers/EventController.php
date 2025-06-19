<?php
// app/Http/Controllers/EventController.php

require_once __DIR__ . '/Controller.php';

class EventController extends Controller
{
    // Konstruktor untuk memastikan user sudah login sebelum mengakses fungsi-fungsi di controller ini.
    public function __construct()
    {
        // Memulai sesi jika belum dimulai.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Jika user belum login, arahkan ke halaman login.
        if (!isset($_SESSION['user'])) {
            header("Location:?c=auth&m=login");
            exit();
        }
    }

    /**
     * Menampilkan daftar semua event yang dimiliki oleh user yang sedang login.
     */
    public function index()
    {
        // Memuat model 'Event'.
        $eventModel = $this->loadModel('Event');
        // Mendapatkan ID user dari sesi.
        $userId = $_SESSION['user']['id'];
        // Mengambil semua event milik user tersebut.
        $events = $eventModel->getAllByUser($userId);

        // Memuat tampilan 'events/index.php' dengan data event dan nama user.
        $this->loadView('events/index', ['events' => $events, 'username' => $_SESSION['user']['name']], 'main');
    }

    /**
     * Menampilkan form untuk membuat event baru.
     */
    public function create()
    {
        // Memuat tampilan 'events/create.php' dengan nama user.
        $this->loadView('events/create', ['username' => $_SESSION['user']['name']], 'main');
    }

    /**
     * Menyimpan data event baru ke database.
     */
    public function store()
    {
        // Memuat model 'Event'.
        $eventModel = $this->loadModel('Event');
        // Mendapatkan ID user dari sesi.
        $userId = $_SESSION['user']['id'];

        // Mengambil data dari request POST.
        $eventName = trim($_POST['event_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $startTime = $_POST['start_time'] ?? '';
        $endTime = $_POST['end_time'] ?? null; // Null jika tidak diisi atau checkbox tidak dicentang
        $location = trim($_POST['location'] ?? '');

        // Validasi input
        if (empty($eventName) || empty($startTime)) {
            // Jika nama event atau waktu mulai kosong, set pesan error dan kembali ke form create.
            $_SESSION['error_message'] = "Nama Event dan Waktu Mulai tidak boleh kosong.";
            // Kita bisa tambahkan data yang sudah diisi agar user tidak perlu mengulang input
            $_SESSION['old_input'] = $_POST;
            header("Location: ?c=event&m=create");
            exit();
        }

        // Siapkan data untuk model.
        $data = [
            'user_id' => $userId,
            'event_name' => $eventName,
            'description' => $description,
            'start_time' => $startTime,
            'location' => $location,
        ];

        // Tambahkan end_time jika ada dan tidak kosong.
        if (!empty($endTime)) {
            $data['end_time'] = $endTime;
        }

        // Panggil method create di model untuk menyimpan data.
        $success = $eventModel->create($data);

        if ($success) {
            $_SESSION['success_message'] = "Event '{$eventName}' berhasil ditambahkan!";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan event. Silakan coba lagi.";
        }

        // Arahkan kembali ke halaman daftar event.
        header("Location: ?c=event&m=index");
        exit;
    }

    /**
     * Menampilkan form untuk mengedit event yang sudah ada.
     *
     * @param int $id ID event yang akan diedit, diambil dari parameter URL.
     */
    public function edit($id = null)
    {
        // Jika ID tidak ada, arahkan kembali ke daftar event.
        if (!$id) {
            header("Location: ?c=event&m=index");
            exit;
        }

        // Memuat model 'Event'.
        $eventModel = $this->loadModel('Event');
        // Mengambil event berdasarkan ID.
        $event = $eventModel->getById($id);

        // Jika event tidak ditemukan, arahkan kembali ke daftar event.
        if (!$event || $event['user_id'] !== $_SESSION['user']['id']) {
            $_SESSION['error_message'] = "Event tidak ditemukan atau Anda tidak memiliki akses.";
            header("Location: ?c=event&m=index");
            exit;
        }

        // Memuat tampilan 'events/edit.php' dengan data event dan nama user.
        $this->loadView('events/edit', ['event' => $event, 'username' => $_SESSION['user']['name']], 'main');
    }

    /**
     * Menyimpan perubahan pada event yang sudah ada ke database.
     *
     * @param int $id ID event yang akan diperbarui, diambil dari request POST atau parameter URL.
     */
    public function saveUpdate($id = null)
    {
        // Prioritaskan ID dari POST (jika form dikirim) atau dari GET (jika dari URL langsung).
        if ($id === null) {
            $id = $_POST['id'] ?? $_GET['id'] ?? null;
        }

        // Jika ID tidak ada, set pesan error dan kembali ke dashboard.
        if (!$id) {
            $_SESSION['error_message'] = "ID event tidak ditemukan.";
            header("Location: ?c=event&m=index");
            exit();
        }

        // Memuat model 'Event'.
        $eventModel = $this->loadModel('Event');
        $userId = $_SESSION['user']['id'];

        // Pastikan event yang akan diupdate dimiliki oleh user yang sedang login
        $existingEvent = $eventModel->getById($id);
        if (!$existingEvent || $existingEvent['user_id'] !== $userId) {
            $_SESSION['error_message'] = "Event tidak ditemukan atau Anda tidak memiliki hak akses untuk mengubahnya.";
            header("Location: ?c=event&m=index");
            exit();
        }

        // Mengambil data dari request POST.
        $eventName = trim($_POST['event_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $startTime = $_POST['start_time'] ?? '';
        $endTime = $_POST['end_time'] ?? null; // Null jika tidak diisi atau checkbox tidak dicentang
        $location = trim($_POST['location'] ?? '');

        // Validasi input
        if (empty($eventName) || empty($startTime)) {
            $_SESSION['error_message'] = "Nama Event dan Waktu Mulai tidak boleh kosong.";
            header("Location: ?c=event&m=edit&id=" . $id); // Kembali ke form edit dengan ID
            exit();
        }

        // Siapkan data untuk model.
        $data = [
            'event_name' => $eventName,
            'description' => $description,
            'start_time' => $startTime,
            'location' => $location,
        ];

        // Tambahkan end_time jika ada dan tidak kosong.
        if (!empty($endTime)) {
            $data['end_time'] = $endTime;
        } else {
            $data['end_time'] = null; // Pastikan end_time diset null jika tidak dicentang/diisi
        }


        // Panggil method update di model.
        $success = $eventModel->update($id, $data);

        if ($success) {
            $_SESSION['success_message'] = "Event '{$eventName}' berhasil diperbarui!";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui event. Silakan coba lagi.";
        }

        // Arahkan kembali ke halaman daftar event.
        header("Location: ?c=event&m=index");
        exit();
    }

    /**
     * Menghapus event dari database.
     *
     * @param int $id ID event yang akan dihapus, diambil dari parameter URL.
     */
    public function delete($id = null)
    {
        // Jika ID tidak ada, arahkan kembali ke daftar event.
        if (!$id) {
            header("Location: ?c=event&m=index");
            exit;
        }

        // Memuat model 'Event'.
        $eventModel = $this->loadModel('Event');
        $userId = $_SESSION['user']['id'];

        // Pastikan event yang akan dihapus dimiliki oleh user yang sedang login
        $existingEvent = $eventModel->getById($id);
        if (!$existingEvent || $existingEvent['user_id'] !== $userId) {
            $_SESSION['error_message'] = "Event tidak ditemukan atau Anda tidak memiliki hak akses untuk menghapusnya.";
            header("Location: ?c=event&m=index");
            exit();
        }

        // Panggil method delete di model.
        $success = $eventModel->delete($id);

        if ($success) {
            $_SESSION['success_message'] = "Event berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus event. Silakan coba lagi.";
        }

        // Arahkan kembali ke halaman daftar event.
        header("Location: ?c=event&m=index");
        exit;
    }
}
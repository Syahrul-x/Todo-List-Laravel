<?php
require_once __DIR__ . '/Controller.php';
class EventController extends Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header("Location:?c=auth&m=login");
            exit();
        }
    }

    public function index()
    {
        $eventModel = $this->loadModel('Event');
        $userId = $_SESSION['user']['id'];
        $events = $eventModel->getAllByUser($userId);
        $this->loadView('events/index', ['events' => $events, 'username' => $_SESSION['user']['name']], 'main');
    }

    public function create()
    {
        $this->loadView('events/create', ['username' => $_SESSION['user']['name']], 'main');
    }

    public function store()
    {
        $eventModel = $this->loadModel('Event');
        $userId = $_SESSION['user']['id'];

        $eventName = trim($_POST['event_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $startTime = $_POST['start_time'] ?? '';
        $endTime = $_POST['end_time'] ?? null;
        $location = trim($_POST['location'] ?? '');

        // Validasi input
        if (empty($eventName) || empty($startTime)) {
            $_SESSION['error_message'] = "Nama Event dan Waktu Mulai tidak boleh kosong.";
            $_SESSION['old_input'] = $_POST;
            header("Location: ?c=event&m=create");
            exit();
        }

        $data = [
            'user_id' => $userId,
            'event_name' => $eventName,
            'description' => $description,
            'start_time' => $startTime,
            'location' => $location,
        ];

        if (!empty($endTime)) {
            $data['end_time'] = $endTime;
        }

        $success = $eventModel->create($data);

        header("Location: ?c=event&m=index");
        exit;
    }

    public function edit($id = null)
    {
        if (!$id) {
            header("Location: ?c=event&m=index");
            exit;
        }

        $eventModel = $this->loadModel('Event');

        $event = $eventModel->getById($id);

        if (!$event || $event['user_id'] !== $_SESSION['user']['id']) {
            $_SESSION['error_message'] = "Event tidak ditemukan atau Anda tidak memiliki akses.";
            header("Location: ?c=event&m=index");
            exit;
        }

        $this->loadView('events/edit', ['event' => $event, 'username' => $_SESSION['user']['name']], 'main');
    }

    public function saveUpdate($id = null)
    {
        if ($id === null) {
            $id = $_POST['id'] ?? $_GET['id'] ?? null;
        }

        if (!$id) {
            $_SESSION['error_message'] = "ID event tidak ditemukan.";
            header("Location: ?c=event&m=index");
            exit();
        }

        $eventModel = $this->loadModel('Event');
        $userId = $_SESSION['user']['id'];

        $existingEvent = $eventModel->getById($id);
        if (!$existingEvent || $existingEvent['user_id'] !== $userId) {
            $_SESSION['error_message'] = "Event tidak ditemukan atau Anda tidak memiliki hak akses untuk mengubahnya.";
            header("Location: ?c=event&m=index");
            exit();
        }

        $eventName = trim($_POST['event_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $startTime = $_POST['start_time'] ?? '';
        $endTime = $_POST['end_time'] ?? null; 
        $location = trim($_POST['location'] ?? '');

        if (empty($eventName) || empty($startTime)) {
            $_SESSION['error_message'] = "Nama Event dan Waktu Mulai tidak boleh kosong.";
            header("Location: ?c=event&m=edit&id=" . $id);
            exit();
        }

        $data = [
            'event_name' => $eventName,
            'description' => $description,
            'start_time' => $startTime,
            'location' => $location,
        ];

        if (!empty($endTime)) {
            $data['end_time'] = $endTime;
        } else {
            $data['end_time'] = null;
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

    public function delete($id = null)
    {
        if (!$id) {
            header("Location: ?c=event&m=index");
            exit;
        }

        // Memuat model 'Event'.
        $eventModel = $this->loadModel('Event');
        $userId = $_SESSION['user']['id'];

        $existingEvent = $eventModel->getById($id);
        if (!$existingEvent || $existingEvent['user_id'] !== $userId) {
            $_SESSION['error_message'] = "Event tidak ditemukan atau Anda tidak memiliki hak akses untuk menghapusnya.";
            header("Location: ?c=event&m=index");
            exit();
        }

        $success = $eventModel->delete($id);

        header("Location: ?c=event&m=index");
        exit;
    }
}
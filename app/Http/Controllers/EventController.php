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
        // Pastikan 'old_input' dan 'error_message' dihapus setelah dibaca di view
        // agar tidak muncul lagi jika user refresh halaman create
        $old_input = $_SESSION['old_input'] ?? [];
        $error_message = $_SESSION['error_message'] ?? null;
        unset($_SESSION['old_input']);
        unset($_SESSION['error_message']);

        $this->loadView('events/create', [
            'username' => $_SESSION['user']['name'],
            'old_input' => $old_input, // Teruskan ke view
            'error_message' => $error_message // Teruskan ke view
        ], 'main');
    }

    public function store()
    {
        header('Content-Type: application/json'); // Mengatur header untuk respons JSON
        $eventModel = $this->loadModel('Event');
        $userId = $_SESSION['user']['id'];

        $eventName = trim($_POST['event_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $startTime = $_POST['start_time'] ?? '';
        $endTime = $_POST['end_time'] ?? null;
        $location = trim($_POST['location'] ?? '');
        $includeEndTime = $_POST['include_end_time'] ?? 'off'; // Tangkap nilai checkbox

        // Validasi input
        if (empty($eventName) || empty($startTime)) {
            echo json_encode([
                'success' => false,
                'message' => "Nama Event dan Waktu Mulai tidak boleh kosong."
            ]);
            exit();
        }

        // Jika checkbox tidak dicentang, pastikan endTime menjadi null
        if ($includeEndTime === 'off') {
            $endTime = null;
        }

        $data = [
            'user_id' => $userId,
            'event_name' => $eventName,
            'description' => $description,
            'start_time' => $startTime,
            'location' => $location,
            'end_time' => $endTime // Pastikan end_time selalu ada di array data, meskipun null
        ];

        $success = $eventModel->create($data);

        if ($success) {
            echo json_encode([
                'success' => true,
                'message' => 'Event berhasil ditambahkan!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menambahkan event. Silakan coba lagi.'
            ]);
        }
        exit;
    }

    public function edit($id = null)
    {
        if (!$id) {
            $_SESSION['error_message'] = "ID event tidak ditemukan.";
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

        // Pastikan 'error_message' dan 'success_message' dihapus setelah dibaca di view
        $error_message = $_SESSION['error_message'] ?? null;
        $success_message = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message']);
        unset($_SESSION['success_message']);

        $this->loadView('events/edit', [
            'event' => $event,
            'username' => $_SESSION['user']['name'],
            'error_message' => $error_message, // Teruskan ke view
            'success_message' => $success_message // Teruskan ke view
        ], 'main');
    }


    public function saveUpdate($id = null)
    {
        header('Content-Type: application/json'); // Mengatur header untuk respons JSON
        if ($id === null) {
            $id = $_POST['id'] ?? $_GET['id'] ?? null;
        }

        if (!$id) {
            echo json_encode([
                'success' => false,
                'message' => "ID event tidak ditemukan."
            ]);
            exit();
        }

        $eventModel = $this->loadModel('Event');
        $userId = $_SESSION['user']['id'];

        $existingEvent = $eventModel->getById($id);
        if (!$existingEvent || $existingEvent['user_id'] !== $userId) {
            echo json_encode([
                'success' => false,
                'message' => "Event tidak ditemukan atau Anda tidak memiliki hak akses untuk mengubahnya."
            ]);
            exit();
        }

        $eventName = trim($_POST['event_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $startTime = $_POST['start_time'] ?? '';
        $endTime = $_POST['end_time'] ?? null;
        $location = trim($_POST['location'] ?? '');
        $includeEndTime = $_POST['include_end_time'] ?? 'off'; // Tangkap nilai checkbox

        if (empty($eventName) || empty($startTime)) {
            echo json_encode([
                'success' => false,
                'message' => "Nama Event dan Waktu Mulai tidak boleh kosong."
            ]);
            exit();
        }

        // Jika checkbox tidak dicentang, pastikan endTime menjadi null
        if ($includeEndTime === 'off') {
            $endTime = null;
        }

        $data = [
            'event_name' => $eventName,
            'description' => $description,
            'start_time' => $startTime,
            'location' => $location,
            'end_time' => $endTime // Pastikan end_time selalu ada di array data, meskipun null
        ];

        $success = $eventModel->update($id, $data);

        if ($success) {
            echo json_encode([
                'success' => true,
                'message' => "Event '{$eventName}' berhasil diperbarui!"
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => "Gagal memperbarui event. Silakan coba lagi."
            ]);
        }
        exit();
    }

    public function delete($id = null)
    {
        header('Content-Type: application/json'); // Mengatur header untuk respons JSON
        if (!$id) {
            echo json_encode([
                'success' => false,
                'message' => "ID event tidak ditemukan."
            ]);
            exit();
        }

        $eventModel = $this->loadModel('Event');
        $userId = $_SESSION['user']['id'];

        $existingEvent = $eventModel->getById($id);
        if (!$existingEvent || $existingEvent['user_id'] !== $userId) {
            echo json_encode([
                'success' => false,
                'message' => "Event tidak ditemukan atau Anda tidak memiliki hak akses untuk menghapusnya."
            ]);
            exit();
        }

        $success = $eventModel->delete($id);

        if ($success) {
            echo json_encode([
                'success' => true,
                'message' => 'Event berhasil dihapus!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus event. Silakan coba lagi.'
            ]);
        }
        exit;
    }
}
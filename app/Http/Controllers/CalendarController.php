<?php
// app/Http/Controllers/CalendarController.php

require_once __DIR__ . '/Controller.php';

class CalendarController extends Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            // Redirect to login if not authenticated
            header("Location:?c=auth&m=login");
            exit();
        }
    }

    /**
     * Display the main calendar page.
     */
    public function index()
    {
        $userId = $_SESSION['user']['id'];

        $eventModel = $this->loadModel('Event');
        $reminderModel = $this->loadModel('Reminder'); // Load reminder model

        $events = $eventModel->getAllByUser($userId);
        $reminders = $reminderModel->getCalendarEntriesForUser($userId); // Ambil semua reminder

        // Buat pemetaan dari event_id ke reminder untuk pencarian cepat
        $reminderMap = [];
        foreach ($reminders as $reminder) {
            $key = $reminder['entry_type'] . '-' . $reminder['entry_id'];
            $reminderMap[$key] = $reminder;
        }

        $calendarEntries = [];

        // Format data event untuk kalender
        foreach ($events as $event) {
            $key = 'event-' . $event['id'];
            $existingReminder = $reminderMap[$key] ?? null;

            $calendarEntries[] = [
                'id'          => 'event-' . $event['id'],
                'entry_id'    => $event['id'],
                'entry_type'  => 'event',
                'title'       => htmlspecialchars($event['event_name']),
                'start'       => $event['start_time'],
                'end'         => $event['end_time'],
                'description' => htmlspecialchars($event['description'] ?? 'No description.'),
                'color'       => '#ffc107',
                'textColor'   => 'black',
                // Sisipkan data reminder jika ada
                'reminder_id' => $existingReminder['id'] ?? null,
            ];
        }

        $this->loadView('calendar/index', [
            'title'           => 'My Calendar',
            'username'        => $_SESSION['user']['name'],
            'calendarEntries' => $calendarEntries
        ], 'main');
    }

    
    /**
     * Show form to create a new reminder for a task or event.
     */
    public function create()
    {
        $taskId = $_GET['task_id'] ?? null;
        $eventId = $_GET['event_id'] ?? null;
        $item = null;
        $itemType = null;

        if ($taskId) {
            $taskModel = $this->loadModel('Tugas');
            $item = $taskModel->getTaskByIdAndUserId($taskId, $_SESSION['user']['id']);
            $itemType = 'task';
        } elseif ($eventId) {
            $eventModel = $this->loadModel('Event');
            $item = $eventModel->getById($eventId);
            // Additional check to ensure the event belongs to the user
            if ($item && $item['user_id'] != $_SESSION['user']['id']) {
                $item = null;
            }
            $itemType = 'event';
        }

        if (!$item) {
            $_SESSION['error_message'] = "Task or Event not found or you don't have access.";
            header("Location: ?c=dashboard&m=index");
            exit();
        }

        $this->loadView('calendar/create', [
            'title' => 'Create New Reminder',
            'username' => $_SESSION['user']['name'],
            'item' => $item,
            'itemType' => $itemType
        ], 'main');
    }

    /**
     * Store a new reminder in the database.
     */
    public function store()
    {
        $userId = $_SESSION['user']['id'];
        $taskId = $_POST['task_id'] ?? null;
        $eventId = $_POST['event_id'] ?? null;
        $reminderTime = $_POST['reminder_time'] ?? '';
        $message = trim($_POST['message'] ?? '');

        if (empty($reminderTime) || (!$taskId && !$eventId)) {
            $_SESSION['error_message'] = "Reminder time and a related Task/Event are required.";
            // Redirect back to dashboard as we don't know which create form to return to
            header("Location: ?c=dashboard&m=index");
            exit();
        }

        $data = [
            'user_id' => $userId,
            'task_id' => $taskId ? (int)$taskId : null,
            'event_id' => $eventId ? (int)$eventId : null,
            'reminder_time' => $reminderTime,
            'message' => $message
        ];

        $reminderModel = $this->loadModel('Reminder');
        if ($reminderModel->create($data)) {
            $_SESSION['success_message'] = "Reminder successfully created!";
        } else {
            $_SESSION['error_message'] = "Failed to create reminder. A reminder for this item may already exist.";
        }
        
        header("Location: ?c=calendar&m=index");
        exit();
    }

    /**
     * Update an existing reminder.
     */
    public function update()
    {
        $reminderId = $_POST['reminder_id'] ?? null;
        $userId = $_SESSION['user']['id'];
        
        if (!$reminderId) {
            $_SESSION['error_message'] = "Invalid request.";
            header("Location: ?c=calendar&m=index");
            exit();
        }

        $data = [
            'reminder_time' => $_POST['reminder_time'],
            'message' => trim($_POST['message'] ?? '')
        ];
        
        $reminderModel = $this->loadModel('Reminder');
        if ($reminderModel->update($reminderId, $data, $userId)) {
            $_SESSION['success_message'] = "Reminder updated successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to update reminder.";
        }
        
        header("Location: ?c=calendar&m=index");
        exit();
    }
    /**
     * Menampilkan form untuk mengedit reminder.
     */
    public function edit()
    {
        $reminderId = $_GET['reminder_id'] ?? null;
        $userId = $_SESSION['user']['id'];

        if (!$reminderId) {
            $_SESSION['error_message'] = "Reminder tidak ditemukan.";
            header("Location: ?c=calendar&m=index");
            exit();
        }

        $reminderModel = $this->loadModel('Reminder');
        $reminder = $reminderModel->getReminderWithDetails($reminderId, $userId);

        if (!$reminder) {
            $_SESSION['error_message'] = "Reminder tidak ditemukan atau Anda tidak memiliki akses.";
            header("Location: ?c=calendar&m=index");
            exit();
        }

        $this->loadView('calendar/edit', [
            'title' => 'Edit Reminder',
            'username' => $_SESSION['user']['name'],
            'reminder' => $reminder
        ], 'main');
    }
    /**
     * Delete a reminder.
     */
    public function delete()
    {
        $reminderId = $_POST['reminder_id'] ?? null;
        $userId = $_SESSION['user']['id'];
        
        if (!$reminderId) {
            $_SESSION['error_message'] = "Invalid request.";
            header("Location: ?c=calendar&m=index");
            exit();
        }
        
        $reminderModel = $this->loadModel('Reminder');
        if ($reminderModel->delete($reminderId, $userId)) {
            $_SESSION['success_message'] = "Reminder deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to delete reminder.";
        }
        
        header("Location: ?c=calendar&m=index");
        exit();
    }
}
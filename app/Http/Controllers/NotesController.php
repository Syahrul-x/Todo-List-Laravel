<?php

require_once __DIR__. '/Controller.php';

class NotesController extends Controller
{
    public function __construct() {
    session_start();
    if (!isset($_SESSION['user'])) {
      header("Location:?c=auth&m=login");
      exit();
    }
    }

    public function index()
    {
        $notesModel = $this->loadModel('Notes');
        $userId = $_SESSION['user']['id'];
        $notes = $notesModel->getAllByUser($userId);
        if (!isset($_SESSION['user'])) {
            header("Location:?c=auth&m=login");
            exit();
        }
        $this->loadView('notes/meetingnotes', ['notes' => $notes,'username' => $_SESSION['user']['name']], 'main');
    }

    public function create()
    {
        $this->loadView('notes/createnotes', ['username' => $_SESSION['user']['name']], 'main');
    }

    public function store()
    {
        $notesModel = $this->loadModel('Notes');
        $userId = $_SESSION['user']['id'];

        $data = [
            'user_id' => $userId,
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];

        $notesModel->create($data);

        header("Location: ?c=notes&m=index");
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: ?c=notes&m=index");
            exit;
        }

        $notesModel = $this->loadModel('Notes');
        $note = $notesModel->getById($id);

        $this->loadView('notes/editnotes', ['note' => $note,'username' => $_SESSION['user']['name']], 'main');
    }

    public function update()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: ?c=notes&m=index");
            exit;
        }

        $notesModel = $this->loadModel('Notes');
        $note = $notesModel->getById($id);

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];

        $notesModel->update($id, $data);

        header("Location: ?c=notes&m=index");
        exit;
    }

    public function destroy()
    {
        $id = $_GET['id'] ?? null;
        $userId = $_SESSION['user']['id'];
        if (!$id) {
            header("Location: ?c=notes&m=index");
            exit;
        }

        $notesModel = $this->loadModel('Notes');

        $notesModel->delete($id);

        header("Location: ?c=notes&m=index");
        exit;
    }
}
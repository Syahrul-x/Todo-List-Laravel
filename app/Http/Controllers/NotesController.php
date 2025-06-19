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

    public function manage()
    {
        // 1. OTORISASI: Selalu lakukan pengecekan hak akses di paling awal.
        // Gunakan === (perbandingan ketat) untuk membandingkan username.
        if (!isset($_SESSION['user']) || $_SESSION['user']['name'] !== 'admin') {
            // Jika user tidak login ATAU namanya BUKAN 'admin', alihkan ke halaman index.
            header("Location: ?c=notes&m=index");
            exit();
        }

        // 2. JIKA LOLOS OTORISASI, baru proses pengambilan data.
        $notesModel = $this->loadModel('Notes');
        
        // Simpan hasil dari getAll() ke dalam variabel $notes.
        $notes = $notesModel->getAll();

        // 3. Muat view dengan data yang sudah benar dan siap digunakan.
        $this->loadView('notes/managenotes', [
            'notes'    => $notes,
            'username' => $_SESSION['user']['name']
        ], 'main');
    }

        public function search()
    {
        header('Content-Type: application/json');
        $notesModel = $this->loadModel('Notes');
        $searchTerm = $_GET['search'] ?? '';

        $notes = [];
        // Cek apakah user adalah admin
        if (isset($_SESSION['user']) && $_SESSION['user']['name'] === 'admin') {
            // Jika admin dan ada parameter 'context' bernilai 'manage', cari semua notes
            if (isset($_GET['context']) && $_GET['context'] === 'manage') {
                $notes = $notesModel->searchAll($searchTerm);
            } else {
                 // Jika admin tapi di halaman biasa (meetingnotes), cari notes miliknya saja
                 $userId = $_SESSION['user']['id'];
                 $notes = $notesModel->searchByUser($searchTerm, $userId);
            }
        } else {
            // Jika bukan admin, cari hanya notes miliknya
            $userId = $_SESSION['user']['id'];
            $notes = $notesModel->searchByUser($searchTerm, $userId);
        }

        echo json_encode($notes);
        exit();
    }
}
<?php
// app/Http/Controllers/PriorityController.php

// Ensure Controller.php is included so PriorityController can extend it
require_once __DIR__ . '/Controller.php';

class PriorityController extends Controller
{
    protected $priorityModel;

    public function __construct()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Redirect to login if user is not authenticated
        if (!isset($_SESSION['user'])) {
            header("Location:?c=auth&m=login");
            exit();
        }
        // Load the Priority model
        $this->priorityModel = $this->loadModel("Priority");
    }

    // Displays the list of all priorities.
    public function index()
    {
        $priorities = $this->priorityModel->getAllPriorities(); // Get all priorities from the model
        // Load the view, passing priorities and title, using 'main' layout
        $this->loadView("priority/index", ['priorities' => $priorities, 'title' => 'Priority Management'], "main");
    }

    // Handles displaying the creation form and processing new priority submissions.
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            // Validate name input
            if (empty($name)) {
                $_SESSION['error_message'] = "Nama prioritas tidak boleh kosong"; // Set error message in session
                // Reload the view with previous inputs and error message
                $this->loadView("priority/create", [
                    'error' => $_SESSION['error_message'], // Pass error to the view
                    'name' => $name,
                    'description' => $description,
                    'title' => 'Tambah Prioritas'
                ], "main");
                unset($_SESSION['error_message']); // Clear error after displaying
                return;
            }

            // Attempt to create the new priority
            $success = $this->priorityModel->create($name, $description);
            if ($success) {
                $_SESSION['success_message'] = "Prioritas '$name' berhasil ditambahkan."; // Set success message
            } else {
                $_SESSION['error_message'] = "Gagal menambahkan prioritas. Nama mungkin sudah ada."; // Set error message
            }
            // Redirect to the index page
            header("Location:?c=priority&m=index");
            exit();
        }

        // Display the create form for GET requests
        $this->loadView("priority/create", ['title' => 'Tambah Prioritas'], "main");
    }

    // Handles displaying the edit form and processing updates for an existing priority.
    public function edit()
    {
        $id = $_GET['id'] ?? null; // Get ID from GET parameter
        // If ID is missing, redirect to index
        if (!$id) {
            $_SESSION['error_message'] = "ID prioritas tidak ditemukan.";
            header("Location:?c=priority&m=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            // Validate name input
            if (empty($name)) {
                $priority = $this->priorityModel->getById($id); // Re-fetch for displaying in form
                $_SESSION['error_message'] = "Nama prioritas tidak boleh kosong";
                $this->loadView("priority/edit", [
                    'error' => $_SESSION['error_message'],
                    'priority' => $priority,
                    'title' => 'Edit Prioritas'
                ], "main");
                unset($_SESSION['error_message']);
                return;
            }

            // Attempt to update the priority
            $success = $this->priorityModel->update($id, $name, $description);
            if ($success) {
                $_SESSION['success_message'] = "Prioritas '$name' berhasil diperbarui.";
            } else {
                $_SESSION['error_message'] = "Gagal memperbarui prioritas. Nama mungkin sudah ada atau ID tidak valid.";
            }
            // Redirect to the index page
            header("Location:?c=priority&m=index");
            exit();
        }

        // Display the edit form for GET requests
        $priority = $this->priorityModel->getById($id); // Fetch the priority to be edited
        // If priority not found, redirect
        if (!$priority) {
            $_SESSION['error_message'] = "Prioritas tidak ditemukan.";
            header("Location:?c=priority&m=index");
            exit();
        }

        $this->loadView("priority/edit", ['priority' => $priority, 'title' => 'Edit Prioritas'], "main");
    }

    // Deletes a priority.
    public function delete()
    {
        $id = $_GET['id'] ?? null; // Get ID from GET parameter
        // If ID is missing, redirect to index
        if (!$id) {
            $_SESSION['error_message'] = "ID prioritas tidak ditemukan.";
            header("Location:?c=priority&m=index");
            exit();
        }

        // Check if the priority is in use by any tasks before attempting deletion
        if ($this->priorityModel->checkIfPriorityInUse($id)) {
            $_SESSION['error_message'] = "Prioritas ini sedang digunakan pada tugas dan tidak dapat dihapus.";
        } else {
            // Attempt to delete the priority
            $success = $this->priorityModel->delete($id);
            if ($success) {
                $_SESSION['success_message'] = "Prioritas berhasil dihapus.";
            } else {
                $_SESSION['error_message'] = "Gagal menghapus prioritas.";
            }
        }
        // Redirect to the index page
        header("Location:?c=priority&m=index");
        exit();
    }

    // Handles AJAX search requests for priorities.
    public function search()
    {
        header('Content-Type: application/json'); // Set header for JSON response
        $searchTerm = $_GET['search'] ?? ''; // Get search term

        $priorities = [];
        if (!empty($searchTerm)) {
            $priorities = $this->priorityModel->searchPriorities($searchTerm); // Perform search
        } else {
            $priorities = $this->priorityModel->getAllPriorities(); // Get all if no search term
        }

        echo json_encode($priorities); // Return results as JSON
        exit();
    }
}
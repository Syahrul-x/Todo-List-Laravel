<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Priority Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            transition: background-color 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen p-6">

<div class="max-w-4xl mx-auto bg-[#1e1e1e] rounded-lg p-6 shadow-lg">
    <h1 class="text-3xl font-semibold mb-6">Priority Management</h1>

    <div class="mb-4">
        <input type="text" id="prioritySearchInput" class="w-full p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search Priorities by Name...">
    </div>

    <a href="?c=priority&m=create" class="inline-block mb-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded text-white transition">
        <i class="fas fa-plus mr-2"></i> Add New Priority
    </a>
    

    <?php if (isset($_SESSION['error_message'])): ?>
        <div id="error-message" class="mb-4 p-3 bg-red-600 text-white rounded font-medium">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div id="success-message" class="mb-4 p-3 bg-green-600 text-white rounded font-medium">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (!empty($priorities)): ?>
        <div class="overflow-x-auto overflow-y-auto">
            <table class="min-w-full bg-[#303030] rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-[#4a4a4a] text-left text-gray-300 uppercase text-sm leading-normal">
                        <th class="py-3 px-6">Name</th>
                        <th class="py-3 px-6">Description</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-200 text-sm font-light" id="priorityTableBody">
                    <?php foreach ($priorities as $priority): ?>
                        <tr id="priority-<?= $priority->id ?>" class="border-b border-gray-600 hover:bg-[#3a3a3a]">
                            <td class="py-3 px-6 whitespace-nowrap"><?= htmlspecialchars($priority->name) ?></td>
                            <td class="py-3 px-6"><?= htmlspecialchars($priority->description) ?></td>
                            <td class="py-3 px-6 whitespace-nowrap text-center">
                                <a href="?c=priority&m=edit&id=<?= $priority->id ?>" class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>
                                <a href="?c=priority&m=delete&id=<?= $priority->id ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Yakin ingin menghapus prioritas ini? Ini akan mengatur prioritas tugas terkait menjadi NULL.')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-6 text-gray-400">No priorities found.</div>
    <?php endif; ?>

</div>

<script>
    // Function for AJAX search
    $('#prioritySearchInput').on('input', function() {
        var searchTerm = $(this).val();

        $.ajax({
            url: '?c=priority&m=search',
            method: 'GET',
            data: { search: searchTerm },
            success: function(response) {
                var priorities = response; // response is already parsed JSON due to header('Content-Type: application/json')
                var tableBody = $('#priorityTableBody');
                tableBody.empty();

                if (priorities.length > 0) {
                    priorities.forEach(function(priority) {
                        tableBody.append('<tr id="priority-' + priority.id + '" class="border-b border-gray-600 hover:bg-[#3a3a3a]">' +
                            '<td class="py-3 px-6 whitespace-nowrap">' + priority.name + '</td>' +
                            '<td class="py-3 px-6">' + priority.description + '</td>' +
                            '<td class="py-3 px-6 text-center">' +
                                '<a href="?c=priority&m=edit&id=' + priority.id + '" class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>' +
                                '<a href="?c=priority&m=delete&id=' + priority.id + '" class="text-red-500 hover:text-red-700" onclick="return confirm(\'Yakin ingin menghapus prioritas ini? Ini akan mengatur prioritas tugas terkait menjadi NULL.\')">Delete</a>' +
                            '</td>' +
                        '</tr>');
                    });
                } else {
                    tableBody.append('<tr><td colspan="3" class="text-center text-gray-400 py-4">No priorities found.</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });

    // Hide messages after 3 seconds
    document.addEventListener("DOMContentLoaded", function() {
        var errorMessage = document.getElementById('error-message');
        var successMessage = document.getElementById('success-message');

        if (errorMessage) {
            setTimeout(function() {
                errorMessage.style.display = 'none';
            }, 3000);
        }
        if (successMessage) {
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 3000);
        }
    });
</script>
</body>
</html>
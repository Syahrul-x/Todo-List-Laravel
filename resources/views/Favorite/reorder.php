<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Urutan Favorit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <style>
        body {
            transition: background-color 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        .sortable-ghost {
            opacity: 0.4;
        }
        .sortable-drag {
            opacity: 0.8;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen p-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-[#1e1e1e] p-6 rounded-lg shadow-lg text-white">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-white">
                    <i class="fas fa-sort mr-3"></i>
                    Atur Urutan Favorit
                </h2>
                <a href="?c=favorite&m=index" class="bg-[#2684FF] hover:bg-[#006bb3] text-white font-semibold py-2 px-4 rounded-full transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <div class="mb-4 text-gray-400">
                <i class="fas fa-info-circle mr-2"></i>
                Seret dan lepas untuk mengatur urutan tugas favorit
            </div>

            <?php if (!empty($favorites)): ?>
                <div id="sortable-list" class="space-y-3">
                    <?php foreach ($favorites as $index => $favorite): ?>
                        <div class="bg-[#303030] p-4 rounded-lg cursor-move hover:bg-[#3a3a3a] transition" 
                             data-task-id="<?= htmlspecialchars($favorite['task_id']) ?>">
                            <div class="flex items-center">
                                <i class="fas fa-grip-vertical text-gray-500 mr-4"></i>
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium">
                                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                                        <?= htmlspecialchars($favorite['title']) ?>
                                    </h3>
                                    <p class="text-gray-400 text-sm mt-1">
                                        <?= htmlspecialchars(substr($favorite['description'] ?? '', 0, 100)) ?>
                                        <?= strlen($favorite['description'] ?? '') > 100 ? '...' : '' ?>
                                    </p>
                                    <div class="mt-2">
                                        <span class="text-xs text-gray-500">
                                            Kategori: <?= htmlspecialchars($favorite['category_name'] ?? '-') ?>
                                        </span>
                                        <span class="ml-4 py-1 px-2 rounded-full text-xs 
                                            <?php 
                                                if ($favorite['status'] == 'completed' || $favorite['status'] == 'Selesai') {
                                                    echo 'bg-green-600';
                                                } elseif ($favorite['status'] == 'Sedang Dikerjakan') {
                                                    echo 'bg-blue-600';
                                                } else {
                                                    echo 'bg-yellow-600';
                                                }
                                            ?>
                                        ">
                                            <?= htmlspecialchars($favorite['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-6 text-center">
                    <button id="save-order" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-full transition">
                        <i class="fas fa-save mr-2"></i>Simpan Urutan
                    </button>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-star text-gray-600 text-5xl mb-4"></i>
                    <p class="text-gray-400 text-lg">Belum ada tugas favorit untuk diatur.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Initialize Sortable
        const sortableList = document.getElementById('sortable-list');
        if (sortableList) {
            const sortable = Sortable.create(sortableList, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
            });

            // Save order button
            document.getElementById('save-order').addEventListener('click', function() {
                const items = sortableList.querySelectorAll('[data-task-id]');
                const orders = [];
                
                items.forEach((item, index) => {
                    orders.push({
                        task_id: item.dataset.taskId,
                        position: index + 1
                    });
                });

                // Send AJAX requests to update order
                let successCount = 0;
                let errorOccurred = false; // Flag to track if any error occurred

                orders.forEach(order => {
                    fetch('?c=favorite&m=updateOrder', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `task_id=${order.task_id}&position=${order.position}` 
                    })
                    .then(response => {
                        if (!response.ok) { // Check if HTTP status is 200-299
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            successCount++;
                            if (successCount === orders.length && !errorOccurred) {
                                alert('Urutan berhasil disimpan!');
                                window.location.href = '?c=favorite&m=index';
                            }
                        } else {
                            errorOccurred = true;
                            console.error('Server reported error:', data.message);
                            alert('Terjadi kesalahan saat menyimpan urutan: ' + (data.message || 'Unknown error'));
                            // Optional: stop processing further if an error occurs
                            // throw new Error('Server reported failure'); 
                        }
                    })
                    .catch(error => {
                        errorOccurred = true;
                        console.error('Error in fetch operation:', error);
                        alert('Terjadi kesalahan koneksi atau data: ' + error.message);
                    });
                });
            });
        }
    </script>
</body>
</html>

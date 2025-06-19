<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS tambahan untuk responsivitas */
        @media (max-width: 640px) {
            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-section > form {
                width: 100%;
            }
            .filter-section select {
                width: 100%;
                margin-bottom: 0.75rem; /* Space between dropdowns on small screens */
            }
            .filter-section input[type="text"] {
                width: 100%;
                margin-bottom: 0.75rem;
            }
        }
    </style>
</head>

<body class="bg-gray-900 min-h-screen p-4">
    <div class="max-w-6xl mx-auto">
        <div class="bg-[#1e1e1e] p-6 rounded-lg shadow-lg text-white">
            <div class="flex flex-wrap justify-between items-center mb-6 gap-3">
                <h2 class="text-2xl font-semibold text-white flex-shrink-0 w-full text-center sm:text-left">
                    Daftar Tugas
                </h2>
                <div class="flex flex-wrap justify-between items-center w-full gap-3 filter-section">
                    <div class="flex gap-3">
                        <a href="?c=favorite&m=index"
                            class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold w-12 h-12 flex items-center justify-center rounded-full transition duration-300 ease-in-out transform hover:scale-105"
                            title="Tugas Favorit">
                            <i class="fas fa-star text-xl"></i>
                        </a>
                        <a href="?c=tugas&m=create"
                            class="bg-[#2684FF] hover:bg-[#006bb3] text-white font-semibold w-12 h-12 flex items-center justify-center rounded-full transition duration-300 ease-in-out transform hover:scale-105"
                            title="Tambah Tugas Baru">
                            <i class="fas fa-plus text-xl"></i>
                        </a>
                    </div>

                    <form method="GET" action="" class="inline-block flex-grow" id="taskFilterForm">
                        <input type="hidden" name="c" value="dashboard" />
                        <input type="hidden" name="m" value="index" />
                        <input type="text" name="search" id="taskSearchInput" placeholder="Cari tugas berdasarkan judul..."
                               value="<?= htmlspecialchars($searchTerm ?? '') ?>"
                               class="w-full p-2 rounded-full bg-[#2c2e31] border border-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </form>

                    <form method="GET" action="" class="inline-block">
                        <input type="hidden" name="c" value="dashboard" />
                        <input type="hidden" name="m" value="index" />
                        <select name="category_id" id="categoryFilterSelect"
                            class="bg-[#2c2e31] border border-gray-700 rounded-full text-gray-100 font-semibold py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <option value="">Filter by Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat->id) ?>" <?= (isset($selectedCategory) && $selectedCategory == $cat->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>

                    <form method="GET" action="" class="inline-block">
                        <input type="hidden" name="c" value="dashboard" />
                        <input type="hidden" name="m" value="index" />
                        <select name="priority_id" id="priorityFilterSelect"
                            class="bg-[#2c2e31] border border-gray-700 rounded-full text-gray-100 font-semibold py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <option value="">Filter by Priority</option>
                            <?php foreach ($priorities as $prio): ?>
                                <option value="<?= htmlspecialchars($prio->id) ?>" <?= (isset($selectedPriority) && $selectedPriority == $prio->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($prio->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="mb-4 p-3 bg-red-600 text-white rounded font-medium" id="error-message">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="mb-4 p-3 bg-green-600 text-white rounded font-medium" id="success-message">
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <div class="overflow-x-auto overflow-y-auto">
                <table class="min-w-full bg-[#303030] rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-[#4a4a4a] text-left text-gray-300 uppercase text-sm leading-normal">
                            <th class="py-3 px-6">Judul</th>
                            <th class="py-3 px-6">Deskripsi</th>
                            <th class="py-3 px-6">Status</th>
                            <th class="py-3 px-6">Kategori</th>
                            <th class="py-3 px-6">Prioritas</th>
                            <th class="py-3 px-6">Tanggal Dibuat</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-200 text-sm font-light" id="taskTableBody">
                        <?php if (!empty($tasks)): ?>
                            <?php foreach ($tasks as $task): ?>
                                <tr class="border-b border-gray-600 hover:bg-[#3a3a3a]">
                                    <td class="py-3 px-6 whitespace-nowrap"><?= htmlspecialchars($task['title']) ?></td>
                                    <td class="py-3 px-6">
                                        <?= htmlspecialchars(substr($task['description'] ?? '', 0, 50)) ?>
                                        <?= strlen($task['description'] ?? '') > 50 ? '...' : '' ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <span class="py-1 px-3 rounded-full text-xs
                                            <?php
                                                if ($task['status'] == 'Selesai') {
                                                    echo 'bg-green-600';
                                                } elseif ($task['status'] == 'Sedang Dikerjakan') {
                                                    echo 'bg-blue-600';
                                                } else {
                                                    echo 'bg-yellow-600';
                                                }
                                            ?>">
                                            <?= htmlspecialchars($task['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <?= htmlspecialchars($task['category_name'] ?? '-') ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <?= htmlspecialchars($task['priority_name'] ?? '-') ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap"><?= htmlspecialchars($task['created_at']) ?></td>
                                    <td class="py-3 px-6 whitespace-nowrap text-center">
                                        <a href="?c=favorite&m=toggle&task_id=<?= htmlspecialchars($task['id']) ?>"
                                            class="text-yellow-500 hover:text-yellow-600 font-medium mr-3"
                                            title="<?= (isset($task['is_favorited']) && $task['is_favorited']) ? 'Hapus dari favorit' : 'Tambahkan ke favorit' ?>">
                                            <i class="<?= (isset($task['is_favorited']) && $task['is_favorited']) ? 'fas' : 'far' ?> fa-star"></i>
                                        </a>
                                        <a href="?c=tugas&m=update&id=<?= htmlspecialchars($task['id']) ?>"
                                            class="text-[#2684FF] hover:text-[#006bb3] font-medium mr-3">Edit</a>
                                        <a href="?c=tugas&m=delete&id=<?= htmlspecialchars($task['id']) ?>"
                                            onclick="return confirm('Yakin ingin menghapus tugas ini?')"
                                            class="text-red-500 hover:text-red-700 font-medium">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="py-6 px-6 text-center text-gray-400">Belum ada tugas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="js/taskManagement.js"></script>
</body>
</html>
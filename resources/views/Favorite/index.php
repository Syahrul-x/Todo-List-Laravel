<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Favorit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            transition: background-color 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen p-4">
    <div class="max-w-6xl mx-auto">
        <div class="bg-[#1e1e1e] p-6 rounded-lg shadow-lg text-white">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-white flex items-center">
                    <i class="fas fa-star text-yellow-500 mr-3"></i>
                    Tugas Favorit
                </h2>
                <div class="flex flex-col md:flex-row md:justify-between items-center gap-3 p-4">
                    <a href="?c=favorite&m=reorder" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-full transition 
                    text-sm md:text-base lg:text-lg w-full md:w-auto text-center">
                        <i class="fas fa-sort mr-2"></i>Atur Urutan
                    </a>
                    <a href="?c=dashboard&m=index" class="bg-[#2684FF] hover:bg-[#006bb3] text-white font-semibold py-2 px-4 rounded-full transition
                    text-sm md:text-base lg:text-lg w-full md:w-auto text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="mb-4 p-3 bg-red-600 text-white rounded font-medium">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="mb-4 p-3 bg-green-600 text-white rounded font-medium">
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <div class="overflow-x-auto">
                <?php if (!empty($favorites)): ?>
                    <table class="min-w-full bg-[#303030] rounded-lg overflow-hidden">
                        <thead>
                            <tr class="bg-[#4a4a4a] text-left text-gray-300 uppercase text-sm leading-normal">
                                <th class="py-3 px-6">No</th>
                                <th class="py-3 px-6">Judul</th>
                                <th class="py-3 px-6">Deskripsi</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6">Kategori</th>
                                <th class="py-3 px-6">Ditambahkan</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-200 text-sm font-light">
                            <?php $no = 1; ?>
                            <?php foreach ($favorites as $favorite): ?>
                                <tr class="border-b border-gray-600 hover:bg-[#3a3a3a]">
                                    <td class="py-3 px-6 whitespace-nowrap"><?= $no++ ?></td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <span class="flex items-center">
                                            <i class="fas fa-star text-yellow-500 mr-2"></i>
                                            <?= htmlspecialchars($favorite['title']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <?= htmlspecialchars(substr($favorite['description'] ?? '', 0, 50)) ?>
                                        <?= strlen($favorite['description'] ?? '') > 50 ? '...' : '' ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <span class="py-1 px-3 rounded-full text-xs 
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
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <?= htmlspecialchars($favorite['category_name'] ?? '-') ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <?= date('d/m/Y', strtotime($favorite['created_at'])) ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap text-center">
                                        <a href="?c=tugas&m=update&id=<?= htmlspecialchars($favorite['task_id']) ?>" 
                                           class="text-[#2684FF] hover:text-[#006bb3] font-medium mr-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?c=favorite&m=toggle&task_id=<?= htmlspecialchars($favorite['task_id']) ?>&redirect=favorite" 
                                           onclick="return confirm('Hapus dari favorit?')"
                                           class="text-red-500 hover:text-red-700 font-medium">
                                            <i class="fas fa-heart-broken"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center py-12">
                        <i class="fas fa-star text-gray-600 text-5xl mb-4"></i>
                        <p class="text-gray-400 text-lg">Belum ada tugas favorit.</p>
                        <a href="?c=dashboard&m=index" class="mt-4 inline-block text-[#2684FF] hover:text-[#006bb3]">
                            Lihat semua tugas untuk menambahkan favorit
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
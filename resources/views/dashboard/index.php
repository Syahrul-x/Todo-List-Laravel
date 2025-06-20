<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= htmlspecialchars($username ?? 'User') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Opsional: Transisi halus untuk mode gelap */
        body {
            transition: background-color 0.3s ease;
            font-family: 'Inter', sans-serif; /* Menggunakan font Inter */
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <div class="flex flex-col lg:flex-row min-h-screen">
        <aside class="w-full lg:w-1/4 bg-[#2c2e31] p-4 shadow-lg">
            <div class="user-info-sidebar text-center mb-6">
                <div class="flex justify-center items-center w-16 h-16 rounded-full bg-[#ff5630] text-white text-2xl font-bold mx-auto">
                    <?php echo htmlspecialchars(substr($username ?? 'US', 0, 2)); ?>
                </div>
                <div class="text-white mt-2 font-medium"><?= htmlspecialchars($username ?? 'Pengguna') ?></div>
            </div>

            <nav class="mt-4">
                <h3 class="text-gray-400 mb-3 uppercase tracking-wider text-sm">Content</h3>
                <ul class="space-y-3">
                    <li><a href="?c=calendar&m=index" class="flex items-center p-2 rounded-md text-[#e0e0e0] hover:bg-[#3a3a3a] hover:text-[#2684FF] transition-colors"><span class="mr-2">📅</span> Calendar</a></li>

                    <li><a href="#" class="flex items-center p-2 rounded-md text-[#e0e0e0] hover:bg-[#3a3a3a] hover:text-[#2684FF] transition-colors"><span class="mr-2">📄</span> All content</a></li>
                    <li><a href="?c=dashboard&m=index" class="flex items-center p-2 rounded-md text-[#e0e0e0] hover:bg-[#3a3a3a] hover:text-[#2684FF] transition-colors"><span class="mr-2">✅</span> <?= htmlspecialchars($username ?? 'Pengguna') ?>'s task list</a></li>
                    <li><a href="?c=notes&m=index" class="flex items-center p-2 rounded-md text-[#e0e0e0] hover:bg-[#3a3a3a] hover:text-[#2684FF] transition-colors"><span class="mr-2">📝</span> Meeting notes</a></li>
                    <li><a href="?c=event&m=index" class="flex items-center p-2 rounded-md text-[#e0e0e0] hover:bg-[#3a3a3a] hover:text-[#2684FF] transition-colors"><span class="mr-2">🗓️</span> Event List</a></li>
                </ul>
            </nav>
        </aside>

        <main class="flex-1 bg-[#1e1e1e] p-6 overflow-y-auto">
            <nav class="breadcrumb text-[#a9b7c6] mb-6">
                <a href="#" class="hover:text-white">Home</a> <span class="mx-2">/</span> <span class="text-[#007AFF]"><?= htmlspecialchars($username ?? 'Pengguna') ?>'s task list</span>
            </nav>
            
            <section class="upcoming-reminders mb-8">
                <h2 class="text-xl font-semibold text-white mb-4">Upcoming Events</h2>
                <div class="bg-[#2c2e31] p-4 rounded-lg shadow-md">
                    <?php if (!empty($upcomingReminders)): ?>
                        <ul class="space-y-3">
                            <?php foreach ($upcomingReminders as $reminder): ?>
                                <li class="flex items-center justify-between p-2 rounded-md transition-colors hover:bg-[#3a3a3a]">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 rounded-full mr-3 bg-yellow-400"></span>
                                        <span class="font-medium text-white"><?= htmlspecialchars($reminder['title']) ?></span>
                                    </div>
                                    <span class="text-sm text-gray-400"><?= date('D, M j g:i A', strtotime($reminder['event_start_time'])) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-gray-400">You have no upcoming reminders.</p>
                    <?php endif; ?>
                </div>
            </section>
            <section class="page-header mb-6">
                <div class="flex items-center justify-between">
                    <div class="page-title text-[#e0e0e0] flex items-center">
                        <span class="mr-2 text-3xl">📋</span>
                        <h1 class="text-2xl font-semibold">Today's Tasks</h1>
                    </div>
                </div>
            </section>

            <section class="actual-task-list-data">
                <?php
                // Logika untuk menyertakan konten tugas/index.php
                // Variabel $tasks, $error, $success harus disiapkan oleh controller
                // yang memanggil dashboard/index.php ini.

                // Contoh cara mendefinisikan variabel dummy untuk pengujian:
                // $tasks = [
                //     ['id' => 1, 'title' => 'Belajar React', 'status' => 'Sedang Dikerjakan', 'created_at' => '2025-05-27'],
                //     ['id' => 2, 'title' => 'Selesaikan Laporan', 'status' => 'Pending', 'created_at' => '2025-05-26'],
                //     ['id' => 3, 'title' => 'Review Code', 'status' => 'Selesai', 'created_at' => '2025-05-25'],
                // ];
                // $error = null;
                // $success = "Daftar tugas berhasil dimuat!";

                // Sertakan file tampilan daftar tugas
                // Menggunakan __DIR__ untuk path absolut yang lebih kuat dan andal.
                // __DIR__ adalah direktori dari file saat ini (dashboard/index.php).
                // Path ini akan naik satu level (dari 'dashboard' ke 'views'),
                // lalu masuk ke folder 'tugas' untuk menemukan 'index.php'.
                include __DIR__ . '/../tugas/index.php';
                ?>
            </section>
        </main>
    </div>
</body>
</html>
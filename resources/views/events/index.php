<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            transition: background-color 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        /* Tambahkan atau pastikan style ini ada untuk kontrol visibilitas */
        .hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <div class="flex flex-col lg:flex-row min-h-screen">
        <aside class="w-full lg:w-1/4 bg-[#2c2e31] p-4 shadow-lg lg:h-screen">
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
                <a href="?c=dashboard&m=index" class="hover:text-white">Home</a> <span class="mx-2">/</span> <span class="text-[#007AFF]"><?= htmlspecialchars($username ?? 'Pengguna') ?>'s Event List</span>
            </nav>

            <section class="page-header mb-6">
                <div class="flex flex-col sm:flex-row items-center justify-between">
                    <div class="page-title text-[#e0e0e0] flex items-center mb-4 sm:mb-0">
                        <span class="mr-2 text-3xl">🗓️</span>
                        <h1 class="text-2xl font-semibold text-white text-center w-full sm:w-auto">
                            Daftar Event
                        </h1>
                    </div>
                    <div class="page-actions">
                        <a href="?c=event&m=create" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-full transition text-center w-full sm:w-auto">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Event
                        </a>
                    </div>
                </div>
            </section>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div id="session-error-message" class="mb-4 p-3 bg-red-600 text-white rounded font-medium">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div id="session-success-message" class="mb-4 p-3 bg-green-600 text-white rounded font-medium">
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <div id="ajax-message-container" class="mb-4">
                </div>

            <section class="event-list-data">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-[#303030] rounded-lg overflow-hidden" id="eventTable">
                        <thead>
                            <tr class="bg-[#4a4a4a] text-left text-gray-300 uppercase text-sm leading-normal">
                                <th class="py-3 px-6">Nama Event</th>
                                <th class="py-3 px-6">Deskripsi</th>
                                <th class="py-3 px-6">Dimulai</th>
                                <th class="py-3 px-6">Berakhir</th>
                                <th class="py-3 px-6">Lokasi</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-200 text-sm font-light">
                            <?php foreach ($events as $event): ?>
                                <tr id="event-row-<?= htmlspecialchars($event['id']) ?>" class="border-b border-gray-600 hover:bg-[#3a3a3a]">
                                    <td class="py-3 px-6 whitespace-nowrap"><?= htmlspecialchars($event['event_name']) ?></td>
                                    <td class="py-3 px-6">
                                        <?= htmlspecialchars(substr($event['description'] ?? '', 0, 50)) ?>
                                        <?= strlen($event['description'] ?? '') > 50 ? '...' : '' ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <?= date('d/m/Y H:i', strtotime($event['start_time'])) ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <?= $event['end_time'] ? date('d/m/Y H:i', strtotime($event['end_time'])) : '-' ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <?= htmlspecialchars($event['location'] ?? '-') ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap text-center">
                                        <a href="?c=event&m=edit&id=<?= htmlspecialchars($event['id']) ?>"
                                           class="text-blue-500 hover:text-blue-700 font-medium mr-3">Edit</a>
                                        <button type="button" data-id="<?= htmlspecialchars($event['id']) ?>"
                                           class="delete-event-btn text-red-500 hover:text-red-700 font-medium bg-transparent border-none cursor-pointer">Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div id="no-event-message" class="text-center py-12 <?= empty($events) ? '' : 'hidden' ?>">
                        <i class="fas fa-calendar-alt text-gray-600 text-5xl mb-4"></i>
                        <p class="text-gray-400 text-lg">Belum ada event yang dijadwalkan.</p>
                        <a href="?c=event&m=create" class="mt-4 inline-block text-[#2684FF] hover:text-[#006bb3]">
                            Buat Event Baru
                        </a>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="/js/event-list.js"></script>
</body>
</html>
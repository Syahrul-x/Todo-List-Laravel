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
                    <li><a href="#" class="flex items-center p-2 rounded-md text-[#e0e0e0] hover:bg-[#3a3a3a] hover:text-[#2684FF] transition-colors"><span class="mr-2">📄</span> All content</a></li>
                    <li><a href="?c=dashboard&m=index" class="flex items-center p-2 rounded-md text-[#e0e0e0] hover:bg-[#3a3a3a] hover:text-[#2684FF] transition-colors"><span class="mr-2">✅</span> <?= htmlspecialchars($username ?? 'Pengguna') ?>'s task list</a></li>
                    <li><a href="?c=notes&m=index" class="flex items-center p-2 rounded-md text-[#e0e0e0] hover:bg-[#3a3a3a] hover:text-[#2684FF] transition-colors"><span class="mr-2">📝</span> Meeting notes</a></li>
                    <li><a href="?c=event&m=index" class="flex items-center p-2 rounded-md text-[#e0e0e0] hover:bg-[#3a3a3a] hover:text-[#2684FF] transition-colors"><span class="mr-2">🗓️</span> Event List</a></li>
                </ul>
            </nav>
        </aside>

    <main class="flex-1 bg-[#1e1e1e] p-6">
        <nav class="breadcrumb text-[#a9b7c6]">
            <a href="#">Home</a> / <span class="text-[#007AFF]"><?= $username ?>'s Meeting Notes</span>
        </nav>

        <section class="page-header mb-6">
            <div class="flex items-center justify-between">
                <div class="page-title text-[#e0e0e0] flex items-center">
                    <span class="mr-2">📝</span>
                    <h1 class="text-2xl font-semibold">Meeting Notes</h1>
                </div>
            <div class="page-actions">
                <!-- Tombol untuk desktop -->
                <a href="?c=notes&m=create#" class="hidden md:inline-block bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white">
                    + tambah catatan
                </a>

                <!-- Tombol ikon untuk mobile -->
                <a href="?c=notes&m=create#" class="inline-block md:hidden bg-green-600 hover:bg-green-700 p-2 rounded text-white">
                    +
                </a>
            </div>
        </section>

         <div class="overflow-x-auto overflow-y-auto">
                <table class="min-w-full bg-[#303030] rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-[#4a4a4a] text-left text-gray-300 uppercase text-sm leading-normal">
                            <th class="py-3 px-6">Rapat</th>
                            <th class="py-3 px-6">Isi</th>
                            <th class="py-3 px-6">Tanggal Dibuat</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-200 text-sm font-light">
                        <?php if (!empty($notes)): ?>
                            <?php foreach ($notes as $note): ?>
                                <tr class="border-b border-gray-600 hover:bg-[#3a3a3a]">
                                    <td class="py-3 px-6 whitespace-nowrap"><?= htmlspecialchars($note['title']) ?></td>
                                    <td class="py-3 px-6">
                                        <?= htmlspecialchars(substr($note['description'] ?? '', 0, 50)) ?>
                                        <?= strlen($note['description'] ?? '') > 50 ? '...' : '' ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap"><?= htmlspecialchars($note['created_at']) ?></td>
                                    <td class="py-3 px-6 whitespace-nowrap text-center">
                                        <a href="?c=notes&m=edit&id=<?= htmlspecialchars($note['id']) ?>"
                                            class="text-[#2684FF] hover:text-[#006bb3] font-medium mr-3">Edit</a>
                                        <a href="?c=notes&m=destroy&id=<?= htmlspecialchars($note['id']) ?>"
                                            onclick="return confirm('Yakin ingin menghapus tugas ini?')"
                                            class="text-red-500 hover:text-red-700 font-medium">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="py-6 px-6 text-center text-gray-400">Belum ada catatan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
    </main>
</div>

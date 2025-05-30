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
                    <a href="?c=notes&m=create#" class="bg-green-600 px-4 py-2 rounded">+ Tambah Catatan</a>
                </div>
            </div>
        </section>

        <section class="Meeting Notes Container">
            <div class="overflow-x-auto bg-[#1e1e1e] p-4 rounded-lg shadow-lg">
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr>
                            <th class="text-[#a9b7c6] py-2 px-4">Rapat</th>
                            <th class="text-[#a9b7c6] py-2 px-4">Isi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <ul class="mt-4">
                      <?php foreach ($notes as $note): ?>
                        <tr>
                          <td class="py-2 px-4"><?= htmlspecialchars($note['title']) ?></td>
                          <td class="py-2 px-4"><?= htmlspecialchars($note['description']) ?></td>
                          <td>
                            <a href="?c=notes&m=edit&id=<?= $note['id'] ?>" class="text-yellow-400">Edit</a> |
                            <a href="?c=notes&m=destroy&id=<?= $note['id'] ?>" class="text-red-500" onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </ul>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
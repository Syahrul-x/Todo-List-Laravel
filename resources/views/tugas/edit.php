<div class="max-w-2xl w-full mx-auto bg-[#1e1e1e] p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-6 text-white text-center">
        Edit Tugas
    </h2>

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

    <form action="?c=tugas&m=saveUpdate" method="post" class="space-y-6">
        <input type="hidden" name="id" value="<?= htmlspecialchars($task['id'] ?? '') ?>">

        <div>
            <label for="title" class="block mb-2 text-gray-300 font-medium">Judul Tugas:</label>
            <input type="text" id="title" name="title" required
                value="<?= htmlspecialchars($task['title'] ?? '') ?>"
                class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
            <label for="description" class="block mb-2 text-gray-300 font-medium">Deskripsi:</label>
            <textarea id="description" name="description" rows="4"
                class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($task['description'] ?? '') ?></textarea>
        </div>

        <div>
            <label for="status" class="block mb-2 text-gray-300 font-medium">Status:</label>
            <select id="status" name="status"
                class="w-full p-3 rounded bg-[#303030] text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Pending" <?= (isset($task['status']) && $task['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                <option value="Sedang Dikerjakan" <?= (isset($task['status']) && $task['status'] == 'Sedang Dikerjakan') ? 'selected' : '' ?>>Sedang Dikerjakan</option>
                <option value="Selesai" <?= (isset($task['status']) && $task['status'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
            </select>
        </div>

        <div>
            <label for="category_id" class="block mb-2 text-gray-300 font-medium">Kategori ID:</label>
            <input type="text" id="category_id" name="category_id"
                value="<?= htmlspecialchars($task['category_id'] ?? '') ?>"
                class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <p class="mt-1 text-sm text-gray-400">Masukkan ID kategori yang relevan.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4">
            <button type="submit" class="flex-1 py-3 bg-[#2684FF] hover:bg-[#006bb3] text-white font-semibold rounded-full transition">
                Simpan Perubahan
            </button>
            <a href="?c=dashboard&m=index" class="flex-1 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-full transition text-center">
                Batal
            </a>
        </div>
    </form>
</div>

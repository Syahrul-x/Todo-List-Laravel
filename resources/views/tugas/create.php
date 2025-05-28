<div class="w-full sm:max-w-2xl mx-auto bg-[#1e1e1e] p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-6 text-white text-center">
        <?= htmlspecialchars($title ?? 'Buat Tugas Baru') ?>
    </h2>

    <?php if (!empty($error)): ?>
        <div class="mb-4 p-3 bg-red-600 text-white rounded font-medium">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="mb-4 p-3 bg-green-600 text-white rounded font-medium">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form action="?c=tugas&m=store" method="post" class="space-y-6">
        <div>
            <label for="title" class="block mb-2 text-gray-300 font-medium">Judul Tugas:</label>
            <input type="text" id="title" name="title" required
                class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
            <label for="description" class="block mb-2 text-gray-300 font-medium">Deskripsi:</label>
            <textarea id="description" name="description" rows="4"
                class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <div>
            <label for="status" class="block mb-2 text-gray-300 font-medium">Status:</label>
            <select id="status" name="status"
                class="w-full p-3 rounded bg-[#303030] text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Pending">Pending</option>
                <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                <option value="Selesai">Selesai</option>
            </select>
        </div>

        <div>
            <label for="category_id" class="block mb-2 text-gray-300 font-medium">Kategori ID:</label>
            <input type="text" id="category_id" name="category_id"
                class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <p class="mt-1 text-sm text-gray-400">Masukkan ID kategori yang relevan.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 mt-6">
            <button type="submit" class="flex-1 py-3 bg-[#2684FF] hover:bg-[#006bb3] text-white font-semibold rounded-full transition">
                Buat Tugas
            </button>
            <a href="?c=dashboard&m=index" class="flex-1 text-center py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-full transition">
                Batal
            </a>
        </div>
    </form>
</div>

<?php
// File: resources/views/tugas/edit.php
// Pastikan variabel $task (array atau objek) dan $categories (array objek) dikirim dari controller
// Variabel $title dan $username juga diasumsikan ada dari controller
?>
<div class="w-full sm:max-w-2xl mx-auto bg-[#1e1e1e] p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-6 text-white text-center">
        <?= htmlspecialchars($title ?? 'Edit Tugas') ?>
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
        <?php // Pastikan $task adalah array dan memiliki kunci 'id' ?>
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
            <label for="category_id" class="block mb-2 text-gray-300 font-medium">Kategori:</label>
            <select id="category_id" name="category_id"
                class="w-full p-3 rounded bg-[#303030] text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Kategori --</option>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <?php // Pastikan $cat adalah objek dan memiliki properti id dan name ?>
                        <?php // Pastikan $task adalah array dan memiliki kunci category_id ?>
                        <option value="<?= htmlspecialchars($cat->id) ?>" 
                                <?= (isset($task['category_id']) && $task['category_id'] == $cat->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat->name) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <p class="mt-1 text-sm text-gray-400">Pilih kategori yang relevan untuk tugas ini.</p>
        </div>


        <div class="flex flex-col sm:flex-row gap-4 mt-6">
            <button type="submit" class="flex-1 py-3 bg-[#2684FF] hover:bg-[#006bb3] text-white font-semibold rounded-full transition">
                Simpan Perubahan
            </button>
            <a href="?c=dashboard&m=index" class="flex-1 text-center py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-full transition">
                Batal
            </a>
        </div>
    </form>
</div>

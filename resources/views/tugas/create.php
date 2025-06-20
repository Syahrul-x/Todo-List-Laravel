<div class="w-full sm:max-w-2xl mx-auto bg-[#1e1e1e] p-4 sm:p-6 rounded-lg shadow-lg"> <h2 class="text-xl sm:text-2xl font-semibold mb-6 text-white text-center"> <?= htmlspecialchars($title ?? 'Buat Tugas Baru') ?>
    </h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="mb-4 p-3 bg-red-600 text-white rounded font-medium text-sm">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="mb-4 p-3 bg-green-600 text-white rounded font-medium text-sm">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <form action="?c=tugas&m=store" method="post" class="space-y-4 sm:space-y-6"> <div>
            <label for="title" class="block mb-2 text-gray-300 font-medium text-sm sm:text-base">Judul Tugas:</label>
            <input type="text" id="title" name="title" required
                value="<?= htmlspecialchars($_SESSION['old_input']['title'] ?? '') ?>"
                class="w-full p-2 sm:p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base" />
        </div>

        <div>
            <label for="description" class="block mb-2 text-gray-300 font-medium text-sm sm:text-base">Deskripsi:</label>
            <textarea id="description" name="description" rows="4"
                class="w-full p-2 sm:p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"><?= htmlspecialchars($_SESSION['old_input']['description'] ?? '') ?></textarea>
        </div>

        <div>
            <label for="status" class="block mb-2 text-gray-300 font-medium text-sm sm:text-base">Status:</label>
            <select id="status" name="status"
                class="w-full p-2 sm:p-3 rounded bg-[#303030] text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base">
                <option value="Pending" <?= (isset($_SESSION['old_input']['status']) && $_SESSION['old_input']['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                <option value="Sedang Dikerjakan" <?= (isset($_SESSION['old_input']['status']) && $_SESSION['old_input']['status'] == 'Sedang Dikerjakan') ? 'selected' : '' ?>>Sedang Dikerjakan</option>
                <option value="Selesai" <?= (isset($_SESSION['old_input']['status']) && $_SESSION['old_input']['status'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
            </select>
        </div>

        <div>
            <label for="category_id" class="block mb-2 text-gray-300 font-medium text-sm sm:text-base">Kategori:</label>
            <select id="category_id" name="category_id"
                class="w-full p-2 sm:p-3 rounded bg-[#303030] text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base">
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat->id) ?>" <?= (isset($_SESSION['old_input']['category_id']) && $_SESSION['old_input']['category_id'] == $cat->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="mt-1 text-xs text-gray-400">Pilih kategori yang relevan untuk tugas ini.</p>
        </div>

        <div>
            <label for="priority_id" class="block mb-2 text-gray-300 font-medium text-sm sm:text-base">Prioritas:</label>
            <select id="priority_id" name="priority_id"
                class="w-full p-2 sm:p-3 rounded bg-[#303030] text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base">
                <option value="">-- Pilih Prioritas --</option>
                <?php foreach ($priorities as $prio): ?>
                    <option value="<?= htmlspecialchars($prio->id) ?>" <?= (isset($_SESSION['old_input']['priority_id']) && $_SESSION['old_input']['priority_id'] == $prio->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($prio->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="mt-1 text-xs text-gray-400">Pilih tingkat prioritas untuk tugas ini.</p>
        </div>


        <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-6"> <button type="submit" class="flex-1 py-2 sm:py-3 bg-[#2684FF] hover:bg-[#006bb3] text-white font-semibold rounded-full transition text-sm sm:text-base">
                Buat Tugas
            </button>
            <a href="?c=dashboard&m=index" class="flex-1 text-center py-2 sm:py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-full transition text-sm sm:text-base">
                Batal
            </a>
        </div>
    </form>
</div>

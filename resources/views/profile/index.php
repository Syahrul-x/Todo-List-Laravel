<div class="max-w-md mx-auto bg-[#1e1e1e] p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-6 text-white"><?= htmlspecialchars($title) ?></h2>

    <?php if (!empty($error)): ?>
        <div class="mb-4 p-3 bg-red-600 text-white rounded font-medium"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="mb-4 p-3 bg-green-600 text-white rounded font-medium"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form action="?c=profile&m=update" method="post" class="space-y-6">
        <div>
            <label for="name" class="block mb-2 text-gray-300 font-medium">Nama</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user->name) ?>" required
                class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
            <label for="email" class="block mb-2 text-gray-300 font-medium">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required
                class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
            <label for="new_password" class="block mb-2 text-gray-300 font-medium">Password Baru (opsional)</label>
            <input type="password" id="new_password" name="new_password" placeholder="Kosongkan jika tidak diubah"
                class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
            <label for="confirm_password" class="block mb-2 text-gray-300 font-medium">Konfirmasi Password Baru</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password baru"
                class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <button type="submit" class="w-full py-3 bg-[#2684FF] hover:bg-[#006bb3] text-white font-semibold rounded-full transition">
            Simpan Perubahan
        </button>
    </form>

    <form action="?c=profile&m=delete" method="post" class="mt-6">
        <button type="submit" 
            onclick="return confirm('Yakin ingin menghapus akun? Data tidak bisa dikembalikan!')" 
            class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-full transition">
            Hapus Akun
        </button>
    </form>
</div>

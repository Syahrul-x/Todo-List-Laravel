<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 min-h-screen p-4">
    <div class="max-w-6xl mx-auto">
        <div class="bg-[#1e1e1e] p-6 rounded-lg shadow-lg text-white">
            <div class="flex flex-wrap justify-between items-center mb-6 gap-3">
                <h2 class="text-2xl font-semibold text-white flex-shrink-0 w-full text-center sm:text-left">
                    Daftar Tugas
                </h2>
                <div class="flex flex-wrap justify-between items-center w-full gap-3">
                    <div class="flex gap-3">
                    <a href="?c=favorite&m=index"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-full transition whitespace-nowrap">
                    <i class="fas fa-star mr-2"></i>Favorit
                    </a>

                    <a href="?c=tugas&m=create"
                    class="bg-[#2684FF] hover:bg-[#006bb3] text-white font-semibold py-2 px-4 rounded-full transition whitespace-nowrap">
                    + Tambah Tugas Baru
                    </a>
                </div>
                    <!-- UNTUK FITUR SERLY FILTER CATEGORY ADA DISINI
                    CATATAN: FOREACH DIGUNAKAN UNTUK MELOOP DATA, CATEGORIES DISINGKAT JADI $CAT, DISETIAP $CAT DIAMBIL KATEGORI NYA APA SAJA 
                    1. DIA AKAN MENCARI CATEGORY ID NYA DULU, LALU DICOCOKAN DENGAN KATEGORI ID YANG ADA DI DATABASE, YANG DITAMILKAN ADALAH NAMANYA 
                    2. KETIKA MEMILIH NAMANYA, DIA AKAN MEMANGGIL DASHBOARDCONTROLLER.PHP FUNCTION INDEX -->
                    <form method="GET" action="" class="inline-block">
                        <!-- action="" berarti form akan submit ke URL halaman saat ini (refresh halaman dengan parameter baru). -->
                        <input type="hidden" name="c" value="dashboard" />
                        <input type="hidden" name="m" value="index" />
                        <!-- c=dashboard → mengindikasikan controller yang akan dipanggil.
                        m=index → mengindikasikan method/action controller yang akan dipanggil. Ini penting supaya saat form submit, parameter ini tetap dikirim agar routing aplikasi tetap pada halaman dashboard/index. -->
                        <select name="category_id" onchange="this.form.submit()" 
                            class="bg-[#2c2e31] border border-gray-700 rounded-full text-gray-100 font-semibold py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <!-- Dropdown/select untuk memilih kategori dengan nama parameter category_id. 
                            Onchange="this.form.submit()" → setiap kali pilihan kategori berubah, form langsung dikirim (auto-submit), tanpa perlu klik tombol submit.
                            class="..." adalah kelas-kelas Tailwind CSS untuk styling dropdown (warna latar, border, rounded corner, teks warna, padding, fokus ring, dll).-->
                            <option value="">Filter by Category</option>
                            <!-- Opsi pertama di dropdown yang kosong nilainya (value="") sebagai pilihan default, artinya tidak melakukan filter (tampilkan semua tugas). -->
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat->id) ?>" <?= ($selectedCategory == $cat->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    <!-- UNTUK FITUR SERLY FILTER CATEGORY ADA DISINI
                    CATATAN: FOREACH DIGUNAKAN UNTUK MELOOP DATA, CATEGORIES DISINGKAT JADI $CAT, DISETIAP $CAT DIAMBIL KATEGORI NYA APA SAJA 
                    1. DIA AKAN MENCARI CATEGORY ID NYA DULU, LALU DICOCOKAN DENGAN KATEGORI ID $selectedCategory DI PASSING DARI DASHBOARDCONTROLLER.PHP YANG ADA DI DATABASE, YANG DITAMILKAN ADALAH NAMANYA 
                    2. KETIKA MEMILIH NAMANYA, DIA AKAN MEMANGGIL DASHBOARDCONTROLLER.PHP FUNCTION INDEX -->
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

            <div class="overflow-x-auto overflow-y-auto">
                <table class="min-w-full bg-[#303030] rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-[#4a4a4a] text-left text-gray-300 uppercase text-sm leading-normal">
                            <th class="py-3 px-6">Judul</th>
                            <th class="py-3 px-6">Deskripsi</th>
                            <th class="py-3 px-6">Status</th>
                            <th class="py-3 px-6">Tanggal Dibuat</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-200 text-sm font-light">
                        <?php if (!empty($tasks)): ?>
                            <?php foreach ($tasks as $task): ?>
                                <tr class="border-b border-gray-600 hover:bg-[#3a3a3a]">
                                    <td class="py-3 px-6 whitespace-nowrap"><?= htmlspecialchars($task['title']) ?></td>
                                    <td class="py-3 px-6">
                                        <?= htmlspecialchars(substr($task['description'] ?? '', 0, 50)) ?>
                                        <?= strlen($task['description'] ?? '') > 50 ? '...' : '' ?>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <span class="py-1 px-3 rounded-full text-xs 
                                            <?php 
                                                if ($task['status'] == 'Selesai') {
                                                    echo 'bg-green-600';
                                                } elseif ($task['status'] == 'Sedang Dikerjakan') {
                                                    echo 'bg-blue-600';
                                                } else {
                                                    echo 'bg-yellow-600';
                                                }
                                            ?>
                                        ">
                                            <?= htmlspecialchars($task['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap"><?= htmlspecialchars($task['created_at']) ?></td>
                                    <td class="py-3 px-6 whitespace-nowrap text-center">
                                        <a href="?c=favorite&m=toggle&task_id=<?= htmlspecialchars($task['id']) ?>"
                                            class="text-yellow-500 hover:text-yellow-600 font-medium mr-3"
                                            title="<?= (isset($task['is_favorited']) && $task['is_favorited']) ? 'Hapus dari favorit' : 'Tambahkan ke favorit' ?>">
                                            <i
                                                class="<?= (isset($task['is_favorited']) && $task['is_favorited']) ? 'fas' : 'far' ?> fa-star"></i>
                                        </a>
                                        <a href="?c=tugas&m=update&id=<?= htmlspecialchars($task['id']) ?>"
                                            class="text-[#2684FF] hover:text-[#006bb3] font-medium mr-3">Edit</a>
                                        <a href="?c=tugas&m=delete&id=<?= htmlspecialchars($task['id']) ?>"
                                            onclick="return confirm('Yakin ingin menghapus tugas ini?')"
                                            class="text-red-500 hover:text-red-700 font-medium">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="py-6 px-6 text-center text-gray-400">Belum ada tugas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

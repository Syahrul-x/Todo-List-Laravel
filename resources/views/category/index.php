<!-- INI HALAMAN UTAMA UNTUK MENAMPILKAN CATEGORY YANG ADA -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Category Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen p-6">

<div class="max-w-4xl mx-auto bg-[#1e1e1e] rounded-lg p-6 shadow-lg">

    <h1 class="text-3xl font-semibold mb-6">Category Management</h1>

    <a href="?c=category&m=create" class="inline-block mb-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded text-white transition">
        + Add New Category
    </a>

    <?php if (!empty($categories)): ?>
        <div class="overflow-x-auto overflow-y-auto">
            <table class="min-w-full bg-[#303030] rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-[#4a4a4a] text-left text-gray-300 uppercase text-sm leading-normal">
                        <th class="py-3 px-6">Name</th>
                        <th class="py-3 px-6">Description</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-200 text-sm font-light">
                    <?php foreach ($categories as $category): ?>
                        <tr class="border-b border-gray-600 hover:bg-[#3a3a3a]">
                            <td class="py-3 px-6 whitespace-nowrap"><?= htmlspecialchars($category->name) ?></td>
                            <td class="py-3 px-6"><?= htmlspecialchars($category->description) ?></td>
                            <td class="py-3 px-6 whitespace-nowrap text-center">
                                <a href="?c=category&m=edit&id=<?= $category->id ?>" class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>
                                <a href="?c=category&m=delete&id=<?= $category->id ?>" onclick="return confirm('Yakin ingin menghapus kategori ini?')" class="text-red-500 hover:text-red-700">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>        
    <?php else: ?>
        <div class="text-gray-400">Tidak ada kategori.</div>
    <?php endif; ?>

</div>

</body>
</html>

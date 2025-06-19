<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Category Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen p-6">

<div class="max-w-4xl mx-auto bg-[#1e1e1e] rounded-lg p-6 shadow-lg">
    <h1 class="text-3xl font-semibold mb-6">Category Management</h1>

    <!-- Input Pencarian -->
    <div class="mb-4">
        <input type="text" id="searchInput" class="w-full p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search Categories by Name...">
    </div>

    <a href="?c=category&m=create" class="inline-block mb-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded text-white transition">
        + Add New Category
    </a>

    <!-- Tabel Kategori -->
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
                <tbody class="text-gray-200 text-sm font-light" id="categoryTableBody">
                    <?php foreach ($categories as $category): ?>
                        <tr id="category-<?= $category->id ?>" class="border-b border-gray-600 hover:bg-[#3a3a3a]">
                            <td class="py-3 px-6 whitespace-nowrap"><?= htmlspecialchars($category->name) ?></td>
                            <td class="py-3 px-6"><?= htmlspecialchars($category->description) ?></td>
                            <td class="py-3 px-6 whitespace-nowrap text-center">
                                <a href="?c=category&m=edit&id=<?= $category->id ?>" class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>
                                <a href="?c=category&m=delete&id=<?= $category->id ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>        
    <?php else: ?>
        <div class="text-gray-400">Tidak ada kategori.</div>
    <?php endif; ?>

    <br>

    <?php if (!empty($error)): ?>
        <div id="error-message" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white transition mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

</div>

</body>
</html>

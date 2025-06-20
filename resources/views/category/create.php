<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add New Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen p-6">

<div class="max-w-xl mx-auto bg-[#1e1e1e] rounded-lg p-6 shadow-lg">

    <h1 class="text-3xl font-semibold mb-6">Add New Category</h1>

    <?php if (!empty($error)): ?>
        <div id="error-message" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white transition mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="?c=category&m=create" class="space-y-4">
        <div>
            <label for="name" class="block mb-1 font-medium">Name</label>
            <input id="name" name="name" type="text" value="<?= htmlspecialchars($name ?? '') ?>" required
                class="w-full p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <!-- REQUIRED DIGUNAKAN UNTUK MENAMPILKAN PESAN WAJIB DIISI -->

        <div>
            <label for="description" class="block mb-1 font-medium">Description</label>
            <textarea id="description" name="description" rows="4"
                class="w-full p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($description ?? '') ?></textarea>
        </div>

        <div class="flex justify-between items-center">
            <a href="?c=category&m=index" class="text-gray-400 hover:text-gray-200">Back to categories list</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white transition">Update</button>
        </div>
    </form>

</div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Priority</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            transition: background-color 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen p-6">

<div class="max-w-xl mx-auto bg-[#1e1e1e] rounded-lg p-6 shadow-lg">

    <h1 class="text-3xl font-semibold mb-6 text-center">Edit Priority</h1>

    <?php if (!empty($error)): ?>
        <div class="mb-4 p-3 bg-red-600 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="?c=priority&m=edit&id=<?= htmlspecialchars($priority->id) ?>" class="space-y-4">
        <div>
            <label for="name" class="block mb-1 font-medium text-gray-300">Name:</label>
            <input id="name" name="name" type="text" value="<?= htmlspecialchars($priority->name) ?>" required
                class="w-full p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
            <label for="description" class="block mb-1 font-medium text-gray-300">Description (Optional):</label>
            <textarea id="description" name="description" rows="4"
                class="w-full p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($priority->description) ?></textarea>
        </div>

        <div class="flex justify-between items-center mt-6">
            <a href="?c=priority&m=index" class="text-gray-400 hover:text-gray-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Back to Priorities List
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white transition flex items-center">
                <i class="fas fa-save mr-2"></i>Update Priority
            </button>
        </div>
    </form>

</div>

</body>
</html>
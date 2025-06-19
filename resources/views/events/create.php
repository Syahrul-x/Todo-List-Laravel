<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Event Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen p-4">
    <div class="max-w-2xl mx-auto bg-[#1e1e1e] p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-6 text-white text-center">
            Tambah Event Baru
        </h2>

        <?php // Tampilkan pesan error dari sesi atau dari variabel yang dilewatkan (untuk inisialisasi) ?>
        <?php if (isset($error_message) && !empty($error_message)): ?>
            <div id="initial-alert-message" class="mb-4 p-3 bg-red-600 text-white rounded font-medium">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <div id="ajax-alert-message-container">
            </div>

        <form action="?c=event&m=store" method="post" class="space-y-6" id="createEventForm">
            <div>
                <label for="event_name" class="block mb-2 text-gray-300 font-medium">Nama Event:</label>
                <input type="text" id="event_name" name="event_name" required
                    value="<?= htmlspecialchars($old_input['event_name'] ?? '') ?>"
                    class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="description" class="block mb-2 text-gray-300 font-medium">Deskripsi (Opsional):</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($old_input['description'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="start_time" class="block mb-2 text-gray-300 font-medium">Dimulai (Tanggal dan Jam):</label>
                <input type="datetime-local" id="start_time" name="start_time" required
                    value="<?= htmlspecialchars($old_input['start_time'] ?? '') ?>"
                    class="w-full p-3 rounded bg-[#303030] text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" id="include_end_time" name="include_end_time" class="form-checkbox h-5 w-5 text-blue-600"
                       <?= isset($old_input['include_end_time']) && $old_input['include_end_time'] == 'on' ? 'checked' : '' ?>>
                <label for="include_end_time" class="text-gray-300 font-medium">Sertakan Waktu Berakhir</label>
            </div>

            <div id="end_time_container" class="
                <?= isset($old_input['include_end_time']) && $old_input['include_end_time'] == 'on' ? '' : 'hidden' ?> transition-all duration-300 ease-in-out">
                <label for="end_time" class="block mb-2 text-gray-300 font-medium">Berakhir (Tanggal dan Jam):</label>
                <input type="datetime-local" id="end_time" name="end_time"
                    value="<?= htmlspecialchars($old_input['end_time'] ?? '') ?>"
                    class="w-full p-3 rounded bg-[#303030] text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="location" class="block mb-2 text-gray-300 font-medium">Lokasi (Opsional):</label>
                <input type="text" id="location" name="location"
                    value="<?= htmlspecialchars($old_input['location'] ?? '') ?>"
                    class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="flex flex-col sm:flex-row gap-4 mt-6">
                <button type="submit" class="flex-1 py-3 bg-[#2684FF] hover:bg-[#006bb3] text-white font-semibold rounded-full transition">
                    Buat Event
                </button>
                <a href="?c=event&m=index" class="flex-1 text-center py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-full transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script src="/js/event-create.js"></script>
</body>
</html>
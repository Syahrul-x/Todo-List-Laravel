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

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="mb-4 p-3 bg-red-600 text-white rounded font-medium">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="?c=event&m=store" method="post" class="space-y-6">
            <div>
                <label for="event_name" class="block mb-2 text-gray-300 font-medium">Nama Event:</label>
                <input type="text" id="event_name" name="event_name" required
                    value="<?= htmlspecialchars($_SESSION['old_input']['event_name'] ?? '') ?>"
                    class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="description" class="block mb-2 text-gray-300 font-medium">Deskripsi (Opsional):</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full p-3 rounded bg-[#303030] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($_SESSION['old_input']['description'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="start_time" class="block mb-2 text-gray-300 font-medium">Dimulai (Tanggal dan Jam):</label>
                <input type="datetime-local" id="start_time" name="start_time" required
                    value="<?= htmlspecialchars($_SESSION['old_input']['start_time'] ?? '') ?>"
                    class="w-full p-3 rounded bg-[#303030] text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" id="include_end_time" name="include_end_time" class="form-checkbox h-5 w-5 text-blue-600"
                       <?= isset($_SESSION['old_input']['include_end_time']) && $_SESSION['old_input']['include_end_time'] == 'on' ? 'checked' : '' ?>>
                <label for="include_end_time" class="text-gray-300 font-medium">Sertakan Waktu Berakhir</label>
            </div>

            <div id="end_time_container" class="<?= isset($_SESSION['old_input']['include_end_time']) && $_SESSION['old_input']['include_end_time'] == 'on' ? '' : 'hidden' ?> transition-all duration-300 ease-in-out">
                <label for="end_time" class="block mb-2 text-gray-300 font-medium">Berakhir (Tanggal dan Jam):</label>
                <input type="datetime-local" id="end_time" name="end_time"
                    value="<?= htmlspecialchars($_SESSION['old_input']['end_time'] ?? '') ?>"
                    class="w-full p-3 rounded bg-[#303030] text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="location" class="block mb-2 text-gray-300 font-medium">Lokasi (Opsional):</label>
                <input type="text" id="location" name="location"
                    value="<?= htmlspecialchars($_SESSION['old_input']['location'] ?? '') ?>"
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

    <script>
        // Hapus old_input dari session setelah digunakan untuk menghindari pengisian ulang yang tidak diinginkan
        <?php unset($_SESSION['old_input']); ?>

        const includeEndTimeCheckbox = document.getElementById('include_end_time');
        const endTimeContainer = document.getElementById('end_time_container');
        const endTimeInput = document.getElementById('end_time');
        const startTimeInput = document.getElementById('start_time');

        // Fungsi untuk mendapatkan tanggal dan waktu sekarang dalam format YYYY-MM-DDTHH:MM
        function getNowDateTimeLocal() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset()); // Adjust for timezone
            return now.toISOString().slice(0, 16);
        }

        // Fungsi untuk mengatur atribut min pada input datetime-local
        function setMinDateTimeLocal(inputElement, minDateTime) {
            if (inputElement) {
                inputElement.setAttribute('min', minDateTime);
            }
        }

        // Fungsi untuk mengontrol visibilitas dan atribut 'required' input end_time
        function toggleEndTimeVisibility() {
            if (includeEndTimeCheckbox.checked) {
                endTimeContainer.classList.remove('hidden');
                endTimeInput.setAttribute('required', 'required');
                // Set min for end_time based on start_time value, or current time if start_time is empty
                setMinDateTimeLocal(endTimeInput, startTimeInput.value || getNowDateTimeLocal());
            } else {
                endTimeContainer.classList.add('hidden');
                endTimeInput.removeAttribute('required');
                endTimeInput.value = ''; // Kosongkan nilai jika tidak digunakan
                endTimeInput.removeAttribute('min'); // Hapus atribut min juga
            }
        }

        // Inisialisasi: Atur min untuk start_time saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            setMinDateTimeLocal(startTimeInput, getNowDateTimeLocal());
            // Jika ada nilai lama di start_time dan itu di masa lalu, kosongkan
            if (startTimeInput.value && startTimeInput.value < getNowDateTimeLocal()) {
                startTimeInput.value = '';
            }
            toggleEndTimeVisibility(); // Panggil untuk set kondisi awal end_time
        });


        // Tambahkan event listener untuk perubahan pada checkbox
        includeEndTimeCheckbox.addEventListener('change', toggleEndTimeVisibility);

        // Tambahkan event listener untuk perubahan pada start_time
        // Ini akan memastikan end_time tidak bisa lebih awal dari start_time yang dipilih
        startTimeInput.addEventListener('change', () => {
            if (includeEndTimeCheckbox.checked) {
                if (startTimeInput.value) {
                    setMinDateTimeLocal(endTimeInput, startTimeInput.value);
                    // Jika end_time saat ini lebih awal dari start_time yang baru, reset end_time
                    if (endTimeInput.value && endTimeInput.value < startTimeInput.value) {
                        endTimeInput.value = startTimeInput.value;
                    }
                } else {
                    // Jika start_time dikosongkan, end_time tidak boleh di masa lalu
                    setMinDateTimeLocal(endTimeInput, getNowDateTimeLocal());
                }
            }
        });
    </script>
</body>
</html>
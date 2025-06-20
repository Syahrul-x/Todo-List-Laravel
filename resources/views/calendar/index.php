<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Prepare data for FullCalendar
$events_for_calendar = [];
foreach ($calendarEntries as $entry) {
    $events_for_calendar[] = [
        'id'          => $entry['id'],
        'entry_id'    => $entry['entry_id'],
        'entry_type'  => $entry['entry_type'],
        'title'       => $entry['title'],
        'start'       => $entry['start'], // Pastikan menggunakan 'start'
        'end'         => $entry['end'] ?? null, // Gunakan 'end' jika ada
        'description' => $entry['description'],
        'color'       => $entry['color'],
        'textColor'   => $entry['textColor'],
    ];
}
$calendar_json = json_encode($events_for_calendar);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'My Calendar') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Custom styles for FullCalendar to match the dark theme */
        :root {
            --fc-border-color: #444;
            --fc-daygrid-event-dot-width: 8px;
            --fc-list-event-dot-width: 10px;
            --fc-list-event-hover-bg-color: #2c2e31;
        }
        .fc {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }
        .fc .fc-toolbar-title { color: #fff; }
        .fc .fc-button { background-color: #3a3a3a; border: none; color: #fff; }
        .fc .fc-button:hover { background-color: #4a4a4a; }
        .fc .fc-daygrid-day.fc-day-today { background-color: rgba(255, 255, 255, 0.1); }
        .fc-event-main { cursor: pointer; }
    </style>
</head>
<body class="bg-[#121212] text-[#e0e0e0] pt-20">

    <main class="w-full max-w-7xl mx-auto p-4 sm:p-6 md:p-8">
        <div class="bg-[#1e1e1e] p-6 rounded-lg shadow-lg">
            
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-white">My Calendar</h1>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center"><span class="w-4 h-4 rounded-full bg-[#28a745] mr-2"></span> Task</div>
                    <div class="flex items-center"><span class="w-4 h-4 rounded-full bg-[#ffc107] mr-2"></span> Event</div>
                </div>
            </div>

            <div id='calendar' class="w-full"></div>

            <div id="event-modal" class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center hidden z-50">
                <div class="bg-[#2c2e31] p-6 rounded-lg shadow-lg max-w-sm w-full">
                    <h3 id="modal-title" class="text-xl font-bold mb-2 text-white"></h3>
                    <p id="modal-time" class="text-sm text-gray-400 mb-4"></p>
                    <p id="modal-desc" class="text-gray-200 mb-6"></p>
        
                    <div class="flex flex-col space-y-3">
                        <a id="modal-reminder-btn" href="?c=calendar&m=create" class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Set Reminder
                        </a>
                        <button onclick="closeModal()" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Close
                        </button>
                    </div>
                </div>
            </div>
    </main>
    
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                // ... (initialView, headerToolbar, events tetap sama) ...
                events: <?php echo json_encode($calendarEntries); ?>,
                eventClick: function(info) {
                    info.jsEvent.preventDefault();

                    const props = info.event.extendedProps;
                    const reminderId = props.reminder_id; // Ambil ID reminder

                    const reminderBtn = document.getElementById('modal-reminder-btn');

                    // LOGIKA BARU: Cek apakah reminder ada atau tidak
                    if (reminderId) {
                        // Jika ADA, ubah tombol menjadi "Edit Reminder"
                        reminderBtn.innerText = "Edit Reminder";
                        reminderBtn.href = `?c=calendar&m=edit&reminder_id=${reminderId}`;
                        reminderBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                        reminderBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                    } else {
                        // Jika TIDAK ADA, tombol menjadi "Set Reminder"
                        reminderBtn.innerText = "Set Reminder";
                        reminderBtn.href = `?c=calendar&m=create&${props.entry_type}_id=${props.entry_id}`;
                        reminderBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                        reminderBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    }

                    // Isi konten modal seperti biasa
                    document.getElementById('modal-title').innerText = info.event.title;
                    document.getElementById('modal-desc').innerText = props.description;
                    document.getElementById('modal-time').innerText = info.event.start.toLocaleString();
                    
                    // Tampilkan modal
                    document.getElementById('event-modal').classList.remove('hidden');
                }
            });
            calendar.render();
        });

        function closeModal() {
            document.getElementById('event-modal').classList.add('hidden');
        }
    </script>
</body>
</html>
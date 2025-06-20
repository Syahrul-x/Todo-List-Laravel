document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-event-btn');
    const ajaxMessageContainer = document.getElementById('ajax-message-container');
    const eventTable = document.getElementById('eventTable');
    const noEventMessage = document.getElementById('no-event-message');

    function showAjaxAlert(message, type = 'success') {
        if (ajaxMessageContainer) {
            ajaxMessageContainer.innerHTML = ''; // Clear previous messages
            const newAlert = document.createElement('div');
            newAlert.className = `p-3 rounded font-medium ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} text-white`;
            newAlert.textContent = message;
            ajaxMessageContainer.appendChild(newAlert);
            setTimeout(() => {
                newAlert.remove(); // Hapus pesan setelah beberapa detik
            }, 3000);
        }
    }

    // Fungsi untuk mengelola tampilan tabel dan pesan kosong
    function updateDisplayBasedOnEvents() {
        // Ambil semua baris event (yang memiliki ID 'event-row-')
        const eventRows = document.querySelectorAll('tr[id^="event-row-"]');

        if (eventRows.length === 0) {
            // Jika tidak ada baris event, sembunyikan tabel dan tampilkan pesan kosong
            if (eventTable) {
                eventTable.classList.add('hidden'); // Sembunyikan seluruh tabel
            }
            if (noEventMessage) {
                noEventMessage.classList.remove('hidden'); // Tampilkan pesan "Belum ada event"
            }
        } else {
            // Jika ada baris event, tampilkan tabel dan sembunyikan pesan kosong
            if (eventTable) {
                eventTable.classList.remove('hidden'); // Tampilkan tabel
            }
            if (noEventMessage) {
                noEventMessage.classList.add('hidden'); // Sembunyikan pesan "Belum ada event"
            }
        }
    }

    // Panggil fungsi saat DOMContentLoaded untuk inisialisasi awal
    updateDisplayBasedOnEvents();

    deleteButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const eventId = this.dataset.id;
            const eventRow = document.getElementById(`event-row-${eventId}`);

            if (!confirm('Yakin ingin menghapus event ini?')) {
                return;
            }

            try {
                const response = await fetch(`?c=event&m=delete&id=${eventId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                });

                const result = await response.json();

                if (result.success) {
                    showAjaxAlert(result.message, 'success');
                    if (eventRow) {
                        eventRow.remove(); // Hapus baris dari DOM
                        // Panggil lagi fungsi untuk memperbarui tampilan setelah penghapusan
                        updateDisplayBasedOnEvents();
                    }
                } else {
                    showAjaxAlert(result.message, 'error');
                }
            } catch (error) {
                    console.error('Error:', error);
                showAjaxAlert('Terjadi kesalahan saat menghapus event.', 'error');
            }
        });
    });

    // Sembunyikan pesan sukses/error dari sesi setelah beberapa waktu
    const sessionErrorMessage = document.getElementById('session-error-message');
    const sessionSuccessMessage = document.getElementById('session-success-message');
    if (sessionErrorMessage) {
        setTimeout(() => { sessionErrorMessage.remove(); }, 3000);
    }
    if (sessionSuccessMessage) {
        setTimeout(() => { sessionSuccessMessage.remove(); }, 3000);
    }
});
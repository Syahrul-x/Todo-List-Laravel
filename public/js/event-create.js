const includeEndTimeCheckbox = document.getElementById('include_end_time');
const endTimeContainer = document.getElementById('end_time_container');
const endTimeInput = document.getElementById('end_time');
const startTimeInput = document.getElementById('start_time');
const createEventForm = document.getElementById('createEventForm');
const initialAlertMessage = document.getElementById('initial-alert-message'); // Untuk pesan dari PHP saat pertama load
const ajaxAlertMessageContainer = document.getElementById('ajax-alert-message-container'); // Untuk pesan dari AJAX

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
        if (!endTimeInput.value) { // Hanya tambahkan required jika input kosong
            endTimeInput.setAttribute('required', 'required');
        }
        setMinDateTimeLocal(endTimeInput, startTimeInput.value || getNowDateTimeLocal());
        // Jika endTimeInput.value sudah ada dan kurang dari startTimeInput.value, reset
        if (endTimeInput.value && endTimeInput.value < startTimeInput.value) {
            endTimeInput.value = startTimeInput.value;
        }
    } else {
        endTimeContainer.classList.add('hidden');
        endTimeInput.removeAttribute('required');
        endTimeInput.value = ''; // Kosongkan nilai saat disembunyikan
        endTimeInput.removeAttribute('min');
    }
}

// Fungsi untuk menampilkan pesan (sukses/error) dari AJAX
function showAjaxAlert(message, type = 'success') {
    if (ajaxAlertMessageContainer) {
        ajaxAlertMessageContainer.innerHTML = ''; // Clear previous messages
        const newAlert = document.createElement('div');
        newAlert.className = `mb-4 p-3 rounded font-medium ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} text-white`;
        newAlert.textContent = message;
        ajaxAlertMessageContainer.appendChild(newAlert);
        setTimeout(() => {
            newAlert.remove(); // Hapus pesan setelah beberapa detik
        }, 3000); // Pesan akan hilang setelah 3 detik
    }
}


// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    // Sembunyikan pesan awal dari PHP setelah beberapa detik
    if (initialAlertMessage) {
        setTimeout(() => { initialAlertMessage.remove(); }, 3000);
    }

    setMinDateTimeLocal(startTimeInput, getNowDateTimeLocal());
    // Jika startTimeInput sudah memiliki nilai dari old_input dan nilainya di masa lalu, kosongkan
    if (startTimeInput.value && startTimeInput.value < getNowDateTimeLocal()) {
        startTimeInput.value = '';
    }
    toggleEndTimeVisibility();

    // Event listeners
    includeEndTimeCheckbox.addEventListener('change', toggleEndTimeVisibility);
    startTimeInput.addEventListener('change', () => {
        if (includeEndTimeCheckbox.checked) {
            if (startTimeInput.value) {
                setMinDateTimeLocal(endTimeInput, startTimeInput.value);
                if (endTimeInput.value && endTimeInput.value < startTimeInput.value) {
                    endTimeInput.value = startTimeInput.value;
                }
            } else {
                setMinDateTimeLocal(endTimeInput, getNowDateTimeLocal());
            }
        }
    });

    // Tambahkan event listener untuk form submission dengan AJAX
    createEventForm.addEventListener('submit', async function(e) {
        e.preventDefault(); // Mencegah form submit normal (reload halaman)

        // Clear previous AJAX alerts
        if (ajaxAlertMessageContainer) {
            ajaxAlertMessageContainer.innerHTML = '';
        }

        const formData = new FormData(this); // Mengambil semua data form

        // Jika include_end_time tidak dicentang, pastikan end_time tidak terkirim atau kosong
        if (!includeEndTimeCheckbox.checked) {
            formData.delete('end_time'); // Hapus jika ada
            formData.append('end_time', ''); // Atau kirim string kosong agar NULL di DB
            formData.append('include_end_time', 'off'); // Kirim status off
        } else {
             formData.append('include_end_time', 'on'); // Kirim status on
        }

        try {
            const response = await fetch(this.action, { // Kirim ke URL action form
                method: 'POST',
                body: formData
            });

            const result = await response.json(); // Mengasumsikan server mengembalikan JSON

            if (result.success) {
                showAjaxAlert(result.message, 'success');
                // Opsional: Redirect ke halaman daftar event setelah sukses
                setTimeout(() => {
                    window.location.href = '?c=event&m=index';
                }, 1500); // Redirect setelah 1.5 detik
            } else {
                showAjaxAlert(result.message, 'error');
                // Tetap di halaman create dan tampilkan error
            }
        } catch (error) {
            console.error('Error:', error);
            showAjaxAlert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.', 'error');
        }
    });

    // Tambahkan event listener untuk input end_time agar required state tetap konsisten
    endTimeInput.addEventListener('input', function() {
        if (includeEndTimeCheckbox.checked) {
            if (this.value) {
                this.setAttribute('required', 'required');
            } else {
                this.removeAttribute('required');
            }
        }
    });
});
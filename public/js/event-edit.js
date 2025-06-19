console.log('eventEdit.js connected');

const includeEndTimeCheckbox = document.getElementById('include_end_time');
const endTimeContainer = document.getElementById('end_time_container');
const endTimeInput = document.getElementById('end_time');
const startTimeInput = document.getElementById('start_time');
const editEventForm = document.getElementById('editEventForm'); // Ambil form
const initialAlertMessage = document.getElementById('initial-alert-message'); // Untuk pesan dari PHP saat pertama load
const ajaxAlertMessageContainer = document.getElementById('ajax-alert-message-container'); // Untuk pesan dari AJAX

function getNowDateTimeLocal() {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    return now.toISOString().slice(0, 16);
}

function setMinDateTimeLocal(inputElement, minDateTime) {
    if (inputElement) {
        inputElement.setAttribute('min', minDateTime);
    }
}

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


document.addEventListener('DOMContentLoaded', () => {
    // Sembunyikan pesan awal dari PHP setelah beberapa detik
    if (initialAlertMessage) {
        setTimeout(() => { initialAlertMessage.remove(); }, 3000);
    }

    const now = getNowDateTimeLocal();
    const currentStartTimeValue = startTimeInput.value; // Ambil nilai start_time dari PHP

    // Modifikasi bagian ini untuk startTimeInput
    // Jika currentStartTimeValue adalah masa lalu dari 'now', biarkan saja.
    // Jika currentStartTimeValue adalah masa depan atau sekarang, set min ke currentStartTimeValue atau now (mana yang lebih besar)
    if (currentStartTimeValue && currentStartTimeValue < now) {
        // Event ini di masa lalu, biarkan inputnya, tidak perlu set min attribute
        // agar user bisa menyimpan tanpa error validasi jika tidak mengubah waktu
        startTimeInput.removeAttribute('min');
    } else {
        // Event ini di masa depan atau sekarang, set min ke waktu saat ini
        setMinDateTimeLocal(startTimeInput, now);
    }


    toggleEndTimeVisibility(); //

    // Event listener untuk checkbox
    includeEndTimeCheckbox.addEventListener('change', toggleEndTimeVisibility);

    // Event listener untuk start_time
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

    // Event listener untuk end_time (untuk mengelola required)
    endTimeInput.addEventListener('input', function () {
        if (includeEndTimeCheckbox.checked) {
            if (this.value) {
                this.setAttribute('required', 'required');
            } else {
                this.removeAttribute('required');
            }
        }
    });

    // Tambahkan event listener untuk form submission dengan AJAX
    editEventForm.addEventListener('submit', async function(e) {
        e.preventDefault(); // Mencegah form submit normal

        // Clear previous AJAX alerts
        if (ajaxAlertMessageContainer) {
            ajaxAlertMessageContainer.innerHTML = '';
        }

        const formData = new FormData(this); //

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

            const result = await response.json(); //

            if (result.success) {
                showAjaxAlert(result.message, 'success'); //
                // Opsional: Redirect ke halaman daftar event setelah sukses
                setTimeout(() => {
                    window.location.href = '?c=event&m=index';
                }, 1500);
            } else {
                showAjaxAlert(result.message, 'error'); //
                // Tetap di halaman edit dan tampilkan error
            }
        } catch (error) {
            console.error('Error:', error);
            showAjaxAlert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.', 'error'); //
        }
    });
});
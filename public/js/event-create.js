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
        setMinDateTimeLocal(endTimeInput, startTimeInput.value || getNowDateTimeLocal());
    } else {
        endTimeContainer.classList.add('hidden');
        endTimeInput.removeAttribute('required');
        endTimeInput.value = '';
        endTimeInput.removeAttribute('min');
    }
}

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    setMinDateTimeLocal(startTimeInput, getNowDateTimeLocal());
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
});

console.log('eventEdit.js connected');

const includeEndTimeCheckbox = document.getElementById('include_end_time');
const endTimeContainer = document.getElementById('end_time_container');
const endTimeInput = document.getElementById('end_time');
const startTimeInput = document.getElementById('start_time');

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
        if (!endTimeInput.value || endTimeInput.value < startTimeInput.value) {
            endTimeInput.setAttribute('required', 'required');
        }
        setMinDateTimeLocal(endTimeInput, startTimeInput.value || getNowDateTimeLocal());
    } else {
        endTimeContainer.classList.add('hidden');
        endTimeInput.removeAttribute('required');
        endTimeInput.removeAttribute('min');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const now = getNowDateTimeLocal();
    setMinDateTimeLocal(startTimeInput, now);

    if (startTimeInput.value && startTimeInput.value < now) {
        startTimeInput.value = '';
    }

    toggleEndTimeVisibility();
});

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

endTimeInput.addEventListener('input', function () {
    if (endTimeInput.value !== '') {
        endTimeInput.setAttribute('required', 'required');
    } else {
        endTimeInput.removeAttribute('required');
    }
});

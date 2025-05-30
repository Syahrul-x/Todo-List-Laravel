// Toggle profile dropdown
document.getElementById('userProfile').addEventListener('click', function(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('hidden');
});
document.addEventListener('click', function() {
    const dropdown = document.getElementById('profileDropdown');
    if (!dropdown.classList.contains('hidden')) {
      dropdown.classList.add('hidden');
    }
});
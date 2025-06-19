// Fungsi untuk melakukan pencarian kategori menggunakan AJAX
$('#searchInput').on('input', function() {
    var searchTerm = $(this).val();

    $.ajax({
        url: '?c=category&m=search',  // Pastikan route sesuai dengan controller dan metode yang dipanggil
        method: 'GET',
        data: { search: searchTerm },  // Kirimkan parameter pencarian ke controller
        success: function(response) {
            var categories = JSON.parse(response);
            var tableBody = $('#categoryTableBody');
            tableBody.empty();  // Clear existing categories

            if (categories.length > 0) {
                categories.forEach(function(category) {
                    tableBody.append('<tr id="category-' + category.id + '" class="border-b border-gray-600 hover:bg-[#3a3a3a]">' +
                        '<td class="py-3 px-6 whitespace-nowrap">' + category.name + '</td>' +
                        '<td class="py-3 px-6">' + category.description + '</td>' +
                        '<td class="py-3 px-6 text-center">' +
                            '<a href="?c=category&m=edit&id=' + category.id + '" class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>' +
                            '<a href="?c=category&m=delete&id=' + category.id + '" class="text-red-500 hover:text-red-700" onclick="return confirm(\'Yakin ingin menghapus kategori ini?\')">Delete</a>' +
                        '</td>' +
                    '</tr>');
                });
            } else {
                tableBody.append('<tr><td colspan="4" class="text-center text-gray-400">No categories found.</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
});

// Pastikan DOM sudah siap
document.addEventListener("DOMContentLoaded", function() {
    // Cek apakah elemen dengan ID 'error-message' ada
    var errorMessage = document.getElementById('error-message');
    
    if (errorMessage) {
        // Menyembunyikan elemen setelah 3 detik
        setTimeout(function() {
            errorMessage.style.display = 'none';
        }, 3000); // 3000 milidetik = 3 detik
    }
});
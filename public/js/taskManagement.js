// public/js/taskManagement.js

document.addEventListener("DOMContentLoaded", function() {
    // Fungsi untuk menyembunyikan pesan setelah 3 detik
    const hideAlertMessages = () => {
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');

        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 3000);
        }
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }
    };

    // Panggil saat DOM dimuat untuk pesan dari PHP
    hideAlertMessages();

    // Fungsi untuk memuat ulang tabel tugas dengan AJAX
    const loadTasks = (searchTerm = '', categoryId = '', priorityId = '') => {
        const url = new URL(window.location.origin + window.location.pathname);
        url.searchParams.append('c', 'dashboard');
        url.searchParams.append('m', 'searchTasks'); // Metode AJAX baru di DashboardController

        // Hanya tambahkan parameter jika tidak kosong
        if (searchTerm !== '') { // Cek string kosong juga
            url.searchParams.append('search', searchTerm);
        }
        if (categoryId !== '') { // Cek string kosong juga
            url.searchParams.append('category_id', categoryId);
        }
        if (priorityId !== '') { // Cek string kosong juga
            url.searchParams.append('priority_id', priorityId);
        }

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(tasks => {
                const tableBody = document.getElementById('taskTableBody');
                if (!tableBody) { // Tambahkan pengecekan jika elemen tidak ditemukan
                    console.error('Elemen #taskTableBody tidak ditemukan.');
                    return;
                }
                tableBody.innerHTML = ''; // Kosongkan isi tabel

                if (tasks.length > 0) {
                    tasks.forEach(task => {
                        // Pastikan nilai 'null' tidak menyebabkan error pada htmlspecialchars
                        const title = escapeHtml(task.title || '');
                        const description = escapeHtml(task.description || '');
                        const categoryName = escapeHtml(task.category_name || '-');
                        const priorityName = escapeHtml(task.priority_name || '-');
                        const createdAt = escapeHtml(task.created_at || '');

                        const statusClass = task.status === 'Selesai' ? 'bg-green-600' :
                                            task.status === 'Sedang Dikerjakan' ? 'bg-blue-600' :
                                            'bg-yellow-600';
                        const isFavoritedClass = (task.is_favorited && task.is_favorited == 1) ? 'fas' : 'far';

                        tableBody.innerHTML += `
                            <tr class="border-b border-gray-600 hover:bg-[#3a3a3a]">
                                <td class="py-3 px-6 whitespace-nowrap">${title}</td>
                                <td class="py-3 px-6">${description.substring(0, 50)} ${description.length > 50 ? '...' : ''}</td>
                                <td class="py-3 px-6 whitespace-nowrap">
                                    <span class="py-1 px-3 rounded-full text-xs ${statusClass}">
                                        ${escapeHtml(task.status)}
                                    </span>
                                </td>
                                <td class="py-3 px-6 whitespace-nowrap">${categoryName}</td>
                                <td class="py-3 px-6 whitespace-nowrap">${priorityName}</td>
                                <td class="py-3 px-6 whitespace-nowrap">${createdAt}</td>
                                <td class="py-3 px-6 whitespace-nowrap text-center">
                                    <a href="?c=favorite&m=toggle&task_id=${escapeHtml(task.id)}"
                                        class="text-yellow-500 hover:text-yellow-600 font-medium mr-3"
                                        title="${(task.is_favorited && task.is_favorited == 1) ? 'Hapus dari favorit' : 'Tambahkan ke favorit'}">
                                        <i class="${isFavoritedClass} fa-star"></i>
                                    </a>
                                    <a href="?c=tugas&m=update&id=${escapeHtml(task.id)}"
                                        class="text-[#2684FF] hover:text-[#006bb3] font-medium mr-3">Edit</a>
                                    <a href="?c=tugas&m=delete&id=${escapeHtml(task.id)}"
                                        onclick="return confirm('Yakin ingin menghapus tugas ini?')"
                                        class="text-red-500 hover:text-red-700 font-medium">Hapus</a>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tableBody.innerHTML = `<tr><td colspan="7" class="py-6 px-6 text-center text-gray-400">Belum ada tugas.</td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error loading tasks:', error);
                const tableBody = document.getElementById('taskTableBody');
                if (tableBody) {
                    tableBody.innerHTML = `<tr><td colspan="7" class="py-6 px-6 text-center text-red-500">Gagal memuat tugas. Silakan coba lagi.</td></tr>`;
                }
            });
    };

    // Helper function to escape HTML for security
    function escapeHtml(text) {
        // htmlspecialchars in PHP typically converts null to empty string.
        // In JS, ensure it's treated as string before replacement.
        const strText = String(text || ''); // Convert null/undefined to empty string

        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return strText.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Mendapatkan elemen filter
    const searchInput = document.getElementById('taskSearchInput');
    const categoryFilterSelect = document.getElementById('categoryFilterSelect');
    const priorityFilterSelect = document.getElementById('priorityFilterSelect');

    // Event listener untuk input pencarian (saat mengetik)
    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            const currentCategory = categoryFilterSelect ? categoryFilterSelect.value : '';
            const currentPriority = priorityFilterSelect ? priorityFilterSelect.value : '';
            loadTasks(searchInput.value, currentCategory, currentPriority);
        });
    }

    // Event listener untuk filter kategori (saat pilihan berubah)
    if (categoryFilterSelect) {
        categoryFilterSelect.addEventListener('change', () => {
            const currentSearch = searchInput ? searchInput.value : '';
            const currentPriority = priorityFilterSelect ? priorityFilterSelect.value : '';
            loadTasks(currentSearch, categoryFilterSelect.value, currentPriority);
        });
    }

    // Event listener untuk filter prioritas (saat pilihan berubah)
    if (priorityFilterSelect) {
        priorityFilterSelect.addEventListener('change', () => {
            const currentSearch = searchInput ? searchInput.value : '';
            const currentCategory = categoryFilterSelect ? categoryFilterSelect.value : '';
            loadTasks(currentSearch, currentCategory, priorityFilterSelect.value);
        });
    }

    // Memuat tugas saat halaman pertama kali dimuat (dengan nilai awal dari PHP)
    // Nilai awal diambil dari atribut `value` pada input search dan `selected` pada option select
    const initialSearchTerm = searchInput ? searchInput.value : '';
    const initialCategory = categoryFilterSelect ? categoryFilterSelect.value : '';
    const initialPriority = priorityFilterSelect ? priorityFilterSelect.value : '';
    loadTasks(initialSearchTerm, initialCategory, initialPriority);
});
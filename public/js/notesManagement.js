// public/js/notesManagement.js

document.addEventListener("DOMContentLoaded", function() {
    
    // Fungsi pembantu untuk escape HTML, mencegah XSS
    function escapeHtml(text) {
        const strText = String(text || ''); // Pastikan input adalah string
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return strText.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Fungsi untuk menghandle pencarian di halaman meetingnotes
    const meetingSearchInput = document.getElementById('meetingNotesSearchInput');
    if (meetingSearchInput) {
        meetingSearchInput.addEventListener('input', function() {
            const searchTerm = this.value;

            fetch(`?c=notes&m=search&search=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('meetingNotesTableBody');
                    tableBody.innerHTML = ''; // Kosongkan tabel
                    if (data.length > 0) {
                        data.forEach(note => {
                            const row = `
                                <tr class="border-b border-gray-600 hover:bg-[#3a3a3a]">
                                    <td class="py-3 px-6">${escapeHtml(note.title)}</td>
                                    <td class="py-3 px-6">${escapeHtml(note.description)}</td>
                                    <td class="py-3 px-6">${escapeHtml(note.created_at)}</td>
                                    <td class="py-3 px-6 text-center">
                                        <a href="?c=notes&m=edit&id=${note.id}" class="text-[#2684FF] hover:text-[#006bb3] font-medium mr-3">Edit</a>
                                        <a href="?c=notes&m=destroy&id=${note.id}" onclick="return confirm('Yakin ingin menghapus catatan ini?')" class="text-red-500 hover:text-red-700 font-medium">Hapus</a>
                                    </td>
                                </tr>
                            `;
                            tableBody.innerHTML += row;
                        });
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="4" class="py-6 px-6 text-center text-gray-400">Catatan tidak ditemukan.</td></tr>';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }

    // Fungsi untuk menghandle pencarian di halaman managenotes (admin)
    const manageSearchInput = document.getElementById('manageNotesSearchInput');
    if (manageSearchInput) {
        manageSearchInput.addEventListener('input', function() {
            const searchTerm = this.value;
            // Tambahkan parameter 'context=manage' untuk memberitahu controller ini adalah pencarian admin
            fetch(`?c=notes&m=search&search=${encodeURIComponent(searchTerm)}&context=manage`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('manageNotesTableBody');
                    tableBody.innerHTML = ''; // Kosongkan tabel
                    if (data.length > 0) {
                        data.forEach(note => {
                            const row = `
                                <tr class="border-b border-gray-600 hover:bg-[#3a3a3a]">
                                    <td class="py-3 px-6">${escapeHtml(note.user_id)}</td>
                                    <td class="py-3 px-6">${escapeHtml(note.title)}</td>
                                    <td class="py-3 px-6">${escapeHtml(note.description)}</td>
                                    <td class="py-3 px-6">${escapeHtml(note.created_at)}</td>
                                    <td class="py-3 px-6 text-center">
                                        <a href="?c=notes&m=edit&id=${note.id}" class="text-[#2684FF] hover:text-[#006bb3] font-medium mr-3">Edit</a>
                                        <a href="?c=notes&m=destroy&id=${note.id}" onclick="return confirm('Yakin ingin menghapus catatan ini?')" class="text-red-500 hover:text-red-700 font-medium">Hapus</a>
                                    </td>
                                </tr>
                            `;
                            tableBody.innerHTML += row;
                        });
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="5" class="py-6 px-6 text-center text-gray-400">Catatan tidak ditemukan.</td></tr>';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }

});
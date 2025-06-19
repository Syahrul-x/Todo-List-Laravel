<h2 class="text-2xl font-bold mb-4">Tambah Catatan Rapat</h2>

<form action="?c=notes&m=store" method="POST" class="space-y-4">
  <input type="hidden" name="_token" value="<?= 123456789?>">

  <div>
    <label for="title" class="block mb-1">Judul:</label>
    <input type="text" id="title" name="title" class="w-full px-4 py-2 bg-gray-700 rounded" required>
  </div>

  <div>
    <label for="description" class="block mb-1">Isi Catatan:</label>
    <textarea id="description" name="description" class="w-full px-4 py-2 bg-gray-700 rounded" rows="5" required></textarea>
  </div>

  <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white">Simpan</button>
  <a href="?c=notes&m=index" class="ml-4 text-gray-300 underline">Batal</a>
</form>

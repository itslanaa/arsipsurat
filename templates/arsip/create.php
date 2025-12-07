<div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
    <h3 class="text-xl font-semibold mb-6 border-b pb-4">Form Tambah Arsip Baru</h3>
    
    <form action="<?= BASE_URL; ?>/arsip/store" method="post" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Dokumen</label>
            <input type="text" id="judul" name="judul" class="block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="id_kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select id="id_kategori" name="id_kategori" class="block w-full rounded-md border-gray-300 shadow-sm" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $kat) : ?>
                    <option value="<?= $kat['id']; ?>"><?= htmlspecialchars($kat['kode'] . ' - ' . $kat['nama_kategori']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">File Arsip</label>
            <div id="file-upload-container" class="space-y-3">
                <!-- Input file pertama -->
                <div class="flex items-center">
                    <input type="file" name="files[]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                </div>
            </div>
            <button type="button" id="addFileButton" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-semibold">
                <i class="fas fa-plus mr-1"></i> Tambah File Lain
            </button>
            <p class="text-xs text-gray-500 mt-1">Anda bisa mengunggah lebih dari satu file.</p>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="<?= BASE_URL; ?>/arsip" class="text-gray-600 hover:text-gray-900">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                Simpan Arsip
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('addFileButton').addEventListener('click', function() {
    const container = document.getElementById('file-upload-container');
    const newFileInput = document.createElement('div');
    newFileInput.classList.add('flex', 'items-center');
    newFileInput.innerHTML = `
        <input type="file" name="files[]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
        <button type="button" class="remove-file-btn ml-2 text-red-500 hover:text-red-700">&times;</button>
    `;
    container.appendChild(newFileInput);

    newFileInput.querySelector('.remove-file-btn').addEventListener('click', function() {
        newFileInput.remove();
    });
});
</script>
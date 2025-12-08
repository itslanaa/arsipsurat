<div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
    <h3 class="text-xl font-semibold mb-6 border-b pb-4">Form Edit Arsip</h3>

    <form action="<?= BASE_URL; ?>/arsip/update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $arsip['id']; ?>">

        <div class="mb-4">
            <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Dokumen</label>
            <input type="text" id="judul" name="judul" class="block w-full rounded-md border-gray-300 shadow-sm" value="<?= htmlspecialchars($arsip['judul']); ?>" required>
        </div>

        <div class="mb-4">
            <label for="id_kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select id="id_kategori" name="id_kategori" class="block w-full rounded-md border-gray-300 shadow-sm" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $kat) : ?>
                    <option value="<?= $kat['id']; ?>" <?= ($kat['id'] == $arsip['id_kategori']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($kat['kode'] . ' - ' . $kat['nama_kategori']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Dibuat Oleh</label>
            <input type="text" id="author" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed" value="<?= htmlspecialchars($arsip['author'] ?? 'N/A'); ?>" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">File Tersimpan</label>
            <div id="existing-files-list" class="space-y-2">
                <?php if (empty($arsip['files'])) : ?>
                    <p class="text-sm text-gray-500">Tidak ada file terlampir.</p>
                <?php else : ?>
                    <?php foreach ($arsip['files'] as $file) : ?>
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                            <a href="<?= BASE_URL; ?>/arsip/downloadFile/<?= $file['id']; ?>" class="text-blue-600 hover:underline text-sm" title="Klik untuk mengunduh">
                                <i class="fas fa-download mr-2"></i>
                                <?= htmlspecialchars($file['nama_file_asli']); ?> (<?= formatBytes($file['filesize'] ?? 0); ?>)
                            </a>
                            <button type="button"
                                onclick="showConfirmation('Konfirmasi Hapus', 'Yakin ingin menghapus file: <?= htmlspecialchars(addslashes($file['nama_file_asli'])); ?>?', '<?= BASE_URL; ?>/arsip/deleteFile/<?= $file['id']; ?>/<?= $arsip['id']; ?>', 'Ya, Hapus', 'danger')"
                                class="text-red-500 hover:text-red-700 text-lg">&times;</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tambah File Baru</label>
            <div id="file-upload-container" class="space-y-3">
                <div class="flex items-center">
                    <input type="file" name="files[]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>
            <button type="button" id="addFileButtonEdit" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-semibold">
                <i class="fas fa-plus mr-1"></i> Tambah File Lain
            </button>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="<?= BASE_URL; ?>/arsip" class="text-gray-600 hover:text-gray-900">Batal</a>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                Update Arsip
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('addFileButtonEdit').addEventListener('click', function() {
        const container = document.getElementById('file-upload-container');
        const newFileInput = document.createElement('div');
        newFileInput.classList.add('flex', 'items-center', 'mt-2');
        newFileInput.innerHTML = `
        <input type="file" name="files[]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        <button type="button" class="remove-file-btn ml-2 text-red-500 hover:text-red-700">&times;</button>
    `;
        container.appendChild(newFileInput);

        newFileInput.querySelector('.remove-file-btn').addEventListener('click', function() {
            newFileInput.remove();
        });
    });
</script>
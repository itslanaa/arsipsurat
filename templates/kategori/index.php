<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold">Manajemen Kategori Arsip</h3>
        <!-- Tombol ini akan memicu modal untuk menambah kategori baru -->
        <button id="tombolTambahKategori" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">
            <i class="fas fa-plus mr-2"></i>Tambah Kategori
        </button>
    </div>
    <p class="text-sm text-gray-600 mb-6">Kelola semua kategori yang akan digunakan untuk pengarsipan dokumen.</p>
    
    <!-- Tabel Kategori -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Aksi</th>
                </tr>
            </thead>
            <tbody id="kategoriTableBody" class="bg-white divide-y divide-gray-200">
                <?php $no = 1; ?>
                <?php foreach ($kategori as $kat) : ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-800"><?= htmlspecialchars($kat['kode']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($kat['nama_kategori']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="tombolEditKategori text-yellow-600 hover:text-yellow-900" data-id="<?= $kat['id']; ?>" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <!-- === -->
                            <button type="button" 
                                    onclick="showConfirmation('Konfirmasi Hapus', 'Apakah Anda yakin ingin menghapus kategori ini?', '<?= BASE_URL; ?>/kategori/destroy/<?= $kat['id']; ?>', 'Ya, Hapus', 'danger')" 
                                    class="text-red-600 hover:text-red-900 ml-4" 
                                    title="Hapus">
                                <i class="fas fa-trash"></i> 
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- === PERBAIKAN DI SINI: Memanggil Flasher === -->
    <?php Flasher::flash(); ?>
</div>

<!-- Modal untuk Tambah/Edit Kategori -->
<div id="kategoriModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Tambah Kategori Baru</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-5">
            <form id="kategoriForm" action="" method="post">
                <input type="hidden" id="kategoriId" name="id">
                
                <div class="mb-4">
                    <label for="kode_kategori" class="block text-sm font-medium text-gray-700">Kode Klasifikasi</label>
                    <input type="text" name="kode" id="kode_kategori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Mis. 800.1" required>
                </div>

                <div>
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                
                <div class="flex justify-end mt-6">
                    <button type="button" id="cancelModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md mr-2 hover:bg-gray-300">Batal</button>
                    <button type="submit" id="submitModal" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript untuk fungsionalitas modal CRUD -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('kategoriModal');
    if (!modal) return; // Pastikan script hanya berjalan di halaman kategori

    const closeModalBtn = document.getElementById('closeModal');
    const cancelModalBtn = document.getElementById('cancelModal');
    const tombolTambah = document.getElementById('tombolTambahKategori');
    const tombolEdit = document.querySelectorAll('.tombolEditKategori');
    
    const modalTitle = document.getElementById('modalTitle');
    const kategoriForm = document.getElementById('kategoriForm');
    const kategoriIdInput = document.getElementById('kategoriId');
    const kodeKategoriInput = document.getElementById('kode_kategori');
    const namaKategoriInput = document.getElementById('nama_kategori');
    const baseUrl = '<?= BASE_URL; ?>';

    const openModal = () => modal.classList.remove('hidden');
    const closeModal = () => modal.classList.add('hidden');

    closeModalBtn.addEventListener('click', closeModal);
    cancelModalBtn.addEventListener('click', closeModal);

    tombolTambah.addEventListener('click', () => {
        modalTitle.innerText = 'Tambah Kategori Baru';
        kategoriForm.action = `${baseUrl}/kategori/store`;
        kategoriIdInput.value = '';
        kodeKategoriInput.value = '';
        namaKategoriInput.value = '';
        kodeKategoriInput.focus();
        openModal();
    });

    tombolEdit.forEach(button => {
        button.addEventListener('click', async () => {
            const id = button.dataset.id;
            
            try {
                const response = await fetch(`${baseUrl}/kategori/getUbah`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                });
                const data = await response.json();

                modalTitle.innerText = 'Edit Kategori';
                kategoriForm.action = `${baseUrl}/kategori/update`;
                kategoriIdInput.value = data.id;
                kodeKategoriInput.value = data.kode;
                namaKategoriInput.value = data.nama_kategori;
                kodeKategoriInput.focus();
                openModal();
            } catch (error) {
                console.error('Gagal mengambil data kategori:', error);
            }
        });
    });
});
</script>

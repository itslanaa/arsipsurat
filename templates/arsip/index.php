<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h3 class="text-xl font-semibold mb-3 sm:mb-0">Manajemen Arsip Digital</h3>
        <a href="<?= BASE_URL; ?>/arsip/create" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300 w-full sm:w-auto text-center">
            <i class="fas fa-plus mr-2"></i>Tambah Arsip
        </a>
    </div>
    <p class="text-sm text-gray-600 mb-6">Di sini Anda dapat mengelola semua dokumen yang diarsipkan. Gunakan fitur pencarian dan filter untuk menemukan dokumen dengan cepat.</p>
    
    <!-- Filter dan Pencarian dengan Rentang Tanggal -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 items-end">
        <div class="lg:col-span-2">
            <label for="searchInput" class="block text-sm font-medium text-gray-700">Cari Judul Dokumen</label>
            <input type="text" id="searchInput" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ketik untuk mencari...">
        </div>
        <div>
            <label for="kategoriFilter" class="block text-sm font-medium text-gray-700">Filter Kategori</label>
            <select id="kategoriFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategori as $kat) : ?>
                    <option value="<?= htmlspecialchars($kat['id']); ?>"><?= htmlspecialchars($kat['kode'] . ' - ' . $kat['nama_kategori']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label for="tglMulaiFilter" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" id="tglMulaiFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="tglAkhirFilter" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" id="tglAkhirFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </div>
    </div>

    <!-- Tabel Arsip (Responsif) -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Dokumen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Kategori</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Jumlah File</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Tgl Upload</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="arsipTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Kontrol Paginasi -->
    <div id="paginationControls" class="flex justify-center mt-6">
        <!-- Tombol paginasi akan di-generate oleh JavaScript -->
    </div>
    
    <!--  Panggil Flasher untuk mencetak data notifikasi === -->
    <?php Flasher::flash(); ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const kategoriFilter = document.getElementById('kategoriFilter');
    const tglMulaiFilter = document.getElementById('tglMulaiFilter');
    const tglAkhirFilter = document.getElementById('tglAkhirFilter');
    const tableBody = document.getElementById('arsipTableBody');
    const paginationControls = document.getElementById('paginationControls');
    const baseUrl = '<?= BASE_URL; ?>';

    let fullArsipData = [];
    let currentPage = 1;
    const rowsPerPage = 10;

    function formatDate(dateString) {
        if (!dateString) return '';
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    function renderPagination() {
        paginationControls.innerHTML = '';
        const pageCount = Math.ceil(fullArsipData.length / rowsPerPage);
        if (pageCount <= 1) return;

        for (let i = 1; i <= pageCount; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.classList.add('px-4', 'py-2', 'mx-1', 'rounded-md', 'text-sm', 'font-medium', 'transition-colors');
            if (i === currentPage) {
                btn.classList.add('bg-blue-600', 'text-white');
            } else {
                btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300', 'hover:bg-gray-50');
            }
            btn.addEventListener('click', () => {
                currentPage = i;
                renderTable();
                renderPagination();
            });
            paginationControls.appendChild(btn);
        }
    }

    function renderTable() {
        tableBody.innerHTML = '';
        if (fullArsipData.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-10 text-gray-500">Data tidak ditemukan.</td></tr>`;
            return;
        }

        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        const paginatedData = fullArsipData.slice(startIndex, endIndex);

        let html = '';
        paginatedData.forEach((doc, index) => {
            const rowNumber = startIndex + index + 1;
            const downloadUrl = `${baseUrl}/arsip/download/${doc.id}`;
            const downloadTitle = doc.jumlah_file > 1 ? 'Unduh sebagai ZIP' : 'Unduh File';
            const kodeLabel = doc.kode_klasifikasi ? `<span class="px-2 py-1 rounded bg-slate-100 text-slate-700">${doc.kode_klasifikasi}</span>` : '';
            const refLabel = doc.id_surat_masuk ? `<span class="px-2 py-1 rounded bg-emerald-50 text-emerald-700">Ref Surat Masuk #${doc.id_surat_masuk}</span>` : '';

            html += `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${rowNumber}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">${doc.judul}</div>
                        <div class="flex flex-wrap gap-2 mt-2">${kodeLabel}${refLabel}</div>
                        <div class="text-sm text-gray-500 md:hidden mt-1">
                            <p><strong>Kategori:</strong> ${doc.nama_kategori}</p>
                            <p><strong>Jumlah File:</strong> ${doc.jumlah_file}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">${doc.nama_kategori}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center hidden md:table-cell">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            ${doc.jumlah_file}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                    ${doc.tgl_upload.split('-').reverse().join('-')}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="${downloadUrl}" class="text-blue-600 hover:text-blue-900" title="${downloadTitle}"><i class="fas fa-download"></i></a>
                        <a href="${baseUrl}/arsip/edit/${doc.id}" class="text-yellow-600 hover:text-yellow-900 ml-4" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                        <button type="button" onclick="showConfirmation('Konfirmasi Hapus', 'Apakah Anda yakin ingin menghapus arsip ini?', '${baseUrl}/arsip/destroy/${doc.id}')" class="text-red-600 hover:text-red-900 ml-4" title="Hapus"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
        });
        tableBody.innerHTML = html;
    }

    async function fetchArsip() {
        const formData = new FormData();
        formData.append('keyword', searchInput.value);
        formData.append('kategori', kategoriFilter.value);
        formData.append('tanggal_mulai', tglMulaiFilter.value);
        formData.append('tanggal_akhir', tglAkhirFilter.value);

        try {
            const response = await fetch(`${baseUrl}/arsip/search`, {
                method: 'POST',
                body: formData
            });
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            fullArsipData = data;
            currentPage = 1; 
            renderTable();
            renderPagination();
        } catch (error) {
            console.error('Error fetching data:', error);
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-10 text-red-500">Gagal memuat data.</td></tr>`;
        }
    }

    searchInput.addEventListener('input', fetchArsip);
    kategoriFilter.addEventListener('change', fetchArsip);
    tglMulaiFilter.addEventListener('change', fetchArsip);
    tglAkhirFilter.addEventListener('change', fetchArsip);

    fetchArsip();
});
</script>

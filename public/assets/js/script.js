document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('.nav-link');
    const pages = document.querySelectorAll('.page');
    const pageTitle = document.getElementById('page-title');

    const pageTitles = {
        'dashboard': 'Dashboard',
        'arsip': 'Arsip Digital',
        'surat': 'Buat Surat Keluar',
        'pengaturan': 'Pengaturan'
    };

    function showPage(targetId) {
        // Logika untuk menampilkan halaman yang sesuai
        // ... (Ini akan ditambahkan di file view utama)
    }

    // --- Logika untuk Live Preview Surat ---
    const suratForm = document.getElementById('suratForm');
    if (suratForm) {
        const inputs = {
            noSurat: document.getElementById('noSurat'),
            tglSurat: document.getElementById('tglSurat'),
            dasarSurat: document.getElementById('dasarSurat'),
            pegawaiNama: document.getElementById('pegawaiNama'),
            pegawaiPangkat: document.getElementById('pegawaiPangkat'),
            pegawaiNip: document.getElementById('pegawaiNip'),
            pegawaiJabatan: document.getElementById('pegawaiJabatan'),
            tugasSurat: document.getElementById('tugasSurat'),
        };

        const previews = {
            noSurat: document.getElementById('preview-noSurat'),
            tglSurat: document.getElementById('preview-tglSurat'),
            dasar: document.getElementById('preview-dasar'),
            pegawaiNama: document.getElementById('preview-pegawaiNama'),
            pegawaiPangkat: document.getElementById('preview-pegawaiPangkat'),
            pegawaiNip: document.getElementById('preview-pegawaiNip'),
            pegawaiJabatan: document.getElementById('preview-pegawaiJabatan'),
            tugas: document.getElementById('preview-tugas'),
        };

        function updatePreview() {
            // Update Nomor Surat
            previews.noSurat.innerHTML = inputs.noSurat.value.replace(/ /g, '&nbsp;') || '...';
            
            // Update Tanggal Surat
            if (inputs.tglSurat.value) {
                const tgl = new Date(inputs.tglSurat.value);
                previews.tglSurat.innerText = `Cibungbulang, ${tgl.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}`;
            } else {
                previews.tglSurat.innerText = 'Cibungbulang, ...';
            }

            // Update Textarea dengan konversi newline ke <br>
            previews.dasar.innerHTML = inputs.dasarSurat.value.replace(/\n/g, '<br>') || '...';
            previews.tugas.innerHTML = inputs.tugasSurat.value.replace(/\n/g, '<br>') || '...';

            // Update Info Pegawai
            previews.pegawaiNama.innerText = inputs.pegawaiNama.value || '...';
            previews.pegawaiPangkat.innerText = inputs.pegawaiPangkat.value || '...';
            previews.pegawaiNip.innerText = inputs.pegawaiNip.value || '...';
            previews.pegawaiJabatan.innerText = inputs.pegawaiJabatan.value || '...';
        }

        suratForm.addEventListener('input', updatePreview);
        
        // Inisialisasi tanggal hari ini
        const today = new Date();
        const offset = today.getTimezoneOffset();
        const todayLocal = new Date(today.getTime() - (offset*60*1000));
        inputs.tglSurat.value = todayLocal.toISOString().split('T')[0];

        updatePreview(); // Panggil sekali saat halaman dimuat
    }

    // --- Logika untuk Filter Arsip (AJAX) ---
    const searchInput = document.getElementById('searchInput');
    const kategoriFilter = document.getElementById('kategoriFilter');
    const tglFilter = document.getElementById('tglFilter');

    function fetchAndRenderArsip() {
        const search = searchInput.value;
        const kategori = kategoriFilter.value;
        const tgl = tglFilter.value;
        
        // Nanti ini akan diganti dengan fetch ke API PHP
        console.log(`Mencari dengan: search=${search}, kategori=${kategori}, tgl=${tgl}`);
        // fetch(`/api/arsip.php?search=${search}&kategori=${kategori}&tgl=${tgl}`)
        // .then(response => response.json())
        // .then(data => {
        //     renderArsipTable(data);
        // });
    }

    if (searchInput) searchInput.addEventListener('input', fetchAndRenderArsip);
    if (kategoriFilter) kategoriFilter.addEventListener('change', fetchAndRenderArsip);
    if (tglFilter) tglFilter.addEventListener('change', fetchAndRenderArsip);

});

<style>
/* === PRINT FIX: biar lanjut ke halaman berikutnya & sidebar hilang === */
@media print {
  /* Halaman & margin */
  @page { size: A4; margin: 16mm; }
  html, body { height: auto !important; }
  body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

  /* Sembunyikan sidebar/header/footer/tombol saat print (tanpa ubah HTML) */
  aside, .sidebar, #sidebar, .app-sidebar, .sidenav, .navbar, header, footer,
  #btnPrint, #btnKeAtas { display: none !important; }

  /* Jangan ada overflow yang memotong konten */
  html, body, #app, main, .container, .content, .page, .wrapper {
    overflow: visible !important;
    max-height: none !important;
  }

  /* Flex sering bikin Chrome enggan memecah halaman → jadikan block saat print */
  .flex, .flex-col, .flex-row, .grid { display: block !important; }

  /* Izinkan section dipotong (agar lanjutan masuk ke halaman baru) */
  section { break-inside: auto !important; page-break-inside: auto !important; }

  /* Tapi hindari patahan di elemen tertentu */
  thead, tr, img, h3, h4 {
    break-inside: avoid !important; page-break-inside: avoid !important;
    page-break-after: avoid !important;
  }

  /* Header tabel berulang di tiap halaman */
  table { width: 100% !important; border-collapse: collapse !important; }
  thead { display: table-header-group; }
  tfoot { display: table-footer-group; }

  /* Gambar responsif saat print */
  img { max-width: 100% !important; height: auto !important; }

  /* Jika template luar pakai transform (zoom/scale), matikan saat print */
  [style*="transform"] { transform: none !important; }

  
  /* (Opsional) Paksa page break sebelum bagian-bagian panjang */
  #menu-utama, #arsip, #buat-surat, #generate-simpan, #flash {
    break-before: page; page-break-before: always;
  }

  /* Hilangkan bayangan yang suka bikin artefak */
  .shadow, .shadow-md, .shadow-lg, .ring, .ring-1, .ring-2 { box-shadow: none !important; }
}
</style>


<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <h3 class="text-xl font-semibold">Panduan Penggunaan Aplikasi Arsip Digital & Surat Keluar</h3>
        <div class="flex items-center gap-2">
            <button type="button" id="btnKeAtas" class="px-4 py-2 rounded-md border text-gray-700 hover:bg-gray-50 transition">⬆️ Ke Atas</button>
            <button type="button" id="btnPrint" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">🖨️ Cetak / Simpan PDF</button>
        </div>
    </div>

    <p class="text-sm text-gray-600 mb-6">
        Dokumen ini memandu pengguna dalam digitalisasi dokumen arsip dan pembuatan surat keluar berbasis template (mis. Surat Keterangan, Surat Tugas) pada lingkungan kerja kecamatan.
    </p>

    <!-- Shortcut Nav (TOC ringan) -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="#pendahuluan" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Pendahuluan</a>
        <a href="#instalasi" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Instalasi</a>
        <a href="#akses-login" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Akses & Login</a>
        <a href="#menu-utama" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Menu Utama</a>
        <a href="#buat-surat" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Membuat Surat</a>
        <a href="#generate-simpan" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Generate & Simpan</a>
        <a href="#arsip" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Manajemen Arsip</a>
        <a href="#flash" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Notifikasi</a>
    </div>

    <!-- 1. Pendahuluan -->
    <section id="pendahuluan" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">1. Pendahuluan</h4>
        <div class="p-3 rounded-md bg-blue-50 border border-blue-100 text-sm">
            Aplikasi berfungsi untuk mendigitalisasi dokumen arsip dan proses pembuatan surat keluar dengan <em>template</em> seragam. Pengguna dapat mengunggah file arsip,
            dan akan tersimpan sebagai arsip digital yang agar mudah dilacak kembali.
        </div>
    </section>

    <!-- 2. Instalasi -->
    <section id="instalasi" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">2. Instalasi </h4>
        <div class="flex gap-4 mb-4">
            <img src="<?= BASE_URL; ?>/assets/img/panduan-instalasi.png"
                alt="Langkah instalasi 1"
                class="w-1/2 h-auto rounded-md border" />
            <img src="<?= BASE_URL; ?>/assets/img/panduan-instalasi-2.png"
                alt="Langkah instalasi 2"
                class="w-1/2 h-auto rounded-md border" />
        </div>
        <li>Buka aplikasi atau software web server yaitu <strong>Laragon</strong>.</li>
        <li>Klik <strong>Start All</strong> untuk menjalankan local web server.</li>
        <li>Setelah tombol berubah menjadi <strong>Stop</strong>, lalu akses tautan/link pada browser <strong><a href="http://localhost/arsipsurat">http://localhost/arsipsurat</a></strong> untuk mengakses aplikasi atau dapat mengaksesnya langsung
            dengan mengklik tombol <strong> Web</strong> dan pilih <strong>arsipsurat</strong>.</li>
        </ol>
    </section>

    <!-- 3. Akses & Login -->
    <section id="akses-login" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">3. Akses & Login</h4>
        <img src="<?= BASE_URL; ?>/assets/img/panduan-login.png" width="800px" height="800px">
        <ol class="list-decimal pl-5 space-y-1 text-sm">
            <li>Buka aplikasi melalui browser yang tersedia. (http://localhost/arsipsurat)</li>
            <li>Masukkan <strong>username</strong> dan <strong>password</strong>, lalu klik <em>Login</em>.</li>
        </ol>
    </section>

    <!-- 4. Menu Utama -->
    <section id="menu-utama" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">4. Menu Utama</h4>
        <img src="<?= BASE_URL; ?>/assets/img/panduan-dashboard.png" width="800px" height="800px">
        <img src="<?= BASE_URL; ?>/assets/img/panduan-dashboard-2.png" width="800px" height="800px">
        <ul class="list-disc pl-5 space-y-1 text-sm">
            <li><strong>Dashboard:</strong> ringkasan total arsip, total kategori, total pengguna, dan beberapa grafik seperti Distribusi Arsip per Kategori dan tren pembuatan surat.</li>
            <li><strong>Arsip Digital:</strong> Tempat pengarsipan dokumen menjadi dokumen digital yang mudah diakses (Pencarian & Filter Tersedia).</li>
            <li><strong>Buat Surat:</strong> Tempat pembuatan surat keluar dengan beberapa template yang tersedia dan siap digunakan.</li>
            <li><strong>Kategori:</strong> Digunakan untuk mengkategorikan jenis dokumen arsip.</li>
        </ul>
    </section>

    <!-- 5. Manajemen Arsip -->
    <section id="arsip" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">5. Manajemen Arsip</h4>
        <img src="<?= BASE_URL; ?>/assets/img/panduan-manajemen-arsip.png" width="800px" height="800px">
        <ul class="list-disc pl-5 space-y-1 text-sm">
            <li>Buka menu <strong>Arsip Digital</strong> untuk melihat dokumen arsip.</li>
            <li>Gunakan kolom <em>cari</em> & <em>filter kategori</em> untuk mempersempit data.</li>
            <li>Unduh ulang file dokumen arsip PDF/DOCX kapan saja dari daftar arsip.</li>
        </ul>
    </section>


    <!-- 6. Membuat Surat -->
    <section id="buat-surat" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">6. Membuat Surat Baru</h4>
        <ol class="list-decimal pl-5 space-y-1 text-sm mb-4">
            <li>Buka menu <strong>Buat Surat</strong>.</li>
            <li>Pilih <strong>Template</strong> (mis. Surat Keterangan/Surat Tugas).</li>
            <li>Isi formulir sesuai kebutuhan.</li>
        </ol>

        <div class="overflow-x-auto rounded-md border">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Kolom</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Penjelasan</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Contoh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-2">Nomor Surat</td>
                        <td class="px-4 py-2">Nomor unik mengikuti pola instansi</td>
                        <td class="px-4 py-2">123/UM/IX/2025</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">Nama/Pihak</td>
                        <td class="px-4 py-2">Nama penerima/yang ditugaskan</td>
                        <td class="px-4 py-2">Ahmad Setiadi</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">Dasar Surat</td>
                        <td class="px-4 py-2">Rujukan aturan/nota dinas</td>
                        <td class="px-4 py-2">Nota Dinas No. 10/2025</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">Tanggal</td>
                        <td class="px-4 py-2">Tanggal terbit surat</td>
                        <td class="px-4 py-2">04-09-2025</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">Isi/Keperluan</td>
                        <td class="px-4 py-2">Uraian singkat maksud surat</td>
                        <td class="px-4 py-2">Penugasan monitoring kegiatan X</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 p-3 rounded-md bg-green-50 border border-green-100 text-sm">
            <span class="font-medium">Preview Dinamis:</span>
            Panel pratinjau akan berubah <em>realtime</em> saat Anda mengetik/memilih opsi di formulir.
        </div>
    </section>

    <!-- 7. Generate & Simpan -->
    <section id="generate-simpan" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">7. Generate, Simpan & Unduh</h4>
        <ol class="list-decimal pl-5 space-y-1 text-sm">
            <li>Periksa kembali pratinjau (preview).</li>
            <li>Klik <strong>Generate</strong>. Sistem akan <strong>menyimpan otomatis</strong> ke database dan menampilkannya ditabel riwayat surat keluar dibawah halaman buat surat.</li>
            <li>Pilih <strong>Unduh PDF</strong> untuk cetak/arsip lokal.</li>
        </ol>
    </section>

    <!-- 8. Notifikasi -->
    <section id="flash" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">8. Notifikasi (Flash)</h4>
        <div class="flex gap-4 mb-4">
            <img src="<?= BASE_URL; ?>/assets/img/panduan-notif-berhasil.png"
                alt="Langkah instalasi 1"
                class="w-1/2 h-auto rounded-md border" />
            <img src="<?= BASE_URL; ?>/assets/img/panduan-notif-peringatan.png"
                alt="Langkah instalasi 2"
                class="w-1/2 h-auto rounded-md border" />
        </div>
        <p class="text-sm">
            Setiap operasi yang berhasil dilakukan pada sistem, sistem akan menampilkan notifikasi hijau seperti <em>“Berhasil membuat surat.”</em>
            Jika terjadi kesalahan validasi, muncul notifikasi merah.
        </p>
    </section>
    <!-- Flasher -->
    <div class="mt-4">
        <?php Flasher::flash(); ?>
    </div>
</div>

<!-- Script kecil untuk tombol & smooth scroll TOC -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnPrint = document.getElementById('btnPrint');
        const btnKeAtas = document.getElementById('btnKeAtas');

        if (btnPrint) {
            btnPrint.addEventListener('click', function() {
                window.print();
            });
        }

        if (btnKeAtas) {
            btnKeAtas.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Smooth scroll untuk link TOC
        document.querySelectorAll('a[href^="#"]').forEach(function(a) {
            a.addEventListener('click', function(e) {
                const id = a.getAttribute('href').slice(1);
                const el = document.getElementById(id);
                if (!el) return;
                e.preventDefault();
                el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    });
</script>
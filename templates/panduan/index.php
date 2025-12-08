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
        Dokumentasi singkat ini menyusun ulang panduan agar fokus pada alur penggunaan utama: menerima surat masuk, mengarsipkannya secara digital, hingga menerbitkan surat keluar yang sudah bernomor resmi.
    </p>

    <!-- Shortcut Nav (TOC ringan) -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="#pendahuluan" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Pendahuluan</a>
        <a href="#akses-login" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Akses & Login</a>
        <a href="#alur-surat-masuk" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Alur Surat Masuk</a>
        <a href="#arsip" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Pengarsipan Digital</a>
        <a href="#alur-surat-keluar" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Alur Surat Keluar</a>
        <a href="#buat-surat" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Menyusun Surat Keluar</a>
        <a href="#generate-simpan" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Generate & Simpan</a>
        <a href="#penomoran-arsip" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Penomoran & Pengarsipan</a>
        <a href="#flash" class="px-3 py-1 rounded-full text-sm border hover:bg-gray-50">Notifikasi</a>
    </div>

    <!-- 1. Pendahuluan -->
    <section id="pendahuluan" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">1. Pendahuluan</h4>
        <div class="p-3 rounded-md bg-blue-50 border border-blue-100 text-sm space-y-2">
            <p>Aplikasi ini mendampingi proses <strong>surat masuk → tindak lanjut → arsip digital</strong> dan <strong>pembuatan surat keluar</strong> berbasis template.</p>
            <p>Setiap langkah diarahkan supaya nomor registrasi, file digital, dan riwayat tindakan tercatat rapi.</p>
        </div>
    </section>

    <!-- 2. Akses & Login -->
    <section id="akses-login" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">2. Akses & Login</h4>
        <ol class="list-decimal pl-5 space-y-1 text-sm">
            <li>Buka aplikasi melalui browser (<code>http://localhost/arsipsurat</code>).</li>
            <li>Masukkan <strong>username</strong> dan <strong>password</strong>, kemudian klik <em>Login</em>.</li>
            <li>Pastikan profil pengguna sesuai peran (admin/umpeg/unit pengolah) agar menu yang diperlukan muncul.</li>
        </ol>
    </section>

    <!-- 3. Alur Surat Masuk -->
    <section id="alur-surat-masuk" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">3. Alur Surat Masuk → Arsip</h4>
        <ol class="list-decimal pl-5 space-y-2 text-sm">
            <li><strong>Register awal:</strong> catat surat di buku kendali melalui menu <em>Surat Masuk</em> (isi nomor agenda, tanggal, pengirim, ringkasan isi).</li>
            <li><strong>Catat disposisi:</strong> tambahkan instruksi Camat/Sekcam dan tetapkan <em>unit pengolah</em> yang bertugas. Lampirkan kartu disposisi jika ada.</li>
            <li><strong>Distribusi unit:</strong> Kasubag Umum & Kepegawaian menyerahkan surat ke unit pengolah sesuai disposisi dan mengubah status tindak lanjut di aplikasi.</li>
            <li><strong>Pemindaian & unggah:</strong> unggah PDF hasil scan surat masuk serta lampiran pendukung agar jejak audit tersimpan.</li>
            <li><strong>Tandai selesai:</strong> setelah unit menyelesaikan instruksi, perbarui status penyelesaian sehingga alur surat masuk tercatat lengkap sampai arsip digital.</li>
        </ol>
        <div class="mt-3 p-3 rounded-md bg-yellow-50 border border-yellow-100 text-sm">
            <span class="font-medium">Tip:</span> sertakan kata kunci penting pada ringkasan isi untuk memudahkan pencarian arsip di kemudian hari.
        </div>
    </section>

    <!-- 4. Pengarsipan Digital -->
    <section id="arsip" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">4. Pengarsipan Digital</h4>
        <ul class="list-disc pl-5 space-y-1 text-sm">
            <li>Buka menu <strong>Arsip Digital</strong> untuk memverifikasi unggahan dari langkah surat masuk.</li>
            <li>Gunakan <em>cari</em> atau <em>filter kategori</em> untuk menemukan arsip berdasarkan klasifikasi/kata kunci.</li>
            <li>Unduh ulang PDF atau dokumen asli jika diperlukan untuk proses lanjut atau cetak.</li>
        </ul>
    </section>


    <!-- 5. Alur Surat Keluar -->
    <section id="alur-surat-keluar" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">5. Alur Surat Keluar</h4>
        <ol class="list-decimal pl-5 space-y-2 text-sm">
            <li><strong>Permintaan dari disposisi:</strong> unit pengolah menyiapkan draft surat sesuai hasil tindak lanjut surat masuk.</li>
            <li><strong>Pilih template:</strong> gunakan menu <em>Buat Surat</em> untuk memilih template (Surat Keterangan, Surat Tugas, dsb.).</li>
            <li><strong>Isi data & pratinjau:</strong> lengkapi kolom yang diminta dan cek panel pratinjau untuk memastikan format sesuai.</li>
            <li><strong>Registrasi nomor:</strong> catat nomor surat sesuai buku register surat keluar sebelum dikirim.</li>
            <li><strong>Unggah dan simpan:</strong> simpan hasil generate sebagai PDF dan pastikan tercatat di daftar riwayat surat keluar.</li>
        </ol>
    </section>


    <!-- 6. Menyusun Surat Keluar -->
    <section id="buat-surat" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">6. Menyusun Surat Keluar</h4>
        <ol class="list-decimal pl-5 space-y-1 text-sm mb-4">
            <li>Buka menu <strong>Buat Surat</strong> dan pilih <strong>Template</strong> (mis. Surat Keterangan/Surat Tugas).</li>
            <li>Isi formulir sesuai kebutuhan dan gunakan pratinjau untuk mengecek format.</li>
            <li>Simpan data agar muncul di tabel riwayat sebagai arsip digital.</li>
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

    <!-- 8. Penomoran & Pengarsipan -->
    <section id="penomoran-arsip" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">8. Penomoran & Pengarsipan</h4>
        <div class="p-3 rounded-md bg-blue-50 border border-blue-100 text-sm space-y-2">
            <p><span class="font-medium">Format nomor surat keluar</span> mengikuti pola: <code>kode_kelasifikasi/nomor_registrasi -Unit Pengolah</code>. Contoh: <strong>800.1/01 -Umpeg</strong> (kode klasifikasi &lt;=&gt; bidang/jenis surat).</p>
            <p><span class="font-medium">Kode klasifikasi</span> wajib dipakai kembali saat <strong>mengarsipkan</strong> surat agar struktur folder maupun metadata konsisten dengan ketentuan pemerintah.</p>
            <p><span class="font-medium">Lampiran pendukung</span> (mis. disposisi camat, kartu disposisi, bukti terima) sebaiknya dipindai dan diunggah bersama file utama untuk menjaga jejak audit.</p>
        </div>
    </section>

    <!-- 9. Notifikasi -->
    <section id="flash" class="mb-8">
        <h4 class="text-lg font-semibold mb-2">9. Notifikasi (Flash)</h4>
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
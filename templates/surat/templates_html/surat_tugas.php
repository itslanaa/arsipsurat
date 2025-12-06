<style>
    body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; }
    .kop-surat { border-bottom: 4px double black; padding-bottom: 5px; text-align: center; }
    .kop-surat img { width: 75px; position: absolute; left: 20mm; top: 15mm; }
    .kop-surat .judul1 { font-size: 14pt; font-weight: bold; }
    .kop-surat .judul2 { font-size: 18pt; font-weight: bold; }
    .kop-surat .alamat { font-size: 10pt; }
    .judul-surat { text-align: center; margin-top: 30px; margin-bottom: 30px; }
    .judul-surat p { margin: 0; padding: 0; }
    .isi-surat { text-align: justify; line-height: 1.8; }
    table { width: 100%; border-collapse: collapse; }
    td { vertical-align: top; padding: 2px; }
    .tanda-tangan { margin-top: 50px; }
</style>

<div class="kop-surat">
    <!-- Path ke gambar harus absolut dari root server saat generate PDF -->
    <img src="../../../public/assets/img/logo.png">
    <p class="judul1">PEMERINTAH KABUPATEN BOGOR</p>
    <p class="judul2">KECAMATAN CIBUNGBULANG</p>
    <p class="alamat">Jln. Raya Cibungbulang Km. 18 Desa Cimanggu Dua Kode Pos. 16630</p>
    <p class="alamat">Telp. (0251) 8647511 Bogor Faks. 86475111</p>
</div>

<div class="judul-surat">
    <p style="font-weight: bold; text-decoration: underline;">SURAT TUGAS</p>
    <p>Nomor: <?= htmlspecialchars($postData['noSurat']); ?></p>
</div>

<div class="isi-surat">
    <table>
        <tr>
            <td style="width: 15%;">Dasar</td>
            <td style="width: 3%;">:</td>
            <td style="width: 82%;"><?= nl2br(htmlspecialchars($postData['dasarSurat'])); ?></td>
        </tr>
    </table>

    <p style="text-align: center; font-weight: bold; margin-top: 20px; margin-bottom: 20px;">MEMERINTAHKAN:</p>

    <table>
        <tr>
            <td style="width: 15%;">Kepada</td>
            <td style="width: 3%;">:</td>
            <td style="width: 82%;">
                <table style="width: 100%;">
                    <tr><td style="width: 25%;">Nama</td><td>: <?= htmlspecialchars($postData['pegawaiNama']); ?></td></tr>
                    <tr><td>Pangkat/gol</td><td>: <?= htmlspecialchars($postData['pegawaiPangkat']); ?></td></tr>
                    <tr><td>NIP</td><td>: <?= htmlspecialchars($postData['pegawaiNip']); ?></td></tr>
                    <tr><td>Jabatan</td><td>: <?= htmlspecialchars($postData['pegawaiJabatan']); ?></td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 10px;">Untuk</td>
            <td style="padding-top: 10px;">:</td>
            <td style="padding-top: 10px;"><?= nl2br(htmlspecialchars($postData['tugasSurat'])); ?></td>
        </tr>
    </table>

    <p style="margin-top: 20px;">Demikian untuk dilaksanakan dengan penuh tanggung jawab.</p>
</div>

<div class="tanda-tangan">
    <table style="width: 100%;">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <p>Cibungbulang, <?= date('d F Y', strtotime($postData['tglSurat'])); ?></p>
                <p style="font-weight: bold;"><?= htmlspecialchars($pejabat['jabatan']); ?>,</p>
                <div style="height: 80px;"></div>
                <p style="font-weight: bold; text-decoration: underline;"><?= htmlspecialchars($pejabat['nama_lengkap']); ?></p>
                <p><?= htmlspecialchars($pejabat['pangkat_gol']); ?></p>
                <p>NIP. <?= htmlspecialchars($pejabat['nip']); ?></p>
            </td>
        </tr>
    </table>
</div>

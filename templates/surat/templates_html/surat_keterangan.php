<style>
    body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; }
    .kop-surat { border-bottom: 4px double black; padding-bottom: 5px; text-align: center; }
    .judul-surat { text-align: center; margin-top: 30px; margin-bottom: 30px; }
    .isi-surat { text-align: justify; line-height: 1.8; }
    .tanda-tangan { margin-top: 50px; }
</style>

<div class="kop-surat">
    <!-- ... (Sama seperti surat tugas) ... -->
    <p style="font-size: 14pt; font-weight: bold;">PEMERINTAH KABUPATEN BOGOR</p>
    <p style="font-size: 18pt; font-weight: bold;">KECAMATAN CIBUNGBULANG</p>
</div>

<div class="judul-surat">
    <p style="font-weight: bold; text-decoration: underline;">SURAT KETERANGAN</p>
    <p>Nomor: <?= htmlspecialchars($postData['noSurat']); ?></p>
</div>

<div class="isi-surat">
    <p>Yang bertanda tangan di bawah ini, Camat Cibungbulang, Kabupaten Bogor, dengan ini menerangkan bahwa:</p>
    
    <table style="width: 100%; margin-left: 50px; margin-top: 20px; margin-bottom: 20px;">
        <tr><td style="width: 30%;">Nama</td><td>: <?= htmlspecialchars($postData['pegawaiNama']); ?></td></tr>
        <tr><td>Jabatan</td><td>: <?= htmlspecialchars($postData['pegawaiJabatan']); ?></td></tr>
        <tr><td>NIP</td><td>: <?= htmlspecialchars($postData['pegawaiNip']); ?></td></tr>
    </table>

    <p><?= nl2br(htmlspecialchars($postData['dasarSurat'])); ?></p>
    
    <p style="margin-top: 20px;">Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
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
                <p>NIP. <?= htmlspecialchars($pejabat['nip']); ?></p>
            </td>
        </tr>
    </table>
</div>

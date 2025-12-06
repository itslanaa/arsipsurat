<?php /* Preview Surat Keterangan */ ?>
<div class="kop-surat flex items-start mb-4">
    <table style="width:100%; border-collapse:collapse; margin-bottom:1rem;">
        <tr>
            <td style="width:90px; vertical-align:top;">
                <img src="<?= BASE_URL; ?>/assets/img/logo.png"
                    alt="Logo"
                    style="width:75px; height:90px; object-fit:contain;">
            </td>
            <td style="text-align:center; vertical-align:top;">
                <p class="font-bold text-lg" style="margin:0;">PEMERINTAH KABUPATEN BOGOR</p>
                <p class="font-bold text-2xl" style="margin:0;">KECAMATAN CIBUNGBULANG</p>
                <p class="text-sm" style="margin:0;">Jln. Raya Cibungbulang Km. 18 Desa Cimanggu Dua Kode Pos. 16630</p>
                <p class="text-sm" style="margin:0;">Telp. (0251) 8647511 Bogor Faks. 86475111</p>
                <p class="text-sm" style="margin:0;">E-mail: cibungbulang.kec@gmail.com Website: kecamatancibungbulang.bogorkab.go.id</p>
            </td> 
        </tr>
    </table>
</div>

<div class="text-center my-8">
    <p class="font-bold underline text-lg uppercase">SURAT KETERANGAN</p>
    <p>Nomor: <span id="preview-noSurat"></span></p>
</div>

<div class="mt-8 text-justify" style="line-height: 1.8;">
    <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

    <table class="w-full mb-4 ml-12">
        <tbody>
            <tr>
                <td style="width: 25%;">Nama</td>
                <td>: <span id="preview-namaPenduduk"></span></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>: <span id="preview-nikPenduduk"></span></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: <span id="preview-alamatPenduduk"></span></td>
            </tr>
        </tbody>
    </table>

    <table class="w-full mb-4">
        <tbody>
            <tr>
                <td class="align-top" style="width: 15%;">Keterangan</td>
                <td class="align-top" style="width: 5%;">:</td>
                <td class="align-top" id="preview-keteranganIsi" style="width: 80%;"></td>
            </tr>
        </tbody>
    </table>

    <p class="mt-6">Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
</div>

<div class="flex justify-end mt-16">
    <div class="text-center">
        <p id="preview-tglSurat"></p>
        <p class="font-bold"><?= htmlspecialchars($pejabat['jabatan'] ?? 'Camat'); ?>,</p>
        <div style="height: 80px;"></div>
        <p class="font-bold underline"><?= htmlspecialchars($pejabat['nama_lengkap'] ?? 'Nama Pejabat'); ?></p>
        <p><?= htmlspecialchars($pejabat['pangkat_gol'] ?? 'Pangkat'); ?></p>
        <p>NIP. <?= htmlspecialchars($pejabat['nip'] ?? 'NIP Pejabat'); ?></p>
    </div>
</div>
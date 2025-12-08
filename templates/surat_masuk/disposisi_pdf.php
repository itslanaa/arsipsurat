<?php
$asal = $surat['asal_surat'] ?? '-';
$perihal = $surat['perihal'] ?? '-';
$agenda = $surat['nomor_agenda'] ?? '-';
$tglSurat = !empty($surat['tanggal_surat']) ? date('d/m/Y', strtotime($surat['tanggal_surat'])) : '-';
$tglTerima = !empty($surat['tanggal_terima']) ? date('d/m/Y', strtotime($surat['tanggal_terima'])) : '-';
$kode = $surat['kode_klasifikasi'] ?? '-';
$namaKat = $kategori['nama_kategori'] ?? '';
$status = $surat['status'] ?? '';
$unit = $surat['unit_pengolah'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            border: 1px solid #000;
            padding: 12px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .title {
            font-weight: bold;
            font-size: 14pt;
            letter-spacing: 1px;
        }

        .sub {
            font-size: 10pt;
        }

        .row {
            display: flex;
            margin-bottom: 6px;
        }

        .label {
            width: 120px;
            font-weight: bold;
        }

        .value {
            flex: 1;
        }

        .box {
            border: 1px solid #000;
            padding: 6px;
            min-height: 70px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 6px;
            margin-bottom: 4px;
        }

        .unit-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 4px 10px;
        }

        .check {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .square {
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            text-align: center;
        }

        .signed {
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <table style="width:100%; border-collapse:collapse; margin-bottom:8px;">
                <tr>
                    <td style="width:90px; vertical-align:top;">
                        <img src="<?= BASE_URL; ?>/assets/img/logo.png"
                            alt="Logo"
                            style="width:75px; height:90px; object-fit:contain;">
                    </td>
                    <td style="text-align:center; vertical-align:top;">
                        <p style="margin:0; font-weight:bold; font-size:14pt;">PEMERINTAH KABUPATEN BOGOR</p>
                        <p style="margin:0; font-weight:bold; font-size:20pt;">DINAS ARSIP DAN PERPUSTAKAAN</p>
                        <p style="margin:0; font-size:10pt;">Jl. Bersih, Kel. Tengah - Kecamatan Cibinong Telp/Fax : (021) 87901363, 8754781</p>
                        <p style="margin:0; font-size:10pt;">CIBINONG 16814</p>
                        <p style="margin:0; font-size:10pt;">&nbsp;</p>
                        <p style="margin:0; font-weight:bold; font-size:18pt;">KARTU DISPOSISI</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="row">
            <div class="label">Tanggal Surat</div>
            <div class="value">: <?= $tglSurat; ?></div>
        </div>
        <div class="row">
            <div class="label">Tanggal Terima</div>
            <div class="value">: <?= $tglTerima; ?></div>
        </div>
        <div class="row">
            <div class="label">Nomor Agenda</div>
            <div class="value">: <?= htmlspecialchars($agenda); ?></div>
        </div>
        <div class="row">
            <div class="label">Asal Surat</div>
            <div class="value">: <?= htmlspecialchars($asal); ?></div>
        </div>
        <div class="row">
            <div class="label">Perihal</div>
            <div class="value">: <?= htmlspecialchars($perihal); ?></div>
        </div>
        <div class="row">
            <div class="label">Klasifikasi</div>
            <div class="value">: <?= htmlspecialchars($kode); ?> <?= $namaKat ? '(' . htmlspecialchars($namaKat) . ')' : ''; ?></div>
        </div>

        <div class="section-title">Instruksi Camat</div>
        <div class="box"><?= nl2br(htmlspecialchars($surat['instruksi_camat'] ?? '')); ?></div>

        <div class="section-title">Catatan Koreksi</div>
        <div class="box"><?= nl2br(htmlspecialchars($surat['catatan_koreksi'] ?? '')); ?></div>

        <div class="section-title">Instruksi / Informasi Sekcam</div>
        <div class="box"><?= nl2br(htmlspecialchars($surat['disposisi_sekcam'] ?? '')); ?></div>

        <div class="section-title">Diteruskan kepada</div>
        <div class="unit-list">
            <?php foreach (($unit_options ?? []) as $opt): ?>
                <div class="check">
                    <div class="square"><?= ($unit === $opt) ? '✓' : '&nbsp;'; ?></div>
                    <div><?= htmlspecialchars($opt); ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="signed">
            <div>Status: <strong><?= htmlspecialchars($status); ?></strong></div>
            <div style="text-align:right;">
                <div style="font-size:10pt;">Paraf Camat/Sekcam</div>
                <div style="margin-top:40px; border-top:1px solid #000; width:160px; text-align:center; margin-left:auto;">Tgl ...............</div>
            </div>
        </div>
    </div>
</body>

</html>
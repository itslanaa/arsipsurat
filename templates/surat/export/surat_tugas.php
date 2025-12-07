<?php /* EXPORT: Surat Tugas (HTML untuk PDF/DOC) */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Tugas</title>
<style>
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12pt; line-height: 1.8; }
  .text-center { text-align:center; }
  .text-justify { text-align: justify; }
  .kop-table { width:100%; border-collapse:collapse; margin-bottom: 8px; }
  .kop-logo { width:75px; height:90px; object-fit:contain; }
  .nomor { margin: 12px 0 24px 0; }
  .spacer-ttd { height: 80px; }
  .w-15 { width:15%; } .w-5 { width:5%; } .w-80 { width:80%; }
</style>
</head>
<body>

<table class="kop-table">
  <tr>
    <td style="width:90px; vertical-align:top;">
      <img src="<?= $logoUrl ?>" alt="Logo" class="kop-logo">
    </td>
    <td class="text-center" style="vertical-align:top;">
      <p style="margin:0; font-weight:bold; font-size:14pt;">PEMERINTAH KABUPATEN BOGOR</p>
      <p style="margin:0; font-weight:bold; font-size:20pt;">KECAMATAN CIBUNGBULANG</p>
      <p style="margin:0; font-size:10pt;">Jln. Raya Cibungbulang Km. 18 Desa Cimanggu Dua Kode Pos. 16630</p>
      <p style="margin:0; font-size:10pt;">Telp. (0251) 8647511 Bogor Faks. 86475111</p>
      <p style="margin:0; font-size:10pt;">E-mail: cibungbulang.kec@gmail.com Website: kecamatancibungbulang.bogorkab.go.id</p>
    </td>
  </tr>
</table>
<!-- Garis kop double rapat -->
<table width="100%" cellpadding="0" cellspacing="0" style="margin:6px 0 10px 0; border-collapse:collapse;">
  <tr>
    <td style="border-bottom:3px double #000; height:0; line-height:0; padding:0;">&nbsp;</td>
  </tr>
</table>

<div class="text-center">
  <p style="margin: 8px 0; font-weight:bold; text-decoration:underline; font-size:14pt;">SURAT TUGAS</p>
  <p class="nomor">Nomor: <?= $noSurat ?></p>
</div>

<div class="text-justify" style="line-height:1.8;">
  <table style="width:100%; margin-bottom:10px;">
    <tr>
      <td class="w-15" style="vertical-align:top;">Dasar</td>
      <td class="w-5"  style="vertical-align:top;">:</td>
      <td class="w-80" style="vertical-align:top; white-space: pre-wrap;"><?= $dasarSuratPlain ?></td>
    </tr>
  </table>

  <p class="text-center" style="font-weight:bold; margin: 8px 0;">MEMERINTAHKAN:</p>

  <table style="width:100%; margin-bottom:10px;">
    <tr>
      <td class="w-15" style="vertical-align:top;">Kepada</td>
      <td class="w-5"  style="vertical-align:top;">:</td>
      <td class="w-80" style="vertical-align:top;">
        <?php if (count($pegawaiList) <= 1): ?>
          <?php $pg = $pegawaiList[0] ?? []; ?>
          <table style="width:100%;">
            <?php if (($pg['visible_nama'] ?? true) || !isset($pg['visible_nama'])): ?><tr><td style="width:25%;">Nama</td><td>: <?= $pg['nama'] ?? '' ?></td></tr><?php endif; ?>
            <?php if (($pg['visible_pangkat'] ?? true) || !isset($pg['visible_pangkat'])): ?><tr><td>Pangkat/gol</td><td>: <?= $pg['pangkat'] ?? '' ?></td></tr><?php endif; ?>
            <?php if (($pg['visible_nip'] ?? true) || !isset($pg['visible_nip'])): ?><tr><td>NIP</td><td>: <?= $pg['nip'] ?? '' ?></td></tr><?php endif; ?>
            <?php if (($pg['visible_jabatan'] ?? true) || !isset($pg['visible_jabatan'])): ?><tr><td>Jabatan</td><td>: <?= $pg['jabatan'] ?? '' ?></td></tr><?php endif; ?>
          </table>
        <?php else: ?>
          <table style="width:100%; border-collapse:collapse;">
            <?php foreach ($pegawaiList as $idx => $pg): ?>
              <tr>
                <td style="width:22px; vertical-align:top; padding:4px 6px 8px 0; font-weight:bold;"><?= $idx + 1 ?>.</td>
                <td style="vertical-align:top; padding:4px 0 8px;">
                  <?php if (($pg['visible_nama'] ?? true) || !isset($pg['visible_nama'])): ?>
                    <div><strong><?= $pg['nama'] ?: '-' ?></strong></div>
                  <?php endif; ?>
                  <div style="padding-left:14px;">
                    <?php if (($pg['visible_pangkat'] ?? true) || !isset($pg['visible_pangkat'])): ?><div>Pangkat/Gol : <?= $pg['pangkat'] ?? '' ?></div><?php endif; ?>
                    <?php if (($pg['visible_nip'] ?? true) || !isset($pg['visible_nip'])): ?><div>NIP : <?= $pg['nip'] ?? '' ?></div><?php endif; ?>
                    <?php if (($pg['visible_jabatan'] ?? true) || !isset($pg['visible_jabatan'])): ?><div>Jabatan : <?= $pg['jabatan'] ?? '' ?></div><?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php endif; ?>
      </td>
    </tr>
    <tr>
      <td style="vertical-align:top;">Untuk</td>
      <td style="vertical-align:top;">:</td>
      <td style="vertical-align:top; white-space: pre-wrap;">
        <?= nl2br($tugasSuratPlain) ?>
      </td>
    </tr>
  </table>

  <p style="margin-top:16px;">Demikian untuk dilaksanakan dengan penuh tanggung jawab.</p>
</div>

<table style="width:100%; border-collapse:collapse; margin-top:28px;">
  <tr>
    <td style="width:60%;"></td> <!-- kosongkan kiri untuk “dorong” ke kanan -->
    <td style="width:40%; text-align:center; vertical-align:top;">
      <p>Cibungbulang, <?= $tglSuratFormatted ?></p>
      <p style="font-weight:bold;"><?= htmlspecialchars($pejabat['jabatan'] ?? ''); ?>,</p>
        <!-- spacer tanda tangan: gunakan mm + nbsp supaya mPDF tidak collapse -->
      <div style="height:28mm;">&nbsp;</div>
      <p style="font-weight:bold; text-decoration:underline;"><?= htmlspecialchars($pejabat['nama_lengkap'] ?? ''); ?></p>
      <p><?= htmlspecialchars($pejabat['pangkat_gol'] ?? ''); ?></p>
      <p>NIP. <?= htmlspecialchars($pejabat['nip'] ?? ''); ?></p>
    </td>
  </tr>
</table>


</body>
</html>

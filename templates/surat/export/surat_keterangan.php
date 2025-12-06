<?php /* EXPORT: Surat Keterangan (HTML untuk PDF/DOC) */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Keterangan</title>
<style>
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12pt; }
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
  <p style="margin: 8px 0; font-weight:bold; text-decoration:underline; font-size:14pt;">SURAT KETERANGAN</p>
  <p class="nomor">Nomor: <?= $noSurat ?></p>
</div>

<div class="text-justify" style="line-height:1.8;">
  <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

  <table style="width:100%; margin-bottom:10px; margin-left:12px;">
    <tr><td style="width:25%;">Nama</td><td>: <?= $namaPenduduk ?></td></tr>
    <tr><td>NIK</td><td>: <?= $nikPenduduk ?></td></tr>
    <tr><td>Alamat</td><td>: <?= $alamatPenduduk ?></td></tr>
  </table>

  <table style="width:100%; margin-bottom:10px;">
    <tr>
      <td class="w-15" style="vertical-align:top;">Keterangan</td>
      <td class="w-5"  style="vertical-align:top;">:</td>
      <td class="w-80" style="vertical-align:top;"><?= $keteranganIsiHtml ?></td>
    </tr>
  </table>

  <p style="margin-top:16px;">Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
</div>

<table style="width:100%; border-collapse:collapse; margin-top:28px;">
  <tr>
    <td style="width:60%;"></td> <!-- kosongkan kiri untuk “dorong” ke kanan -->
    <td style="width:40%; text-align:center; vertical-align:top;">
      <p><?= $tglSuratFormatted ?></p>
      <p style="font-weight:bold;"><?= htmlspecialchars($pejabat['jabatan'] ?? ''); ?>,</p>
        <!-- spacer tanda tangan: gunakan mm + nbsp supaya mPDF tidak collapse -->
      <div style="height:22mm;">&nbsp;</div>
      <div style="height:22mm;">&nbsp;</div>
      <div style="height:22mm;">&nbsp;</div>
      <div style="height:22mm;">&nbsp;</div>
      <div style="height:22mm;">&nbsp;</div>
      <p style="font-weight:bold; text-decoration:underline;"><?= htmlspecialchars($pejabat['nama_lengkap'] ?? ''); ?></p>
      <p><?= htmlspecialchars($pejabat['pangkat_gol'] ?? ''); ?></p>
      <p>NIP. <?= htmlspecialchars($pejabat['nip'] ?? ''); ?></p>
    </td>
  </tr>
</table>


</body>
</html>

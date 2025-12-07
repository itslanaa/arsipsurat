<?php /* Preview Surat Tugas */ ?>
<div class="preview-surat" style="font-family:'DejaVu Sans', Arial, sans-serif; font-size:12pt; line-height:1.8;">
  <table style="width:100%; border-collapse:collapse; margin-bottom:8px;">
    <tr>
      <td style="width:90px; vertical-align:top;">
        <img src="<?= BASE_URL; ?>/assets/img/logo.png"
             alt="Logo"
             style="width:75px; height:90px; object-fit:contain;">
      </td>
      <td style="text-align:center; vertical-align:top;">
        <p style="margin:0; font-weight:bold; font-size:14pt;">PEMERINTAH KABUPATEN BOGOR</p>
        <p style="margin:0; font-weight:bold; font-size:20pt;">KECAMATAN CIBUNGBULANG</p>
        <p style="margin:0; font-size:10pt;">Jln. Raya Cibungbulang Km. 18 Desa Cimanggu Dua Kode Pos. 16630</p>
        <p style="margin:0; font-size:10pt;">Telp. (0251) 8647511 Bogor Faks. 86475111</p>
        <p style="margin:0; font-size:10pt;">E-mail: cibungbulang.kec@gmail.com Website: kecamatancibungbulang.bogorkab.go.id</p>
      </td>
    </tr>
  </table>
  <table width="100%" cellpadding="0" cellspacing="0" style="margin:6px 0 10px 0; border-collapse:collapse;">
    <tr>
      <td style="border-bottom:3px double #000; height:0; line-height:0; padding:0;">&nbsp;</td>
    </tr>
  </table>

  <div class="text-center" style="margin: 18px 0 16px;">
    <p style="margin:0; font-weight:bold; text-decoration:underline; font-size:14pt;">SURAT TUGAS</p>
    <p style="margin:8px 0 0;">Nomor: <span id="preview-noSurat"></span></p>
  </div>

  <div class="text-justify">
    <table style="width:100%; margin-bottom:10px; border-collapse:collapse;">
      <tr>
        <td style="width: 15%; vertical-align:top;">Dasar</td>
        <td style="width: 5%; vertical-align:top;">:</td>
        <td style="width: 80%; vertical-align:top; white-space: pre-wrap;" id="preview-dasar"></td>
      </tr>
    </table>

    <p class="text-center" style="font-weight:bold; margin: 8px 0;">MEMERINTAHKAN:</p>

    <table style="width:100%; margin-bottom:10px; border-collapse:collapse;">
      <tr>
        <td style="width: 15%; vertical-align:top;">Kepada</td>
        <td style="width: 5%; vertical-align:top;">:</td>
        <td style="width: 80%; vertical-align:top;">
          <div id="preview-pegawai-single">
            <table style="width:100%; border-collapse:collapse;">
              <tr id="preview-row-nama"><td style="width: 25%;">Nama</td><td>: <span id="preview-pegawaiNama"></span></td></tr>
              <tr id="preview-row-pangkat"><td>Pangkat/gol</td><td>: <span id="preview-pegawaiPangkat"></span></td></tr>
              <tr id="preview-row-nip"><td>NIP</td><td>: <span id="preview-pegawaiNip"></span></td></tr>
              <tr id="preview-row-jabatan"><td>Jabatan</td><td>: <span id="preview-pegawaiJabatan"></span></td></tr>
            </table>
          </div>
          <div id="preview-pegawai-multi" class="hidden">
            <ol id="preview-pegawai-list" style="margin:0; padding-left:18px;"></ol>
          </div>
        </td>
      </tr>
      <tr>
        <td style="vertical-align:top;">Untuk</td>
        <td style="vertical-align:top;">:</td>
        <td style="vertical-align:top; white-space: pre-wrap;" id="preview-tugas"></td>
      </tr>
    </table>

    <p style="margin-top:16px;">Demikian untuk dilaksanakan dengan penuh tanggung jawab.</p>
  </div>

  <table style="width:100%; border-collapse:collapse; margin-top:28px;">
    <tr>
      <td style="width:60%;"></td>
      <td style="width:40%; text-align:center; vertical-align:top;">
        <p id="preview-tglSurat"></p>
        <p style="font-weight:bold;"><?= htmlspecialchars($pejabat['jabatan'] ?? 'Camat'); ?>,</p>
        <div style="height:22mm;"></div>
        <div style="height:22mm;"></div>
        <div style="height:22mm;"></div>
        <div style="height:22mm;"></div>
        <div style="height:22mm;"></div>
        <p style="font-weight:bold; text-decoration:underline; margin-bottom:0;"><?= htmlspecialchars($pejabat['nama_lengkap'] ?? 'Nama Pejabat'); ?></p>
        <p style="margin:0;"><?= htmlspecialchars($pejabat['pangkat_gol'] ?? 'Pangkat'); ?></p>
        <p style="margin:0;">NIP. <?= htmlspecialchars($pejabat['nip'] ?? 'NIP Pejabat'); ?></p>
      </td>
    </tr>
  </table>
</div>

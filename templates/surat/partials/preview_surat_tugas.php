<?php /* Preview Surat Tugas */ ?>
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
  <p class="font-bold underline text-lg uppercase">SURAT TUGAS</p>
  <p>Nomor: <span id="preview-noSurat"></span></p>
</div>

<div class="mt-8 text-justify" style="line-height: 1.8;">
  <table class="w-full mb-4">
    <tbody>
      <tr>
        <td class="align-top" style="width: 15%;">Dasar</td>
        <td class="align-top" style="width: 5%;">:</td>
        <td class="align-top" id="preview-dasar" style="width: 80%; white-space: pre-wrap;"></td>
      </tr>
    </tbody>
  </table>

  <p class="text-center font-bold my-4">MEMERINTAHKAN:</p>

  <table class="w-full mb-4 ml-12">
    <tbody>
      <tr>
        <td class="align-top" style="width: 15%;">Kepada</td>
        <td class="align-top" style="width: 5%;">:</td>
        <td class="align-top" style="width: 80%;">
          <div id="preview-pegawai-single">
            <table class="w-full">
              <tr id="preview-row-nama"><td style="width: 25%;">Nama</td><td>: <span id="preview-pegawaiNama"></span></td></tr>
              <tr id="preview-row-pangkat"><td>Pangkat/gol</td><td>: <span id="preview-pegawaiPangkat"></span></td></tr>
              <tr id="preview-row-nip"><td>NIP</td><td>: <span id="preview-pegawaiNip"></span></td></tr>
              <tr id="preview-row-jabatan"><td>Jabatan</td><td>: <span id="preview-pegawaiJabatan"></span></td></tr>
            </table>
          </div>
          <div id="preview-pegawai-multi" class="hidden">
            <ol class="list-decimal ml-6" id="preview-pegawai-list"></ol>
          </div>
        </td>
      </tr>
      <tr>
        <td class="align-top">Untuk</td>
        <td class="align-top">:</td>
        <td class="align-top" id="preview-tugas" style="white-space: pre-wrap;"></td>
      </tr>
    </tbody>
  </table>

  <p class="mt-6">Demikian untuk dilaksanakan dengan penuh tanggung jawab.</p>
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

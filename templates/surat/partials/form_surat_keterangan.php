<?php /* Form Surat Keterangan */ ?>
<div class="mb-4">
  <label for="noSurat" class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat</label>
  <input type="text" id="noSurat" name="noSurat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
</div>

<div class="mb-4">
  <label for="tglSurat" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
  <input type="date" id="tglSurat" name="tglSurat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
</div>

<h4 class="font-semibold mt-6 mb-2 text-gray-800">Data Yang Diterangkan</h4>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div class="mb-4">
    <label for="namaPenduduk" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
    <input type="text" id="namaPenduduk" name="namaPenduduk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
  </div>
  <div class="mb-4">
    <label for="nikPenduduk" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
    <input type="text" id="nikPenduduk" name="nikPenduduk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
  </div>
</div>

<div class="mb-4">
  <label for="alamatPenduduk" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
  <input type="text" id="alamatPenduduk" name="alamatPenduduk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
</div>

<div class="mb-6">
  <label for="keteranganIsi" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
  <textarea id="keteranganIsi" name="keteranganIsi" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Isi keterangan..."></textarea>
</div>

<!-- <button type="submit" class="w-full bg-green-600 text-white px-4 py-3 rounded-md hover:bg-green-700 transition duration-300 font-semibold">
  <i class="fas fa-file-pdf mr-2"></i>Generate & Unduh PDF
</button> -->

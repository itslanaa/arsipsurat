<?php /* Form Surat Tugas */ ?>
<div class="mb-4">
  <label for="tglSurat" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
  <input type="date" id="tglSurat" name="tglSurat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
</div>

<div class="mb-4">
  <label for="dasarSurat" class="block text-sm font-medium text-gray-700 mb-1">Dasar</label>
  <textarea id="dasarSurat" name="dasarSurat" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Masukkan dasar surat, bisa lebih dari satu baris..."></textarea>
</div>

<h4 class="font-semibold mt-6 mb-2 text-gray-800 flex items-center justify-between">
  Data Pegawai (Kepada)
  <button type="button" id="btnAddPegawai" class="text-sm bg-blue-600 text-white px-3 py-1 rounded shadow">Tambah</button>
</h4>

<div id="pegawaiRepeater" class="space-y-4">
  <div class="pegawai-item grid grid-cols-1 md:grid-cols-2 gap-4 border rounded-md p-4 relative">
    <button type="button" class="btn-remove-pegawai absolute -right-3 -top-3 bg-red-500 text-white w-8 h-8 rounded-full shadow hidden">
      &times;
    </button>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
      <input type="text" data-field="nama" name="pegawai[nama][]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Pangkat/Gol</label>
      <input type="text" data-field="pangkat" name="pegawai[pangkat][]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
      <input type="text" data-field="nip" name="pegawai[nip][]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
      <input type="text" data-field="jabatan" name="pegawai[jabatan][]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
  </div>
</div>

<template id="pegawai-template">
  <div class="pegawai-item grid grid-cols-1 md:grid-cols-2 gap-4 border rounded-md p-4 relative">
    <button type="button" class="btn-remove-pegawai absolute -right-3 -top-3 bg-red-500 text-white w-8 h-8 rounded-full shadow">
      &times;
    </button>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
      <input type="text" data-field="nama" name="pegawai[nama][]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Pangkat/Gol</label>
      <input type="text" data-field="pangkat" name="pegawai[pangkat][]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
      <input type="text" data-field="nip" name="pegawai[nip][]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
      <input type="text" data-field="jabatan" name="pegawai[jabatan][]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
  </div>
</template>

<div class="mb-6">
  <label for="tugasSurat" class="block text-sm font-medium text-gray-700 mb-1">Untuk (Detail Tugas)</label>
  <textarea id="tugasSurat" name="tugasSurat" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Masukkan detail tugas, bisa lebih dari satu baris..."></textarea>
</div>

<!-- Tombol export
<div class="mt-4 flex items-center gap-3">
  <button type="button" class="btn-export bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md" data-scope="pdf">
    Generate & Unduh PDF
  </button>
  <button type="button" class="btn-export bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md" data-scope="doc">
    Generate & Unduh DOC
  </button>
</div>

hidden scope (boleh ada di partial, karena form-container selalu di-reload) -->
<!-- <input type="hidden" name="export_scope" id="export_scope" value="pdf"> -->

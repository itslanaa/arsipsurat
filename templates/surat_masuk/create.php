<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-xl font-semibold">Input Surat Masuk</h3>
            <p class="text-sm text-gray-500">Catat register, kartu disposisi, dan lampiran yang diterima dari petugas loket.</p>
        </div>
        <a href="<?= BASE_URL; ?>/suratmasuk" class="text-blue-600 hover:underline text-sm">Lihat daftar</a>
    </div>

    <form action="<?= BASE_URL; ?>/suratmasuk/store" method="post" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nomor Agenda / Register</label>
            <input type="text" name="nomor_agenda" required class="mt-1 w-full border rounded-md p-2" placeholder="01/REG/SM/2025">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Surat</label>
            <input type="date" name="tanggal_surat" class="mt-1 w-full border rounded-md p-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Terima</label>
            <input type="date" name="tanggal_terima" required class="mt-1 w-full border rounded-md p-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Asal Surat</label>
            <input type="text" name="asal_surat" required class="mt-1 w-full border rounded-md p-2" placeholder="Instansi pengirim">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Perihal</label>
            <input type="text" name="perihal" required class="mt-1 w-full border rounded-md p-2" placeholder="Isi pokok surat">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Kode Klasifikasi</label>
            <input type="text" name="kode_klasifikasi" list="kodeKlasifikasiList" required class="mt-1 w-full border rounded-md p-2" placeholder="800.1 / 900 dst">
            <datalist id="kodeKlasifikasiList">
                <?php foreach (($kategori_arsip ?? []) as $kat): ?>
                    <option value="<?= htmlspecialchars($kat['kode']); ?>" label="<?= htmlspecialchars($kat['nama_kategori']); ?>"></option>
                <?php endforeach; ?>
            </datalist>
            <p class="text-xs text-gray-500 mt-1">Pilih kode yang tersedia atau ketik manual untuk menambahkan klasifikasi baru.</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Unit Pengolah (opsional)</label>
            <select name="unit_pengolah" class="mt-1 w-full border rounded-md p-2">
                <option value="">-- Pilih Unit --</option>
                <?php foreach (($unit_options ?? []) as $u): ?>
                    <option value="<?= htmlspecialchars($u); ?>"><?= htmlspecialchars($u); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Ringkasan & Lampiran</label>
            <textarea name="ringkasan" rows="3" class="mt-1 w-full border rounded-md p-2" placeholder="Catat ringkas isi surat atau lampiran yang diserahkan."></textarea>
        </div>
        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Instruksi Camat (jika sudah ada)</label>
                <textarea name="instruksi_camat" rows="3" class="mt-1 w-full border rounded-md p-2" placeholder="Isi kartu disposisi Camat"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Disposisi Sekcam</label>
                <textarea name="disposisi_sekcam" rows="3" class="mt-1 w-full border rounded-md p-2" placeholder="Unit yang dituju, tindak lanjut, tenggat"></textarea>
            </div>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Lampiran (scan surat/kartu disposisi/lampiran lain)</label>
            <input type="file" name="lampiran[]" multiple class="mt-1 w-full border rounded-md p-2 bg-white" accept=".pdf,.docx,.jpg,.jpeg,.png">
            <p class="text-xs text-gray-500 mt-1">Lampirkan kartu disposisi, surat masuk, atau bukti terima untuk jejak audit.</p>
        </div>

        <div class="md:col-span-2 flex justify-end gap-3 mt-2">
            <a href="<?= BASE_URL; ?>/suratmasuk" class="px-4 py-2 rounded-md border">Batal</a>
            <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white">Simpan</button>
        </div>
    </form>
</div>

<?php Flasher::flash(); ?>

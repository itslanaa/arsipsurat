<?php
$statusLabels = [
    'diterima' => 'Diterima/Registrasi',
    'instruksi_camat' => 'Disposisi Camat',
    'sekcam' => 'Disposisi Sekcam',
    'distribusi_umpeg' => 'Distribusi Umpeg',
    'diproses_unit' => 'Diproses Unit',
    'selesai' => 'Selesai'
];
$statusClasses = [
    'diterima' => 'bg-gray-100 text-gray-700',
    'instruksi_camat' => 'bg-blue-100 text-blue-700',
    'sekcam' => 'bg-amber-100 text-amber-700',
    'distribusi_umpeg' => 'bg-purple-100 text-purple-700',
    'diproses_unit' => 'bg-green-100 text-green-700',
    'selesai' => 'bg-emerald-100 text-emerald-700'
];
?>
<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <div class="flex items-start justify-between gap-4 mb-4">
        <div>
            <h3 class="text-xl font-semibold">Rekap Surat Masuk</h3>
            <p class="text-sm text-gray-500">Pantau alur register → disposisi camat → sekcam → distribusi Umpeg → unit pengolah.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= BASE_URL; ?>/suratmasuk/create" class="px-4 py-2 bg-blue-600 text-white rounded-md">Tambah Surat Masuk</a>
        </div>
    </div>

    <datalist id="kodeKlasifikasiList">
        <?php foreach (($kategori_arsip ?? []) as $kat): ?>
            <option value="<?= htmlspecialchars($kat['kode']); ?>" label="<?= htmlspecialchars($kat['nama_kategori']); ?>"></option>
        <?php endforeach; ?>
    </datalist>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="p-4 rounded-lg border">
            <p class="text-sm text-gray-500">Total surat masuk</p>
            <p class="text-2xl font-semibold"><?= $total_surat_masuk ?? 0; ?></p>
        </div>
        <?php foreach ($statusLabels as $key => $label): ?>
            <div class="p-4 rounded-lg border">
                <p class="text-sm text-gray-500"><?= $label; ?></p>
                <p class="text-xl font-semibold"><?= $status_count[$key] ?? 0; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="py-2 pr-4 text-left">No Agenda</th>
                    <th class="py-2 pr-4 text-left">Tanggal</th>
                    <th class="py-2 pr-4 text-left">Asal & Perihal</th>
                    <th class="py-2 pr-4 text-left">Kode</th>
                    <th class="py-2 pr-4 text-left">Status</th>
                    <th class="py-2 pr-4 text-left">Disposisi</th>
                    <th class="py-2 pr-4 text-left">Lampiran</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftar_surat)): ?>
                    <tr>
                        <td colspan="7" class="py-4 text-center text-gray-500">Belum ada surat masuk.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($daftar_surat as $item): ?>
                    <tr class="border-b align-top">
                        <td class="py-2 pr-4">
                            <div class="font-semibold"><?= htmlspecialchars($item['nomor_agenda']); ?></div>
                            <div class="text-xs text-gray-500"><?= htmlspecialchars($item['asal_surat']); ?></div>
                        </td>
                        <td class="py-2 pr-4 text-sm">
                            <?= date('d/m/Y', strtotime($item['tanggal_terima'])); ?>
                        </td>
                        <td class="py-2 pr-4">
                            <div class="font-medium text-gray-800"><?= htmlspecialchars($item['perihal']); ?></div>
                            <div class="text-xs text-gray-500 line-clamp-2"><?= nl2br(htmlspecialchars($item['ringkasan'] ?? '')); ?></div>
                        </td>
                        <td class="py-2 pr-4">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-slate-100 text-slate-700"><?= htmlspecialchars($item['kode_klasifikasi']); ?></span>
                            <?php if (!empty($item['unit_pengolah'])): ?>
                                <div class="text-xs text-gray-500 mt-1">Unit: <?= htmlspecialchars($item['unit_pengolah']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 pr-4">
                            <?php $s = $item['status']; ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?= $statusClasses[$s] ?? 'bg-gray-100 text-gray-700'; ?>">
                                <?= $statusLabels[$s] ?? $s; ?>
                            </span>
                            <?php if ($s === 'selesai'): ?>
                                <div class="mt-2">
                                    <a href="<?= BASE_URL; ?>/suratmasuk/arsipkan/<?= (int)$item['id']; ?>" class="text-xs text-emerald-700 font-semibold hover:underline">Arsipkan</a>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 pr-4">
                            <div class="text-xs text-gray-600 mb-2">
                                <div class="font-semibold">Instruksi Camat:</div>
                                <div class="whitespace-pre-line"><?= htmlspecialchars($item['instruksi_camat'] ?? '-'); ?></div>
                            </div>
                            <div class="text-xs text-gray-600 mb-2">
                                <div class="font-semibold">Disposisi Sekcam:</div>
                                <div class="whitespace-pre-line"><?= htmlspecialchars($item['disposisi_sekcam'] ?? '-'); ?></div>
                            </div>
                            <details class="text-xs">
                                <summary class="cursor-pointer text-blue-600">Perbarui</summary>
                                <form action="<?= BASE_URL; ?>/suratmasuk/update/<?= (int)$item['id']; ?>" method="post" enctype="multipart/form-data" class="mt-2 space-y-2">
                                    <textarea name="instruksi_camat" rows="2" class="w-full border rounded-md p-2" placeholder="Instruksi Camat"><?= htmlspecialchars($item['instruksi_camat'] ?? ''); ?></textarea>
                                    <textarea name="disposisi_sekcam" rows="2" class="w-full border rounded-md p-2" placeholder="Disposisi Sekcam"><?= htmlspecialchars($item['disposisi_sekcam'] ?? ''); ?></textarea>
                                    <input type="text" name="unit_pengolah" value="<?= htmlspecialchars($item['unit_pengolah'] ?? ''); ?>" class="w-full border rounded-md p-2" placeholder="Unit pengolah">
                                    <input type="text" name="kode_klasifikasi" list="kodeKlasifikasiList" value="<?= htmlspecialchars($item['kode_klasifikasi'] ?? ''); ?>" class="w-full border rounded-md p-2" placeholder="Kode klasifikasi">
                                    <select name="status" class="w-full border rounded-md p-2">
                                        <?php foreach ($statusLabels as $k => $label): ?>
                                            <option value="<?= $k; ?>" <?= ($item['status'] === $k) ? 'selected' : ''; ?>><?= $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label class="block text-xs text-gray-500">Tambah lampiran baru (opsional)</label>
                                    <input type="file" name="lampiran[]" multiple class="w-full border rounded-md p-2 bg-white" accept=".pdf,.docx,.jpg,.jpeg,.png">
                                    <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-md text-xs">Simpan</button>
                                </form>
                            </details>
                        </td>
                        <td class="py-2 pr-4 text-xs">
                            <?php if (!empty($item['files'])): ?>
                                <ul class="list-disc pl-4 space-y-1">
                                    <?php foreach ($item['files'] as $f): ?>
                                        <li>
                                            <a class="text-blue-600 hover:underline" href="<?= BASE_URL . '/' . ltrim($f['path_file'], '/'); ?>" target="_blank">
                                                <?= htmlspecialchars($f['nama_file_asli']); ?>
                                            </a>
                                            <span class="text-gray-400">(<?= htmlspecialchars($f['jenis_lampiran'] ?? 'lampiran'); ?>)</span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php Flasher::flash(); ?>

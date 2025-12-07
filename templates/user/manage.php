<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-xl font-semibold">Tambah Pengguna</h3>
            <p class="text-sm text-gray-500">Isi data login baru dengan password terkonfirmasi.</p>
        </div>
    </div>
    <form action="<?= BASE_URL; ?>/user/store" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" required class="mt-1 w-full border rounded-md p-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" required class="mt-1 w-full border rounded-md p-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <select name="role" class="mt-1 w-full border rounded-md p-2">
                <option value="staf">Staf</option>
                <option value="admin">Admin</option>
                <option value="camat">Camat</option>
                <option value="sekcam">Sekcam</option>
                <option value="unit">Unit Pengolah</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Password Baru</label>
            <input type="password" name="password_baru" required class="mt-1 w-full border rounded-md p-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input type="password" name="konfirmasi_password" required class="mt-1 w-full border rounded-md p-2">
        </div>
        <div class="md:col-span-2 flex justify-end mt-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan</button>
        </div>
    </form>
</div>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-semibold mb-4">Daftar Pengguna</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2 pr-4">Nama</th>
                    <th class="text-left py-2 pr-4">Username</th>
                    <th class="text-left py-2 pr-4">Role</th>
                    <th class="text-left py-2 pr-4">Edit</th>
                    <th class="text-left py-2 pr-4">Password</th>
                    <th class="text-left py-2 pr-4">Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($users ?? []) as $u): ?>
                    <tr class="border-b align-top">
                        <td class="py-2 pr-4 font-semibold text-gray-800"><?= htmlspecialchars($u['nama_lengkap']); ?></td>
                        <td class="py-2 pr-4 text-gray-700"><?= htmlspecialchars($u['username']); ?></td>
                        <td class="py-2 pr-4 text-gray-700 capitalize"><?= htmlspecialchars($u['role']); ?></td>
                        <td class="py-2 pr-4">
                            <form id="edit-<?= (int)$u['id']; ?>" action="<?= BASE_URL; ?>/user/update" method="post" class="space-y-2 text-xs">
                                <input type="hidden" name="id" value="<?= (int)$u['id']; ?>">
                                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($u['nama_lengkap']); ?>" class="w-full border rounded-md p-2" required>
                                <input type="text" name="username" value="<?= htmlspecialchars($u['username']); ?>" class="w-full border rounded-md p-2" required>
                                <select name="role" class="w-full border rounded-md p-2">
                                    <option value="staf" <?= $u['role'] === 'staf' ? 'selected' : ''; ?>>Staf</option>
                                    <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="camat" <?= $u['role'] === 'camat' ? 'selected' : ''; ?>>Camat</option>
                                    <option value="sekcam" <?= $u['role'] === 'sekcam' ? 'selected' : ''; ?>>Sekcam</option>
                                    <option value="unit" <?= $u['role'] === 'unit' ? 'selected' : ''; ?>>Unit Pengolah</option>
                                </select>
                                <button type="button" class="px-3 py-2 bg-blue-600 text-white rounded-md" onclick="showConfirmation('Simpan Perubahan', 'Perbarui data pengguna ini?', 'javascript:document.getElementById(\'edit-<?= (int)$u['id']; ?>\').submit();', 'Ya, Simpan', 'info');">Update</button>
                            </form>
                        </td>
                        <td class="py-2 pr-4">
                            <form id="pwd-<?= (int)$u['id']; ?>" action="<?= BASE_URL; ?>/user/updatePasswordUser" method="post" class="space-y-2 text-xs">
                                <input type="hidden" name="id" value="<?= (int)$u['id']; ?>">
                                <input type="password" name="password_baru" placeholder="Password baru" class="w-full border rounded-md p-2" required>
                                <input type="password" name="konfirmasi_password" placeholder="Konfirmasi password" class="w-full border rounded-md p-2" required>
                                <button type="button" class="px-3 py-2 bg-amber-500 text-white rounded-md" onclick="showConfirmation('Ubah Password', 'Ganti password pengguna ini?', 'javascript:document.getElementById(\'pwd-<?= (int)$u['id']; ?>\').submit();', 'Ya, Ganti', 'info');">Ganti</button>
                            </form>
                        </td>
                        <td class="py-2 pr-4">
                            <button type="button" class="px-3 py-2 bg-red-600 text-white rounded-md"
                                    onclick="showConfirmation('Hapus Pengguna', 'Yakin ingin menghapus <?= htmlspecialchars(addslashes($u['nama_lengkap'])); ?>?', '<?= BASE_URL; ?>/user/destroy/<?= (int)$u['id']; ?>')">
                                Hapus
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php Flasher::flash(); ?>

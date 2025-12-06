<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Form Ubah Password -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-6 border-b pb-4">Ubah Password</h3>
        <form action="<?= BASE_URL; ?>/user/updatePassword" method="post">
            <div class="mb-4">
                <label for="password_lama" class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                <input type="password" name="password_lama" id="password_lama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="password_baru" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password_baru" id="password_baru" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div class="mb-6">
                <label for="konfirmasi_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <!-- Info Sesi Aktif -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-6 border-b pb-4">Sesi Aktif</h3>
        <div class="space-y-4 text-sm">
            <div class="flex justify-between">
                <span class="font-medium text-gray-600">Alamat IP Anda:</span>
                <span class="text-gray-800"><?= htmlspecialchars($_SERVER['REMOTE_ADDR']); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-600">Waktu Login:</span>
                <span class="text-gray-800"><?= date('d F Y, H:i:s'); ?></span>
            </div>
            <div class="mt-4">
                <p class="font-medium text-gray-600 mb-1">Perangkat/Browser:</p>
                <p class="text-gray-800 bg-gray-50 p-2 rounded-md text-xs"><?= htmlspecialchars($_SERVER['HTTP_USER_AGENT']); ?></p>
            </div>
        </div>
    </div>
</div>
<?php Flasher::flash(); ?>

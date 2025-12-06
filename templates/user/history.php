<div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-semibold mb-6 border-b pb-4">20 Aktivitas Login Terakhir</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Login</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat IP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($history as $log) : ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?= date('d F Y, H:i:s', strtotime($log['login_time'])); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['ip_address']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <?php if ($log['status'] == 'success') : ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Berhasil</span>
                            <?php else : ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Gagal</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

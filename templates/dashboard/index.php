<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
            <i class="fas fa-folder-open text-2xl"></i>
        </div>
        <div class="ml-4">
            <p class="text-gray-500">Total Dokumen Arsip</p>
            <p class="text-2xl font-bold"><?= $total_arsip; ?></p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
        <div class="p-3 rounded-full bg-gray-100 text-gray-600">
            <i class="fas fa-file-alt text-2xl"></i>
        </div>
        <div class="ml-4">
            <p class="text-gray-500">Total Surat Dibuat</p>
            <p class="text-2xl font-bold"><?= number_format($total_surat ?? 0) ?></p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
            <i class="fas fa-inbox text-2xl"></i>
        </div>
        <div class="ml-4">
            <p class="text-gray-500">Surat Masuk Tercatat</p>
            <p class="text-2xl font-bold"><?= number_format($total_surat_masuk ?? 0); ?></p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
            <i class="fas fa-tags text-2xl"></i>
        </div>
        <div class="ml-4">
            <p class="text-gray-500">Total Kategori</p>
            <p class="text-2xl font-bold"><?= $total_kategori; ?></p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
        <div class="p-3 rounded-full bg-red-100 text-red-600">
            <i class="fas fa-user-friends text-2xl"></i>
        </div>
        <div class="ml-4">
            <p class="text-gray-500">Total Pengguna</p>
            <p class="text-2xl font-bold"><?= $total_pengguna; ?></p>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
<div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-lg font-semibold mb-4">Distribusi Arsip per Kategori</h3>
    <p class="text-sm text-gray-600 mb-4">Grafik ini menunjukkan proporsi jumlah dokumen yang tersimpan dalam setiap kategori.</p>
    <div class="chart-container" style="height: 330px; max-width: 430px; margin: left;">
        <canvas id="kategoriChart"></canvas>
    </div>
</div>
 <!-- Tren Surat 12 Bulan -->
  <div class="bg-white rounded-xl shadow p-6">
    <h3 class="text-lg font-semibold mb-2">Tren Surat 12 Bulan</h3>
    <p class="text-sm text-gray-500 mb-4">
      Grafik ini menampilkan jumlah surat keluar yang dibuat per bulan selama 12 bulan terakhir.
      Gunakan untuk melihat pola kenaikan/penurunan.
    </p>
    <canvas id="chartSuratTrend" class="w-full" height="180"></canvas>
  </div>
</div>

<!-- Grid grafik tambahan -->
<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="bg-white rounded-xl shadow p-6">
    <h3 class="text-lg font-semibold mb-2">Status Surat Masuk</h3>
    <p class="text-sm text-gray-500 mb-3">Rekap alur registrasi, disposisi Camat/Sekcam, hingga distribusi ke unit pengolah.</p>
    <?php
      $statusMap = [
        'diterima' => 'Diterima/Registrasi',
        'instruksi_camat' => 'Disposisi Camat',
        'sekcam' => 'Disposisi Sekcam',
        'distribusi_umpeg' => 'Distribusi Umpeg',
        'diproses_unit' => 'Diproses Unit',
        'selesai' => 'Selesai'
      ];
    ?>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="border-b text-gray-500">
            <th class="py-2 text-left">Status</th>
            <th class="py-2 text-left">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($surat_masuk_status)): ?>
            <?php foreach ($surat_masuk_status as $row): ?>
              <tr class="border-b last:border-0">
                <td class="py-2"><?= htmlspecialchars($statusMap[$row['status']] ?? $row['status']); ?></td>
                <td class="py-2 font-semibold"><?= (int)$row['jml']; ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="2" class="py-2 text-gray-400">Belum ada data surat masuk.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Komposisi Template Surat -->
  <div class="bg-white rounded-xl shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Komposisi Template Surat</h3>
    <canvas id="chartTemplateSurat" height="160"></canvas>
  </div>

</div>


<!-- Script khusus untuk halaman dashboard -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('kategoriChart')) {
        // === PERBAIKAN DI SINI: Mengganti 'd' menjadi '2d' ===
        const ctx = document.getElementById('kategoriChart').getContext('2d');
        const kategoriChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?= $chart_labels; ?>,
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: <?= $chart_values; ?>,
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444',
                        '#6366f1', '#8b5cf6', '#ec4899', '#6b7280'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 14 } }
                    },
                    tooltip: {
                        padding: 10,
                        titleFont: { size: 16 },
                        bodyFont: { size: 14 }
                    }
                }
            }
        });
    }
});
</script>
<script>
  // Data dari PHP
  const trendLabels = <?= json_encode($surat_trend_labels ?? []) ?>;
  const trendData   = <?= json_encode($surat_trend_data   ?? []) ?>;

  const tplLabels   = <?= json_encode($tpl_labels ?? []) ?>;
  const tplData     = <?= json_encode($tpl_data   ?? []) ?>;

  // Tren Surat (Line)
  const ctxTrend = document.getElementById('chartSuratTrend').getContext('2d');
  new Chart(ctxTrend, {
    type: 'line',
    data: {
      labels: trendLabels,
      datasets: [{
        label: 'Jumlah Surat',
        data: trendData,
        borderWidth: 2,
        tension: 0.3,
        fill: false,
        pointRadius: 3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { precision: 0 } }
      }
    }
  });

  // Donut Template Surat
  const ctxTpl = document.getElementById('chartTemplateSurat').getContext('2d');
  new Chart(ctxTpl, {
    type: 'doughnut',
    data: {
      labels: tplLabels,
      datasets: [{
        data: tplData
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } },
      cutout: '60%'
    }
  });
</script>

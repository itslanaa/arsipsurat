<?php

class Dashboard extends Controller
{
    public function __construct()
    {
        // Pastikan hanya user yang sudah login yang bisa akses
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }

        if (in_array(currentRole(), ['camat', 'sekcam', 'unit'], true)) {
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }
    }

    public function index()
    {
        // Data yang akan dikirim ke view
        $data['judul'] = 'Dashboard';

        // Memuat model dashboard
        $dashboardModel = $this->model('Dashboard_model');

        // Mengambil data statistik dari model
        $data['total_arsip'] = $dashboardModel->getTotalArsip();
        $data['total_kategori'] = $dashboardModel->getTotalKategori();
        $data['total_pengguna'] = $dashboardModel->getTotalPengguna();
        $data['total_surat']   = $dashboardModel->getTotalSurat();      // <-- baru
        $data['total_surat_masuk'] = $dashboardModel->getTotalSuratMasuk();

        // Mengambil dan memformat data untuk chart
        $chartData = $dashboardModel->getArsipDistribution();
        // Mengambil kolom 'nama_kategori' untuk label chart
        $data['chart_labels'] = json_encode(array_column($chartData, 'nama_kategori'));
        // Mengambil kolom 'jumlah' untuk nilai chart
        $data['chart_values'] = json_encode(array_column($chartData, 'jumlah'));

        // ----- Tren 12 bulan terakhir -----
        $start = new DateTime('first day of -11 months');   // 12 bulan ke belakang
        $startYmd = $start->format('Y-m-01');

        // label kunci (Y-m) & label tampil (M Y)
        $keys   = [];
        $labels = [];
        $cursor = clone $start;
        for ($i = 0; $i < 12; $i++) {
            $keys[]   = $cursor->format('Y-m');
            $labels[] = $cursor->format('M Y');
            $cursor->modify('+1 month');
        }

        $rows = $dashboardModel->getSuratPerBulan($startYmd);
        $map  = array_fill_keys($keys, 0);
        foreach ($rows as $r) {
            $ym = $r['ym'];
            $map[$ym] = (int)$r['jml'];
        }
        $data['surat_trend_labels'] = $labels;
        $data['surat_trend_data']   = array_values($map);

        // ----- Donut komposisi template -----
        $byTpl = $dashboardModel->getSuratByTemplate();
        $data['tpl_labels'] = array_map(fn($r) => $r['nama_template'], $byTpl);
        $data['tpl_data']   = array_map(fn($r) => (int)$r['jml'], $byTpl);

        // ----- Status surat masuk -----
        $data['surat_masuk_status'] = $dashboardModel->getSuratMasukByStatus();

        // Memanggil view dan mengirimkan semua data
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('dashboard/index', $data);
        $this->view('layouts/footer');
    }
}

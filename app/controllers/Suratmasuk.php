<?php

class Suratmasuk extends Controller
{
    private array $unitOptions = ['Umpeg', 'Pemerintahan', 'Pembangunan', 'Trantib', 'Sekretariat'];

    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
    }

    public function index()
    {
        $model = $this->model('Suratmasuk_model');
        $kategoriModel = $this->model('Kategori_model');
        $data['judul'] = 'Surat Masuk & Disposisi';
        $data['daftar_surat'] = $model->getAll(100);
        $data['status_count'] = $model->countByStatus();
        $data['total_surat_masuk'] = $model->countTotal();
        $data['kategori_arsip'] = $kategoriModel->getAllKategori();
        $data['unit_options'] = $this->unitOptions;

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('surat_masuk/index', $data);
        $this->view('layouts/footer');
    }

    public function create()
    {
        ensureRole(['admin', 'staf']);
        $data['judul'] = 'Input Surat Masuk';
        $data['kategori_arsip'] = $this->model('Kategori_model')->getAllKategori();
        $data['unit_options'] = $this->unitOptions;
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('surat_masuk/create', $data);
        $this->view('layouts/footer');
    }

    public function store()
    {
        ensureRole(['admin', 'staf']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }
        $model = $this->model('Suratmasuk_model');
        $id = $model->create($_POST, $_FILES);
        if ($id) {
            Flasher::setFlash('Surat masuk', 'berhasil dicatat bersama lampiran.', 'success');
        } else {
            Flasher::setFlash('Surat masuk', 'gagal dicatat.', 'error');
        }
        header('Location: ' . BASE_URL . '/suratmasuk');
        exit;
    }

    public function update($id)
    {
        if (currentRole() === 'unit') {
            Flasher::setFlash('Disposisi', 'Unit pengolah hanya dapat menekan tombol Terima.', 'info');
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }
        $model = $this->model('Suratmasuk_model');
        $row = $model->updateDisposisi((int)$id, $_POST, $_FILES);
        if ($row >= 0) {
            Flasher::setFlash('Disposisi', 'berhasil diperbarui.', 'success');
        } else {
            Flasher::setFlash('Disposisi', 'gagal diperbarui.', 'error');
        }
        header('Location: ' . BASE_URL . '/suratmasuk');
        exit;
    }

    public function arsipkan($id)
    {
        ensureRole(['admin', 'staf']);
        $model = $this->model('Suratmasuk_model');
        $surat = $model->getById((int)$id);
        if (!$surat) {
            Flasher::setFlash('Arsip', 'Surat masuk tidak ditemukan.', 'error');
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }
        if ($surat['status'] !== 'selesai') {
            Flasher::setFlash('Arsip', 'Lengkapi disposisi hingga status selesai sebelum diarsipkan.', 'info');
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }

        $kategoriModel = $this->model('Kategori_model');
        $kategoriInfo = $this->kategoriArsipDariKode($surat['kode_klasifikasi'] ?? '', $kategoriModel);
        $idKategori = $kategoriInfo['id'];
        if (!$idKategori) {
            Flasher::setFlash('Arsip', 'Kode klasifikasi belum terdaftar sebagai kategori arsip.', 'error');
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }

        $lampiran = $model->getFilesBySurat((int)$id);

        $arsipId = $this->model('Arsip_model')->tambahArsipDariSuratMasuk($surat, $idKategori, $lampiran);
        if ($arsipId) {
            Flasher::setFlash('Arsip', 'Surat masuk dan lampirannya berhasil dipindahkan ke arsip.', 'success');
        } else {
            Flasher::setFlash('Arsip', 'Gagal mengarsipkan surat masuk.', 'error');
        }
        header('Location: ' . BASE_URL . '/arsip');
        exit;
    }

    private function kategoriArsipDariKode(string $kode, Kategori_model $kategoriModel)
    {
        if (!$kode) {
            return ['id' => 0, 'kode' => '', 'nama' => ''];
        }

        $existing = $kategoriModel->getKategoriByKode($kode);
        if ($existing) {
            return ['id' => (int)$existing['id'], 'kode' => $existing['kode'], 'nama' => $existing['nama_kategori']];
        }

        $mapNama = [
            '800.1' => 'Sumber Daya Manusia',
            '800.2' => 'Pendidikan dan Pelatihan',
            '400.14' => 'Hubungan Masyarakat',
        ];
        $nama = $mapNama[$kode] ?? $kode;
        $createdId = $kategoriModel->getOrCreateByKode($kode, $nama);

        return ['id' => $createdId, 'kode' => $kode, 'nama' => $nama];
    }

    public function terima($id)
    {
        if (currentRole() !== 'unit') {
            Flasher::setFlash('Disposisi', 'Hanya unit pengolah yang dapat menandai selesai.', 'error');
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }
        $model = $this->model('Suratmasuk_model');
        $updated = $model->terimaOlehUnit((int)$id);
        if ($updated > 0) {
            Flasher::setFlash('Surat', 'diterima dan dinyatakan selesai oleh unit.', 'success');
        } else {
            Flasher::setFlash('Surat', 'belum bisa ditandai selesai.', 'error');
        }
        header('Location: ' . BASE_URL . '/suratmasuk');
        exit;
    }

    public function cetakDisposisi($id)
    {
        if (!in_array(currentRole(), ['camat', 'sekcam', 'staf', 'admin'], true)) {
            Flasher::setFlash('Akses', 'Anda tidak diperkenankan mencetak kartu disposisi.', 'error');
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }

        $surat = $this->model('Suratmasuk_model')->getById((int)$id);
        if (!$surat) {
            Flasher::setFlash('Surat', 'Data tidak ditemukan.', 'error');
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }

        $kategoriModel = $this->model('Kategori_model');
        $kategori = $kategoriModel->getKategoriByKode($surat['kode_klasifikasi'] ?? '') ?? [];

        $data = [
            'surat' => $surat,
            'kategori' => $kategori,
            'unit_options' => $this->unitOptions,
        ];

        ob_start();
        extract($data);
        require APPROOT . '/templates/surat_masuk/disposisi_pdf.php';
        $html = ob_get_clean();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [210, 148],
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output('kartu-disposisi.pdf', 'I');
        exit;
    }
}

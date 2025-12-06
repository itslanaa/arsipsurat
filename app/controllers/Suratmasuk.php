<?php

class Suratmasuk extends Controller
{
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
        $data['judul'] = 'Surat Masuk & Disposisi';
        $data['daftar_surat'] = $model->getAll(100);
        $data['status_count'] = $model->countByStatus();
        $data['total_surat_masuk'] = $model->countTotal();

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('surat_masuk/index', $data);
        $this->view('layouts/footer');
    }

    public function create()
    {
        $data['judul'] = 'Input Surat Masuk';
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('surat_masuk/create', $data);
        $this->view('layouts/footer');
    }

    public function store()
    {
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

        $kategoriNama = $this->kategoriArsipDariKode($surat['kode_klasifikasi'] ?? '');
        $kategoriModel = $this->model('Kategori_model');
        $idKategori = $kategoriModel->getOrCreateByNama($kategoriNama);
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

    private function kategoriArsipDariKode(string $kode)
    {
        $map = [
            '800.1' => '800.1 - Sumber Daya Manusia',
            '800.2' => '800.2 - Pendidikan dan Pelatihan',
            '400.14' => '400.14 - Hubungan Masyarakat',
        ];
        return $map[$kode] ?? 'Surat Masuk';
    }
}

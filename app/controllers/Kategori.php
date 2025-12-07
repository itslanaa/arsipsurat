<?php

class Kategori extends Controller {
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
        $data['judul'] = 'Manajemen Kategori Arsip';
        $data['kategori'] = $this->model('Kategori_model')->getAllKategori();

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('kategori/index', $data);
        $this->view('layouts/footer');
    }

    public function store()
    {
        if ($this->model('Kategori_model')->tambahDataKategori($_POST) > 0) {
            Flasher::setFlash('Kategori', 'berhasil ditambahkan', 'success');
        } else {
            Flasher::setFlash('Kategori', 'gagal ditambahkan', 'error');
        }
        header('Location: ' . BASE_URL . '/kategori');
        exit;
    }

    public function destroy($id)
    {
        if ($this->model('Kategori_model')->hapusDataKategori($id) > 0) {
            Flasher::setFlash('Kategori', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Kategori', 'gagal dihapus', 'error');
        }
        header('Location: ' . BASE_URL . '/kategori');
        exit;
    }
    
    public function getUbah()
    {
        header('Content-Type: application/json');
        echo json_encode($this->model('Kategori_model')->getKategoriById($_POST['id']));
    }

    public function update()
    {
        if ($this->model('Kategori_model')->ubahDataKategori($_POST) > 0) {
            Flasher::setFlash('Kategori', 'berhasil diubah', 'success');
        } else {
            Flasher::setFlash('Kategori', 'gagal diubah', 'error');
        }
        header('Location: ' . BASE_URL . '/kategori');
        exit;
    }
}

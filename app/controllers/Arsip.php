<?php

class Arsip extends Controller {
    public function __construct()
    {
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
        $data['judul'] = 'Manajemen Arsip Digital';
        $data['arsip'] = $this->model('Arsip_model')->getAllArsip();
        $data['kategori'] = $this->model('Kategori_model')->getAllKategori();
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('arsip/index', $data);
        $this->view('layouts/footer');
    }

    public function create()
    {
        $data['judul'] = 'Tambah Arsip Baru';
        $data['kategori'] = $this->model('Kategori_model')->getAllKategori();
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('arsip/create', $data);
        $this->view('layouts/footer');
    }

    public function store()
    {
        if ($this->model('Arsip_model')->tambahDataArsip($_POST, $_FILES)) {
            Flasher::setFlash('Data Arsip', 'berhasil ditambahkan', 'success');
        } else {
            Flasher::setFlash('Data Arsip', 'gagal ditambahkan', 'error');
        }
        header('Location: ' . BASE_URL . '/arsip');
        exit;
    }
    
    public function edit($id)
    {
        $data['judul'] = 'Edit Arsip';
        $data['arsip'] = $this->model('Arsip_model')->getArsipById($id);
        $data['kategori'] = $this->model('Kategori_model')->getAllKategori();
        if (!$data['arsip']) {
            header('Location: ' . BASE_URL . '/arsip');
            exit;
        }
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('arsip/edit', $data);
        $this->view('layouts/footer');
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('Arsip_model')->ubahDataArsip($_POST, $_FILES) >= 0) {
                Flasher::setFlash('Data Arsip', 'berhasil diubah', 'success');
            } else {
                Flasher::setFlash('Data Arsip', 'gagal diubah', 'error');
            }
            header('Location: ' . BASE_URL . '/arsip');
            exit;
        }
    }

    public function destroy($id)
    {
        if ($this->model('Arsip_model')->hapusDataArsip($id) > 0) {
            Flasher::setFlash('Data Arsip', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Data Arsip', 'gagal dihapus', 'error');
        }
        header('Location: ' . BASE_URL . '/arsip');
        exit;
    }
    
    public function deleteFile($file_id, $arsip_id)
    {
        if ($this->model('Arsip_model')->hapusFileSpesifik($file_id)) {
            Flasher::setFlash('File lampiran', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('File lampiran', 'gagal dihapus', 'error');
        }
        header('Location: ' . BASE_URL . '/arsip/edit/' . $arsip_id);
        exit;
    }

    // === METODE BARU UNTUK MENGAMBIL INFO FILE VIA JAVASCRIPT ===
    public function getFileInfo($file_id)
    {
        header('Content-Type: application/json');
        $file = $this->model('Arsip_model')->getFileById($file_id);
        echo json_encode($file);
    }

    public function download($arsip_id)
    {
        $arsip = $this->model('Arsip_model')->getArsipById($arsip_id);
        if (!$arsip || empty($arsip['files'])) {
            die('Tidak ada file untuk diunduh.');
        }
        if (count($arsip['files']) === 1) {
            $file = $arsip['files'][0];
            $this->kirimFile($file['nama_file_unik'], $file['nama_file_asli']);
        } else {
            $zip = new ZipArchive();
            $zipFileName = 'arsip-' . preg_replace('/[^A-Za-z0-9\-]/', '', $arsip['judul']) . '.zip';
            $zipFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipFileName;
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                die("Tidak dapat membuat file ZIP");
            }
            foreach ($arsip['files'] as $file) {
                $filePath = APPROOT . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'arsip' . DIRECTORY_SEPARATOR . $file['nama_file_unik'];
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $file['nama_file_asli']);
                }
            }
            $zip->close();
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
            header('Content-Length: ' . filesize($zipFilePath));
            flush();
            readfile($zipFilePath);
            unlink($zipFilePath);
            exit;
        }
    }

    public function downloadFile($file_id)
    {
        $file = $this->model('Arsip_model')->getFileById($file_id);
        if ($file) {
            $this->kirimFile($file['nama_file_unik'], $file['nama_file_asli']);
        }
        die('Error: File tidak ditemukan.');
    }

    private function kirimFile($nama_file_unik, $nama_file_asli)
    {
        $filePath = APPROOT . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'arsip' . DIRECTORY_SEPARATOR . $nama_file_unik;
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($nama_file_asli) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            flush();
            readfile($filePath);
            exit;
        }
    }
    
    public function search()
    {
        error_reporting(0);
        ini_set('display_errors', 0);
        header('Content-Type: application/json');
        try {
            $data = $this->model('Arsip_model')->cariDataArsip($_POST);
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan pada server: ' . $e->getMessage()]);
        }
    }
}

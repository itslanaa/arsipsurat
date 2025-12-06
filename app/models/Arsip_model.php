<?php

class Arsip_model {
    private $table = 'arsip';
    private $files_table = 'arsip_files';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllArsip()
    {
        $this->db->query("
            SELECT a.*, k.nama_kategori, 
            (SELECT COUNT(*) FROM {$this->files_table} WHERE id_arsip = a.id) as jumlah_file
            FROM {$this->table} a
            JOIN kategori_arsip k ON a.id_kategori = k.id 
            ORDER BY a.tgl_upload DESC, a.id DESC
        ");
        return $this->db->resultSet();
    }

    public function getArsipById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id=:id");
        $this->db->bind('id', $id);
        $arsip = $this->db->single();

        if ($arsip) {
            $this->db->query("SELECT * FROM " . $this->files_table . " WHERE id_arsip=:id_arsip");
            $this->db->bind('id_arsip', $id);
            $arsip['files'] = $this->db->resultSet();
        }
        return $arsip;
    }
    
    public function getFileById($id)
    {
        $this->db->query("SELECT * FROM " . $this->files_table . " WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahDataArsip($data, $files)
    {
        $query = "INSERT INTO " . $this->table . " (judul, id_kategori, tgl_upload, author, id_user_uploader) 
                  VALUES (:judul, :id_kategori, :tgl_upload, :author, :id_user_uploader)";
        
        $this->db->query($query);
        $this->db->bind('judul', $data['judul']);
        $this->db->bind('id_kategori', $data['id_kategori']);
        $this->db->bind('tgl_upload', date('Y-m-d'));
        $this->db->bind('author', $_SESSION['nama_lengkap'] ?? 'Administrator');
        $this->db->bind('id_user_uploader', $_SESSION['user_id'] ?? 1);
        $this->db->execute();
        
        $arsipId = $this->db->lastInsertId();

        $uploadedFiles = $this->reArrayFiles($files['files']);
        foreach ($uploadedFiles as $file) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $fileInfo = $this->uploadFile($file);
                if ($fileInfo) {
                    $queryFile = "INSERT INTO " . $this->files_table . " (id_arsip, nama_file_asli, nama_file_unik, path_file, filesize)
                                  VALUES (:id_arsip, :nama_asli, :nama_unik, :path, :ukuran)";
                    $this->db->query($queryFile);
                    $this->db->bind('id_arsip', $arsipId);
                    $this->db->bind('nama_asli', $fileInfo['nama_asli']);
                    $this->db->bind('nama_unik', $fileInfo['nama_unik']);
                    $this->db->bind('path', $fileInfo['path']);
                    $this->db->bind('ukuran', $fileInfo['ukuran']);
                    $this->db->execute();
                }
            }
        }
        return $arsipId;
    }

    public function ubahDataArsip($data, $files)
    {
        $query = "UPDATE " . $this->table . " SET judul = :judul, id_kategori = :id_kategori WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('judul', $data['judul']);
        $this->db->bind('id_kategori', $data['id_kategori']);
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        
        $uploadedFiles = $this->reArrayFiles($files['files']);
        foreach ($uploadedFiles as $file) {
            if (isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
                $fileInfo = $this->uploadFile($file);
                if ($fileInfo) {
                    $queryFile = "INSERT INTO " . $this->files_table . " (id_arsip, nama_file_asli, nama_file_unik, path_file, filesize)
                                  VALUES (:id_arsip, :nama_asli, :nama_unik, :path, :ukuran)";
                    $this->db->query($queryFile);
                    $this->db->bind('id_arsip', $data['id']);
                    $this->db->bind('nama_asli', $fileInfo['nama_asli']);
                    $this->db->bind('nama_unik', $fileInfo['nama_unik']);
                    $this->db->bind('path', $fileInfo['path']);
                    $this->db->bind('ukuran', $fileInfo['ukuran']);
                    $this->db->execute();
                }
            }
        }
        return $this->db->rowCount();
    }

    public function hapusDataArsip($id)
    {
        // 1. Ambil semua data file yang terkait dengan arsip ini
        $arsip = $this->getArsipById($id);
        
        // 2. Pastikan data arsip dan file-filenya ada
        if ($arsip && !empty($arsip['files'])) {
            // 3. Loop dan hapus setiap file fisik dari server
            foreach ($arsip['files'] as $file) {
                // Bentuk path absolut ke file di server
                $filePath = APPROOT . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'arsip' . DIRECTORY_SEPARATOR . $file['nama_file_unik'];
                
                // Cek apakah file benar-benar ada sebelum mencoba menghapus
                if (file_exists($filePath)) {
                    unlink($filePath); // Perintah untuk menghapus file
                }
            }
        }

        // 4. Hapus data utama dari tabel 'arsip'. 
        // Karena ada 'ON DELETE CASCADE', semua data di 'arsip_files' akan ikut terhapus.
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusFileSpesifik($file_id)
    {
        $file = $this->getFileById($file_id);
        if ($file) {
            $filePath = APPROOT . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'arsip' . DIRECTORY_SEPARATOR . $file['nama_file_unik'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $query = "DELETE FROM " . $this->files_table . " WHERE id = :id";
            $this->db->query($query);
            $this->db->bind('id', $file_id);
            $this->db->execute();
            return $this->db->rowCount();
        }
        return false;
    }

    private function reArrayFiles(&$file_post) {
        if (!isset($file_post['name']) || !is_array($file_post['name'])) {
            return [];
        }
        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }
        return $file_ary;
    }
    
    private function uploadFile($file)
    {
        $namaFile = $file['name'];
        $tmpName = $file['tmp_name'];
        $ukuranFile = $file['size'];

        $ekstensiValid = ['jpg', 'jpeg', 'png', 'pdf', 'docx', 'xlsx'];
        $ekstensiFile = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        if (!in_array($ekstensiFile, $ekstensiValid)) return false;

        $namaFileUnik = uniqid('arsip-') . '.' . $ekstensiFile;
        $pathTujuan = APPROOT . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'arsip' . DIRECTORY_SEPARATOR . $namaFileUnik;

        if (move_uploaded_file($tmpName, $pathTujuan)) {
            return [
                'nama_asli' => $namaFile,
                'nama_unik' => $namaFileUnik,
                'path'      => 'uploads/arsip/' . $namaFileUnik,
                'ukuran'    => $ukuranFile
            ];
        }
        return false;
    }

    public function cariDataArsip($data)
    {
        $keyword = $data['keyword'] ?? '';
        $kategori = $data['kategori'] ?? '';
        $tanggal_mulai = $data['tanggal_mulai'] ?? '';
        $tanggal_akhir = $data['tanggal_akhir'] ?? '';

        $query = "SELECT a.*, k.nama_kategori, 
                  (SELECT COUNT(*) FROM {$this->files_table} WHERE id_arsip = a.id) as jumlah_file
                  FROM {$this->table} a
                  JOIN kategori_arsip k ON a.id_kategori = k.id 
                  WHERE a.judul LIKE :keyword";

        if (!empty($kategori)) {
            $query .= " AND a.id_kategori = :kategori";
        }
        
        if (!empty($tanggal_mulai) && !empty($tanggal_akhir)) {
            $query .= " AND a.tgl_upload BETWEEN :tanggal_mulai AND :tanggal_akhir";
        } elseif (!empty($tanggal_mulai)) {
            $query .= " AND a.tgl_upload >= :tanggal_mulai";
        } elseif (!empty($tanggal_akhir)) {
            $query .= " AND a.tgl_upload <= :tanggal_akhir";
        }

        $query .= " ORDER BY a.id DESC";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        if (!empty($kategori)) {
            $this->db->bind('kategori', $kategori);
        }
        if (!empty($tanggal_mulai)) {
            $this->db->bind('tanggal_mulai', $tanggal_mulai);
        }
        if (!empty($tanggal_akhir)) {
            $this->db->bind('tanggal_akhir', $tanggal_akhir);
        }

        return $this->db->resultSet();
    }
}

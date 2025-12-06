<?php

class Suratmasuk_model
{
    private $table = 'surat_masuk';
    private $filesTable = 'surat_masuk_files';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAll($limit = 100)
    {
        $sql = "SELECT sm.*, COUNT(f.id) AS jumlah_file
                FROM {$this->table} sm
                LEFT JOIN {$this->filesTable} f ON f.id_surat_masuk = sm.id
                GROUP BY sm.id
                ORDER BY sm.tanggal_terima DESC, sm.id DESC
                LIMIT :batas";
        $this->db->query($sql);
        $this->db->bind('batas', (int)$limit, PDO::PARAM_INT);
        $rows = $this->db->resultSet();
        foreach ($rows as &$row) {
            $row['files'] = $this->getFiles((int)$row['id']);
        }
        return $rows;
    }

    public function getReferensiKeluar($limit = 100)
    {
        $sql = "SELECT id, nomor_agenda, perihal, kode_klasifikasi, unit_pengolah, status
                FROM {$this->table}
                WHERE kode_klasifikasi <> ''
                ORDER BY created_at DESC
                LIMIT :batas";
        $this->db->query($sql);
        $this->db->bind('batas', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind('id', $id);
        $row = $this->db->single();
        if ($row) {
            $row['files'] = $this->getFiles((int)$id);
        }
        return $row;
    }

    public function getFilesBySurat(int $id)
    {
        return $this->getFiles($id);
    }

    public function create(array $data, array $files)
    {
        $query = "INSERT INTO {$this->table}
                    (nomor_agenda, tanggal_terima, asal_surat, perihal, kode_klasifikasi, ringkasan, instruksi_camat, disposisi_sekcam, unit_pengolah, status, id_user_pencatat)
                  VALUES
                    (:nomor_agenda, :tanggal_terima, :asal_surat, :perihal, :kode_klasifikasi, :ringkasan, :instruksi_camat, :disposisi_sekcam, :unit_pengolah, :status, :id_user_pencatat)";

        $this->db->query($query);
        $this->db->bind('nomor_agenda', trim($data['nomor_agenda'] ?? ''));
        $this->db->bind('tanggal_terima', $this->normalizeDate($data['tanggal_terima'] ?? ''));
        $this->db->bind('asal_surat', trim($data['asal_surat'] ?? ''));
        $this->db->bind('perihal', trim($data['perihal'] ?? ''));
        $this->db->bind('kode_klasifikasi', trim($data['kode_klasifikasi'] ?? ''));
        $this->db->bind('ringkasan', trim($data['ringkasan'] ?? ''));
        $this->db->bind('instruksi_camat', trim($data['instruksi_camat'] ?? ''));
        $this->db->bind('disposisi_sekcam', trim($data['disposisi_sekcam'] ?? ''));
        $this->db->bind('unit_pengolah', trim($data['unit_pengolah'] ?? ''));
        $this->db->bind('status', $this->validStatus($data['status'] ?? '') ? $data['status'] : 'diterima');
        $this->db->bind('id_user_pencatat', $_SESSION['user_id'] ?? null);
        $this->db->execute();

        $idBaru = $this->db->lastInsertId();
        $this->simpanLampiran($idBaru, $files['lampiran'] ?? null);

        return $idBaru;
    }

    public function updateDisposisi(int $id, array $data, array $files = [])
    {
        $query = "UPDATE {$this->table}
                  SET instruksi_camat = :instruksi_camat,
                      disposisi_sekcam = :disposisi_sekcam,
                      unit_pengolah = :unit_pengolah,
                      status = :status,
                      kode_klasifikasi = :kode_klasifikasi
                  WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('instruksi_camat', trim($data['instruksi_camat'] ?? ''));
        $this->db->bind('disposisi_sekcam', trim($data['disposisi_sekcam'] ?? ''));
        $this->db->bind('unit_pengolah', trim($data['unit_pengolah'] ?? ''));
        $this->db->bind('kode_klasifikasi', trim($data['kode_klasifikasi'] ?? ''));
        $this->db->bind('status', $this->validStatus($data['status'] ?? '') ? $data['status'] : 'diterima');
        $this->db->bind('id', $id);
        $this->db->execute();

        $this->simpanLampiran($id, $files['lampiran'] ?? null);
        return $this->db->rowCount();
    }

    public function countTotal()
    {
        $this->db->query("SELECT COUNT(*) AS total FROM {$this->table}");
        $row = $this->db->single();
        return (int)($row['total'] ?? 0);
    }

    public function countByStatus()
    {
        $this->db->query("SELECT status, COUNT(*) AS total FROM {$this->table} GROUP BY status");
        $rows = $this->db->resultSet();
        $map = [];
        foreach ($rows as $r) {
            $map[$r['status']] = (int)$r['total'];
        }
        return $map;
    }

    private function simpanLampiran(int $idSurat, $files)
    {
        if (!$files || empty($files['name'])) {
            return;
        }
        $list = $this->reArrayFiles($files);
        foreach ($list as $file) {
            if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) continue;
            $info = $this->uploadFile($file);
            if (!$info) continue;

            $this->db->query("INSERT INTO {$this->filesTable} (id_surat_masuk, nama_file_asli, nama_file_unik, path_file, filesize, jenis_lampiran)
                             VALUES (:id_surat_masuk, :nama_file_asli, :nama_file_unik, :path_file, :filesize, :jenis)");
            $this->db->bind('id_surat_masuk', $idSurat);
            $this->db->bind('nama_file_asli', $info['nama_asli']);
            $this->db->bind('nama_file_unik', $info['nama_unik']);
            $this->db->bind('path_file', $info['path']);
            $this->db->bind('filesize', $info['ukuran']);
            $this->db->bind('jenis', $info['jenis']);
            $this->db->execute();
        }
    }

    private function getFiles(int $idSurat)
    {
        $this->db->query("SELECT * FROM {$this->filesTable} WHERE id_surat_masuk = :id");
        $this->db->bind('id', $idSurat);
        return $this->db->resultSet();
    }

    private function normalizeDate($raw)
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
            return $raw;
        }
        return date('Y-m-d');
    }

    private function validStatus(string $status)
    {
        $allowed = ['diterima', 'instruksi_camat', 'sekcam', 'distribusi_umpeg', 'diproses_unit', 'selesai'];
        return in_array($status, $allowed, true);
    }

    private function reArrayFiles($file_post)
    {
        if (!isset($file_post['name']) || !is_array($file_post['name'])) {
            return [];
        }
        $file_ary = [];
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i = 0; $i < $file_count; $i++) {
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

        $ekstensiValid = ['pdf', 'docx', 'jpg', 'jpeg', 'png'];
        $ekstensiFile = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        if (!in_array($ekstensiFile, $ekstensiValid)) return false;

        $jenisLampiran = 'lampiran';
        $lowerName = strtolower($namaFile);
        if (str_contains($lowerName, 'disposisi')) {
            $jenisLampiran = 'kartu_disposisi';
        } elseif (str_contains($lowerName, 'surat')) {
            $jenisLampiran = 'scan_surat';
        }

        $namaFileUnik = uniqid('sm-') . '.' . $ekstensiFile;
        $folder = APPROOT . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'surat_masuk';
        if (!is_dir($folder)) {
            @mkdir($folder, 0775, true);
        }
        $pathTujuan = $folder . DIRECTORY_SEPARATOR . $namaFileUnik;

        if (move_uploaded_file($tmpName, $pathTujuan)) {
            return [
                'nama_asli' => $namaFile,
                'nama_unik' => $namaFileUnik,
                'path'      => 'uploads/surat_masuk/' . $namaFileUnik,
                'ukuran'    => $ukuranFile,
                'jenis'     => $jenisLampiran
            ];
        }
        return false;
    }
}

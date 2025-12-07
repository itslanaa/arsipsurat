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
        $filter = $this->unitFilterClause('sm');
        $sql = "SELECT sm.*, COUNT(f.id) AS jumlah_file
                FROM {$this->table} sm
                LEFT JOIN {$this->filesTable} f ON f.id_surat_masuk = sm.id
                {$filter['clause']}
                GROUP BY sm.id
                ORDER BY sm.tanggal_terima DESC, sm.id DESC
                LIMIT :batas";
        $this->db->query($sql);
        foreach ($filter['params'] as $key => $val) {
            $this->db->bind($key, $val);
        }
        $this->db->bind('batas', (int)$limit, PDO::PARAM_INT);
        $rows = $this->db->resultSet();
        foreach ($rows as &$row) {
            $row['files'] = $this->getFiles((int)$row['id']);
        }
        return $rows;
    }

    public function getReferensiKeluar($limit = 100)
    {
        $sql = "SELECT id, nomor_agenda, perihal, kode_klasifikasi, unit_pengolah, status, tanggal_surat
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
                    (nomor_agenda, tanggal_surat, tanggal_terima, asal_surat, perihal, kode_klasifikasi, ringkasan, instruksi_camat, catatan_koreksi, disposisi_sekcam, unit_pengolah, status, id_user_pencatat)
                  VALUES
                    (:nomor_agenda, :tanggal_surat, :tanggal_terima, :asal_surat, :perihal, :kode_klasifikasi, :ringkasan, :instruksi_camat, :catatan_koreksi, :disposisi_sekcam, :unit_pengolah, :status, :id_user_pencatat)";

        $this->db->query($query);
        $this->db->bind('nomor_agenda', trim($data['nomor_agenda'] ?? ''));
        $this->db->bind('tanggal_surat', $this->normalizeOptionalDate($data['tanggal_surat'] ?? null));
        $this->db->bind('tanggal_terima', $this->normalizeDate($data['tanggal_terima'] ?? ''));
        $this->db->bind('asal_surat', trim($data['asal_surat'] ?? ''));
        $this->db->bind('perihal', trim($data['perihal'] ?? ''));
        $this->db->bind('kode_klasifikasi', trim($data['kode_klasifikasi'] ?? ''));
        $this->db->bind('ringkasan', trim($data['ringkasan'] ?? ''));
        $this->db->bind('instruksi_camat', trim($data['instruksi_camat'] ?? ''));
        $this->db->bind('catatan_koreksi', trim($data['catatan_koreksi'] ?? ''));
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
        $current = $this->getById($id);
        if (!$current) {
            return -1;
        }

        $role = currentRole();
        if ($role === 'sekcam' && !in_array($current['status'], ['sekcam', 'instruksi_camat'], true)) {
            return -1;
        }

        $statusBaru = $this->resolveStatus($role, $current['status'], $data['status'] ?? '');

        $payload = [
            'instruksi_camat'   => $current['instruksi_camat'],
            'catatan_koreksi'   => $current['catatan_koreksi'],
            'disposisi_sekcam'  => $current['disposisi_sekcam'],
            'unit_pengolah'     => $current['unit_pengolah'],
            'kode_klasifikasi'  => $current['kode_klasifikasi'],
            'status'            => $statusBaru,
        ];

        if (in_array($role, ['camat', 'staf', 'admin'], true)) {
            $payload['instruksi_camat'] = trim($data['instruksi_camat'] ?? $payload['instruksi_camat']);
            $payload['kode_klasifikasi'] = trim($data['kode_klasifikasi'] ?? $payload['kode_klasifikasi']);
        }

        if ($role === 'camat') {
            $payload['catatan_koreksi'] = trim($data['catatan_koreksi'] ?? $payload['catatan_koreksi']);
        }

        if (in_array($role, ['sekcam', 'staf', 'admin'], true)) {
            $payload['disposisi_sekcam'] = trim($data['disposisi_sekcam'] ?? $payload['disposisi_sekcam']);
            $payload['unit_pengolah'] = trim($data['unit_pengolah'] ?? $payload['unit_pengolah']);
        }

        if (in_array($role, ['staf', 'admin'], true) && isset($data['status']) && $this->validStatus($data['status'])) {
            $payload['status'] = $data['status'];
        }

        $query = "UPDATE {$this->table}
                  SET instruksi_camat = :instruksi_camat,
                      catatan_koreksi = :catatan_koreksi,
                      disposisi_sekcam = :disposisi_sekcam,
                      unit_pengolah = :unit_pengolah,
                      status = :status,
                      kode_klasifikasi = :kode_klasifikasi
                  WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('instruksi_camat', $payload['instruksi_camat']);
        $this->db->bind('catatan_koreksi', $payload['catatan_koreksi']);
        $this->db->bind('disposisi_sekcam', $payload['disposisi_sekcam']);
        $this->db->bind('unit_pengolah', $payload['unit_pengolah']);
        $this->db->bind('kode_klasifikasi', $payload['kode_klasifikasi']);
        $this->db->bind('status', $payload['status']);
        $this->db->bind('id', $id);
        $this->db->execute();

        $this->simpanLampiran($id, $files['lampiran'] ?? null);
        return $this->db->rowCount();
    }

    public function countTotal()
    {
        $filter = $this->unitFilterClause();
        $this->db->query("SELECT COUNT(*) AS total FROM {$this->table} {$filter['clause']}");
        foreach ($filter['params'] as $key => $val) {
            $this->db->bind($key, $val);
        }
        $row = $this->db->single();
        return (int)($row['total'] ?? 0);
    }

    public function countByStatus()
    {
        $filter = $this->unitFilterClause();
        $this->db->query("SELECT status, COUNT(*) AS total FROM {$this->table} {$filter['clause']} GROUP BY status");
        foreach ($filter['params'] as $key => $val) {
            $this->db->bind($key, $val);
        }
        $rows = $this->db->resultSet();
        $map = [];
        foreach ($rows as $r) {
            $map[$r['status']] = (int)$r['total'];
        }
        return $map;
    }

    public function terimaOlehUnit(int $id)
    {
        $current = $this->getById($id);
        if (!$current || !$this->isSuratVisibleToCurrentUnit($current) || $current['status'] !== 'diproses_unit') {
            return 0;
        }

        $this->db->query("UPDATE {$this->table} SET status = 'selesai' WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    private function simpanLampiran(int $idSurat, $files)
    {
        if (!$files || empty($files['name'])) {
            return;
        }
        $list = $this->reArrayFiles($files);
        $inserted = [];
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

            $inserted[] = [
                'id'             => $this->db->lastInsertId(),
                'nama_file_asli' => $info['nama_asli'],
                'nama_file_unik' => $info['nama_unik'],
                'path_file'      => $info['path'],
                'filesize'       => $info['ukuran'],
            ];
        }

        if (!empty($inserted)) {
            $this->sinkronLampiranKeArsip($idSurat, $inserted);
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

    private function normalizeOptionalDate($raw)
    {
        if (empty($raw)) {
            return null;
        }
        return $this->normalizeDate($raw);
    }

    private function validStatus(string $status)
    {
        $allowed = ['diterima', 'instruksi_camat', 'koreksi', 'sekcam', 'distribusi_umpeg', 'diproses_unit', 'selesai'];
        return in_array($status, $allowed, true);
    }

    private function resolveStatus(string $role, string $currentStatus, string $submitted)
    {
        if ($role === 'camat') {
            return in_array($submitted, ['instruksi_camat', 'koreksi', 'sekcam'], true) ? $submitted : 'instruksi_camat';
        }
        if ($role === 'sekcam') {
            return 'distribusi_umpeg';
        }
        if ($role === 'unit') {
            return $currentStatus === 'diproses_unit' ? 'selesai' : $currentStatus;
        }
        return $this->validStatus($submitted) ? $submitted : $currentStatus;
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

    private function sinkronLampiranKeArsip(int $idSurat, array $lampiranBaru)
    {
        if (empty($lampiranBaru)) {
            return;
        }
        require_once APPROOT . '/app/models/Arsip_model.php';
        $arsipModel = new Arsip_model();
        $arsipList = $arsipModel->getArsipBySuratMasuk($idSurat);
        if (empty($arsipList)) {
            return;
        }

        foreach ($arsipList as $arsip) {
            foreach ($lampiranBaru as $lampiran) {
                $arsipModel->salinLampiranSuratMasukKeArsip((int)$arsip['id'], $lampiran);
            }
        }
    }

    public function isSuratVisibleToCurrentUnit(array $surat): bool
    {
        if (currentRole() !== 'unit') {
            return true;
        }
        $allowed = $this->unitAccessForUser($_SESSION['username'] ?? '');
        if (empty($allowed)) {
            return false;
        }
        return in_array($surat['unit_pengolah'] ?? '', $allowed, true);
    }

    private function unitFilterClause(string $alias = ''): array
    {
        if (currentRole() !== 'unit') {
            return ['clause' => '', 'params' => []];
        }

        $units = $this->unitAccessForUser($_SESSION['username'] ?? '');
        if (empty($units)) {
            return ['clause' => ' WHERE 1=0', 'params' => []];
        }

        $column = $alias ? $alias . '.unit_pengolah' : 'unit_pengolah';
        $params = [];
        $placeholders = [];
        foreach ($units as $index => $unit) {
            $param = 'unit' . $index;
            $params[$param] = $unit;
            $placeholders[] = ':' . $param;
        }

        return [
            'clause' => ' WHERE ' . $column . ' IN (' . implode(',', $placeholders) . ')',
            'params' => $params,
        ];
    }

    private function unitAccessForUser(string $username): array
    {
        $map = [
            'staf' => ['Umpeg'],
            'kasipenkes' => ['Umpeg'],
            'kasipem' => ['Pemerintahan'],
            'kasipm' => ['Pembangunan'],
            'kasitrantibum' => ['Trantib'],
            'kasiekbang' => ['Ekonomi Pembangunan'],
        ];

        return $map[$username] ?? [];
    }
}

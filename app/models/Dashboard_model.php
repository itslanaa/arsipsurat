<?php

class Dashboard_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Menghitung total arsip (entri utama).
     */
    public function getTotalArsip()
    {
        $this->db->query("SELECT COUNT(id) as total FROM arsip");
        return $this->db->single()['total'];
    }

    /**
     * Menghitung total kategori.
     */
    public function getTotalKategori()
    {
        $this->db->query("SELECT COUNT(id) as total FROM kategori_arsip");
        return $this->db->single()['total'];
    }

    /**
     * Menghitung total pengguna.
     */
    public function getTotalPengguna()
    {
        $this->db->query("SELECT COUNT(id) as total FROM users");
        return $this->db->single()['total'];
    }

    /**
     * Mengambil data untuk chart distribusi arsip.
     */
    public function getArsipDistribution()
    {
        $this->db->query("
            SELECT CONCAT(k.kode, ' - ', k.nama_kategori) AS nama_kategori, COUNT(a.id) as jumlah
            FROM kategori_arsip k
            LEFT JOIN arsip a ON k.id = a.id_kategori
            GROUP BY k.id
            ORDER BY jumlah DESC
        ");
        return $this->db->resultSet();
    }

    public function getTotalSurat() {
        $this->db->query("SELECT COUNT(*) AS total FROM surat_keluar");
        $row = $this->db->single();
        return (int)($row['total'] ?? 0);
    }

    public function getTotalSuratMasuk() {
        $this->db->query("SELECT COUNT(*) AS total FROM surat_masuk");
        $row = $this->db->single();
        return (int)($row['total'] ?? 0);
    }

    /* --- SURAT PER BULAN sejak $start (YYYY-mm-01) --- */
    public function getSuratPerBulan($startYmd) {
        $this->db->query("
            SELECT DATE_FORMAT(tanggal_surat, '%Y-%m') AS ym, COUNT(*) AS jml
            FROM surat_keluar
            WHERE tanggal_surat >= :start
            GROUP BY ym
            ORDER BY ym
        ");
        $this->db->bind('start', $startYmd);
        return $this->db->resultSet();
    }

    /* --- KOMPOSISI TEMPLATE SURAT --- */
    public function getSuratByTemplate() {
        $this->db->query("
            SELECT COALESCE(t.nama_template, 'Tanpa Template') AS nama_template,
                   COUNT(*) AS jml
            FROM surat_keluar sk
            LEFT JOIN template_surat t ON t.id = sk.id_template
            GROUP BY sk.id_template
            ORDER BY jml DESC
        ");
        return $this->db->resultSet();
    }

    public function getSuratMasukByStatus() {
        $this->db->query("SELECT status, COUNT(*) AS jml FROM surat_masuk GROUP BY status");
        return $this->db->resultSet();
    }
}

<?php

class Surat_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getPejabatDefault()
    {
        $this->db->query("SELECT * FROM pejabat WHERE is_default = 1 LIMIT 1");
        return $this->db->single();
    }
    public function getSuratKeluar($limit = 50)
    {
        $this->db->query("
    SELECT sk.*, t.nama_template
    FROM surat_keluar sk
    LEFT JOIN template_surat t ON t.id = sk.id_template
    ORDER BY sk.id DESC
    LIMIT :lim
  ");
        $this->db->bind('lim', (int)$limit);
        return $this->db->resultSet();
    }

    public function getSuratKeluarByUser($userId, $limit = 50)
    {
        $this->db->query("
    SELECT sk.*, t.nama_template
    FROM surat_keluar sk
    LEFT JOIN template_surat t ON t.id = sk.id_template
    WHERE sk.id_user_pembuat = :uid
    ORDER BY sk.id DESC
    LIMIT :lim
  ");
        $this->db->bind('uid', (int)$userId);
        $this->db->bind('lim', (int)$limit);
        return $this->db->resultSet();
    }

    public function getSuratKeluarById($id)
    {
        $this->db->query("SELECT * FROM surat_keluar WHERE id=:id LIMIT 1");
        $this->db->bind('id', (int)$id);
        return $this->db->single();
    }

    public function insertSuratKeluar($p)
    {
        $this->db->query("
    INSERT INTO surat_keluar
    (id_template, nomor_surat, tanggal_surat, perihal, data_surat,
     id_user_pembuat, nama_file_pdf, path_file, kode_klasifikasi, id_surat_masuk, created_at)
    VALUES
    (:id_template, :nomor_surat, :tanggal_surat, :perihal, :data_surat,
     :id_user_pembuat, :nama_file_pdf, :path_file, :kode_klasifikasi, :id_surat_masuk, NOW())
  ");
        $this->db->bind('id_template',     $p['id_template']);
        $this->db->bind('nomor_surat',     $p['nomor_surat']);
        $this->db->bind('tanggal_surat',   $p['tanggal_surat']);
        $this->db->bind('perihal',         $p['perihal']);
        $this->db->bind('data_surat',      $p['data_surat']);
        $this->db->bind('id_user_pembuat', $p['id_user_pembuat']);
        $this->db->bind('nama_file_pdf',   $p['nama_file_pdf']);
        $this->db->bind('path_file',       $p['path_file']);
        $this->db->bind('kode_klasifikasi', $p['kode_klasifikasi'] ?? null);
        $this->db->bind('id_surat_masuk', $p['id_surat_masuk'] ?? null);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function updateSuratKeluar($id, $p)
    {
        $this->db->query("
    UPDATE surat_keluar SET
      nomor_surat=:nomor_surat,
      tanggal_surat=:tanggal_surat,
      perihal=:perihal
    WHERE id=:id
  ");
        $this->db->bind('id', (int)$id);
        $this->db->bind('nomor_surat',   $p['nomor_surat']);
        $this->db->bind('tanggal_surat', $p['tanggal_surat']);
        $this->db->bind('perihal',       $p['perihal']);
        return $this->db->execute();
    }

    public function deleteSuratKeluar($id)
    {
        $this->db->query("DELETE FROM surat_keluar WHERE id=:id");
        $this->db->bind('id', (int)$id);
        return $this->db->execute();
    }

    public function getAllTemplates()
    {
        $this->db->query("SELECT * FROM template_surat ORDER BY nama_template ASC");
        return $this->db->resultSet();
    }

    public function getTemplateById($id)
    {
        $this->db->query("SELECT * FROM template_surat WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getTemplateByKode($kode)
    {
        // Jika tabel punya kolom 'kode_template'
        $this->db->query("SELECT id, nama_template, kode_template FROM template_surat WHERE kode_template = :k LIMIT 1");
        $this->db->bind('k', $kode);
        return $this->db->single();
    }

    /* Fallback jika belum ada kolom 'kode_template':
   cari berdasarkan nama template (LIKE) */
    public function getTemplateByNamaLike($needle)
    {
        $this->db->query("SELECT id, nama_template FROM template_surat WHERE LOWER(nama_template) LIKE :q LIMIT 1");
        $this->db->bind('q', '%' . strtolower($needle) . '%');
        return $this->db->single();
    }


    // Fungsi untuk menyimpan data surat keluar akan ditambahkan di sini
}

<?php

class Kategori_model {
    private $table = 'kategori_arsip';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllKategori()
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY kode ASC');
        return $this->db->resultSet();
    }

    public function getKategoriById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getKategoriByNama($nama)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE nama_kategori = :nama LIMIT 1');
        $this->db->bind('nama', $nama);
        return $this->db->single();
    }

    public function getKategoriByKode($kode)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE kode = :kode LIMIT 1');
        $this->db->bind('kode', $kode);
        return $this->db->single();
    }

    public function getOrCreateByKode($kode, $nama = '')
    {
        if (!$kode) {
            return 0;
        }
        $existing = $this->getKategoriByKode($kode);
        if ($existing && isset($existing['id'])) {
            return (int)$existing['id'];
        }
        $payload = [
            'kode' => $kode,
            'nama_kategori' => $nama ?: $kode,
        ];
        $this->tambahDataKategori($payload);
        $created = $this->getKategoriByKode($kode);
        return (int)($created['id'] ?? 0);
    }

    public function tambahDataKategori($data)
    {
        $query = "INSERT INTO " . $this->table . " (kode, nama_kategori) VALUES (:kode, :nama_kategori)";
        $this->db->query($query);
        $this->db->bind('kode', $data['kode']);
        $this->db->bind('nama_kategori', $data['nama_kategori']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataKategori($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function ubahDataKategori($data)
    {
        $query = "UPDATE " . $this->table . " SET kode = :kode, nama_kategori = :nama_kategori WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('kode', $data['kode']);
        $this->db->bind('nama_kategori', $data['nama_kategori']);
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }
}

<?php
class Pejabat_model {
    private $db;
    public function __construct() {
        $this->db = new Database; // asumsi kamu udah ada class Database wrapper
    }

    public function getDefault() {
        $this->db->query("SELECT * FROM tb_pejabat WHERE is_default = 1 LIMIT 1");
        return $this->db->single();
    }

    public function getAll() {
        $this->db->query("SELECT * FROM tb_pejabat");
        return $this->db->resultSet();
    }
}

<?php
// Memuat file inisialisasi dari folder aplikasi
require_once '../app/init.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Menjalankan kelas App utama untuk memulai routing
$app = new App;

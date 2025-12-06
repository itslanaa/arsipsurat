<?php

// Konfigurasi untuk koneksi ke Database
// Ganti nilai-nilai ini sesuai dengan pengaturan server database Anda.
define('DB_HOST', 'localhost');      // Host database, biasanya 'localhost'
define('DB_USER', 'root');           // Username database
define('DB_PASS', '');               // Password database
define('DB_NAME', 'db_arsipsurat'); // Nama database yang sudah Anda buat

// Definisikan path root aplikasi untuk mempermudah pemanggilan file.
// dirname(__FILE__) -> /app/config
// dirname(dirname(__FILE__)) -> /app
// dirname(dirname(dirname(__FILE__))) -> / (root proyek)
define('APPROOT', dirname(dirname(dirname(__FILE__))));

// Konfigurasi URL dasar (Base URL) aplikasi
// mis: http://localhost/aplikasi-arsip-kecamatan/public
define('BASE_URL', 'http://localhost/arsipsurat/public');

// Konfigurasi zona waktu (Timezone)
// Atur sesuai dengan lokasi Anda untuk memastikan fungsi tanggal dan waktu berjalan benar.
date_default_timezone_set('Asia/Jakarta');

?>

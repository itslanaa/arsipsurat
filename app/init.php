<?php

// Memulai session di awal
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Memuat file konfigurasi utama
require_once 'config/config.php';

// Memuat semua file inti (core)
require_once APPROOT . '/app/core/App.php';
require_once APPROOT . '/app/core/Controller.php';
require_once APPROOT . '/app/core/Database.php';


// Memuat file helper Functions.php (jika ada)
$helperFunctionsPath = APPROOT . '/app/helpers/Functions.php';
if (file_exists($helperFunctionsPath)) {
    require_once $helperFunctionsPath;
}

// Memuat file helper Flasher.php (PENTING!)
$flasherPath = APPROOT . '/app/helpers/Flasher.php';
if (file_exists($flasherPath)) {
    require_once $flasherPath;
} else {
    die('Error: File helper Flasher.php tidak ditemukan. Pastikan file ada di app/helpers/Flasher.php');
}

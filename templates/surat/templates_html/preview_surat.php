<?php
// Nonaktifkan error notice biar tampilan bersih
error_reporting(E_ERROR | E_PARSE);

// Load BASE_URL dari config asli
require_once __DIR__ . '/../app/config/config.php';

// Ambil nama template dari query string
$template = $_GET['template'] ?? 'surat_tugas';

// Whitelist nama template untuk keamanan
$allowed_templates = ['surat_tugas', 'surat_keterangan'];
if (!in_array($template, $allowed_templates)) {
    http_response_code(404);
    echo "Template tidak valid.";
    exit;
}

// Tentukan path absolut file template
$template_path = __DIR__ . '/' . $template . '.php';

// Cek file benar-benar ada
if (!file_exists($template_path)) {
    http_response_code(404);
    echo "File template tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview <?= htmlspecialchars(str_replace('_', ' ', $template)) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/preview.css"> <!-- opsional -->
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff;
            padding: 2rem;
            margin: 0;
        }
    </style>
</head>
<body>
    <?php include $template_path; ?>
</body>
</html>

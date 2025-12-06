<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judul); ?> - Arsip Kecamatan</title>
    
    <!-- Tailwind CSS from CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/style.css">

    <!-- Memuat library Chart.js sebelum konten halaman -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- backkup link chart.js npm https://cdn.jsdelivr.net/npm/chart.js -->
     
    
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen">
        <!-- Sidebar akan dimuat di sini oleh controller -->
         

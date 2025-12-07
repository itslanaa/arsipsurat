<?php

/**
 * Mengubah ukuran byte menjadi format yang mudah dibaca (KB, MB, GB).
 * @param int $bytes Ukuran file dalam bytes.
 * @param int $precision Jumlah angka di belakang koma.
 * @return string Ukuran file yang sudah diformat.
 */
function formatBytes($bytes, $precision = 2) {
    if ($bytes > 0) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    return '0 B';
}

function currentRole(): string {
    return $_SESSION['role'] ?? 'staf';
}

function ensureRole(array $allowed)
{
    if (!in_array(currentRole(), $allowed, true)) {
        header('Location: ' . BASE_URL . '/suratmasuk');
        exit;
    }
}

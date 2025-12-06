<?php

class Flasher {
    /**
     * Menyimpan pesan flash ke dalam session.
     * @param string $pesan Pesan utama (misal: "Data arsip berhasil").
     * @param string $aksi Aksi yang dilakukan (misal: "ditambahkan").
     * @param string $tipe Tipe notifikasi ('success' atau 'error').
     */
    public static function setFlash($pesan, $aksi, $tipe)
    {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi'  => $aksi,
            'tipe'  => $tipe
        ];
    }

    /**
     * Menampilkan pesan flash jika ada, lalu menghapusnya.
     * Ini akan mencetak div tersembunyi yang akan dibaca oleh JavaScript.
     */
    public static function flash()
    {
        if (isset($_SESSION['flash'])) {
            echo '<div id="flash-data" 
                     data-pesan="' . htmlspecialchars($_SESSION['flash']['pesan']) . '" 
                     data-aksi="' . htmlspecialchars($_SESSION['flash']['aksi']) . '" 
                     data-tipe="' . htmlspecialchars($_SESSION['flash']['tipe']) . '">
                  </div>';
            unset($_SESSION['flash']);
        }
    }
}

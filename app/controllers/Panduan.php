<?php

class Panduan extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }

        if (in_array(currentRole(), ['camat', 'sekcam', 'unit'], true)) {
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Panduan Penggunaan';
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('panduan/index', $data);
        $this->view('layouts/footer');
    }
}

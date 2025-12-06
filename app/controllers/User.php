<?php

class User extends Controller {
    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
    }

    public function pengaturan()
    {
        $data['judul'] = 'Pengaturan Akun';
        $data['user'] = $this->model('User_model')->getUserById($_SESSION['user_id']);
        
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('user/pengaturan', $data);
        $this->view('layouts/footer');
    }

    public function history()
    {
        $data['judul'] = 'History Login';
        $data['history'] = $this->model('User_model')->getLoginHistory($_SESSION['user_id']);

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('user/history', $data);
        $this->view('layouts/footer');
    }

    public function updatePassword()
    {
        if ($this->model('User_model')->ubahPassword($_POST) > 0) {
            Flasher::setFlash('Password', 'berhasil diubah', 'success');
        } else {
            // Model akan mengatur pesan error spesifik
        }
        header('Location: ' . BASE_URL . '/user/pengaturan');
        exit;
    }
}

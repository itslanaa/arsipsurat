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

    public function kelola()
    {
        ensureRole(['admin', 'staf']);
        $data['judul'] = 'Kelola Pengguna';
        $data['users'] = $this->model('User_model')->getAll();

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('user/manage', $data);
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

    public function store()
    {
        ensureRole(['admin', 'staf']);
        if ($this->model('User_model')->createUser($_POST) > 0) {
            Flasher::setFlash('Pengguna', 'berhasil ditambahkan', 'success');
        }
        header('Location: ' . BASE_URL . '/user/kelola');
        exit;
    }

    public function update()
    {
        ensureRole(['admin', 'staf']);
        if ($this->model('User_model')->updateUser($_POST) >= 0) {
            Flasher::setFlash('Pengguna', 'berhasil diperbarui', 'success');
        }
        header('Location: ' . BASE_URL . '/user/kelola');
        exit;
    }

    public function updatePasswordUser()
    {
        ensureRole(['admin', 'staf']);
        if ($this->model('User_model')->updatePasswordByAdmin($_POST) > 0) {
            Flasher::setFlash('Password pengguna', 'berhasil diubah', 'success');
        }
        header('Location: ' . BASE_URL . '/user/kelola');
        exit;
    }

    public function destroy($id)
    {
        ensureRole(['admin', 'staf']);
        if ((int)$id === (int)($_SESSION['user_id'] ?? 0)) {
            Flasher::setFlash('Pengguna', 'tidak bisa menghapus akun yang sedang digunakan.', 'error');
            header('Location: ' . BASE_URL . '/user/kelola');
            exit;
        }
        if ($this->model('User_model')->deleteUser((int)$id) > 0) {
            Flasher::setFlash('Pengguna', 'berhasil dihapus', 'success');
        }
        header('Location: ' . BASE_URL . '/user/kelola');
        exit;
    }
}

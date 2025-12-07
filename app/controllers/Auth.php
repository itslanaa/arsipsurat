<?php

class Auth extends Controller {

    // Menampilkan halaman login
    public function index()
    {
        // Jika sudah ada sesi login, langsung arahkan ke dashboard
        if (isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $data['judul'] = 'Login';
        // Hanya memuat view login, tanpa header/sidebar
        $this->view('auth/login', $data);
    }

    // Memproses data login dari form
    public function prosesLogin()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $userModel = $this->model('User_model');
        $user = $userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];

            $userModel->logLoginAttempt($user['id'], 'success'); // Catat login sukses
            $redirect = '/dashboard';
            if (in_array($user['role'], ['camat', 'sekcam', 'unit'], true)) {
                $redirect = '/suratmasuk';
            }
            header('Location: ' . BASE_URL . $redirect);
            exit;
        } else {
            // Login gagal
            if ($user) {
                $userModel->logLoginAttempt($user['id'], 'failed'); // Catat login gagal jika user ada
            }
            Flasher::setFlash('Username atau Password', 'salah', 'error');
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
    }

    // Proses logout
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header('Location: ' . BASE_URL . '/auth');
        exit;
    }
}

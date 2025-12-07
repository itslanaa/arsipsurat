<?php

class User_model {
    private $table = 'users';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getUserByUsername($username)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE username = :username');
        $this->db->bind('username', $username);
        return $this->db->single();
    }

    public function getAll()
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY id ASC');
        return $this->db->resultSet();
    }

    public function logLoginAttempt($userId, $status)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $query = "INSERT INTO login_history (id_user, ip_address, user_agent, status) VALUES (:id_user, :ip, :ua, :status)";
        $this->db->query($query);
        $this->db->bind('id_user', $userId);
        $this->db->bind('ip', $ip);
        $this->db->bind('ua', $userAgent);
        $this->db->bind('status', $status);
        $this->db->execute();
    }

    public function createUser(array $data)
    {
        if (($data['password_baru'] ?? '') === '' || ($data['password_baru'] !== ($data['konfirmasi_password'] ?? ''))) {
            Flasher::setFlash('Password', 'konfirmasi tidak sesuai', 'error');
            return 0;
        }

        $hash = password_hash($data['password_baru'], PASSWORD_DEFAULT);
        $this->db->query("INSERT INTO {$this->table} (nama_lengkap, username, password, role) VALUES (:nama, :username, :pass, :role)");
        $this->db->bind('nama', trim($data['nama_lengkap'] ?? ''));
        $this->db->bind('username', trim($data['username'] ?? ''));
        $this->db->bind('pass', $hash);
        $this->db->bind('role', $data['role'] ?? 'staf');
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateUser(array $data)
    {
        $this->db->query("UPDATE {$this->table} SET nama_lengkap = :nama, username = :username, role = :role WHERE id = :id");
        $this->db->bind('nama', trim($data['nama_lengkap'] ?? ''));
        $this->db->bind('username', trim($data['username'] ?? ''));
        $this->db->bind('role', $data['role'] ?? 'staf');
        $this->db->bind('id', (int)($data['id'] ?? 0));
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updatePasswordByAdmin(array $data)
    {
        if (($data['password_baru'] ?? '') === '' || ($data['password_baru'] !== ($data['konfirmasi_password'] ?? ''))) {
            Flasher::setFlash('Password', 'konfirmasi tidak sesuai', 'error');
            return 0;
        }
        $hash = password_hash($data['password_baru'], PASSWORD_DEFAULT);
        $this->db->query("UPDATE {$this->table} SET password = :pass WHERE id = :id");
        $this->db->bind('pass', $hash);
        $this->db->bind('id', (int)$data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteUser(int $id)
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getLoginHistory($userId)
    {
        $this->db->query("SELECT * FROM login_history WHERE id_user = :id_user ORDER BY login_time DESC LIMIT 20");
        $this->db->bind('id_user', $userId);
        return $this->db->resultSet();
    }

    public function ubahPassword($data)
    {
        $user = $this->getUserById($_SESSION['user_id']);

        // Verifikasi password lama
        if (!password_verify($data['password_lama'], $user['password'])) {
            Flasher::setFlash('Password lama', 'tidak sesuai', 'error');
            return 0;
        }

        // Cek konfirmasi password baru
        if ($data['password_baru'] !== $data['konfirmasi_password']) {
            Flasher::setFlash('Konfirmasi password baru', 'tidak cocok', 'error');
            return 0;
        }

        // Hash password baru dan update ke database
        $passwordHash = password_hash($data['password_baru'], PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('password', $passwordHash);
        $this->db->bind('id', $_SESSION['user_id']);
        $this->db->execute();
        
        return $this->db->rowCount();
    }
}

<?php

class AdminAuthController extends BaseController {
    private $adminModel;

    public function __construct() {
        $this->adminModel = $this->model('AdminModel');
    }

    public function index() {
        // Jika sudah login, redirect ke dashboard
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header('Location: ' . BASEURL . '/admin/home');
            exit;
        }

        $data = [
            'title' => 'Login - Admin Panel',
        ];

        $this->view('admin/auth/login', $data);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/admin/auth');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validasi input kosong
        if (empty($username) || empty($password)) {
            $data = [
                'title' => 'Login - Admin Panel',
                'error' => 'Username dan password harus diisi!'
            ];
            $this->view('admin/auth/login', $data);
            return;
        }

        // Cek kredensial ke database
        $admin = $this->adminModel->getByUsername($username);

        if ($admin && password_verify($password, $admin['password'])) {
            // Login berhasil
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_email'] = $admin['email'];
            
            // Update last login
            $this->adminModel->updateLastLogin($admin['id']);

            // Redirect ke dashboard
            header('Location: ' . BASEURL . '/admin/home');
            exit;
        } else {
            // Login gagal
            $data = [
                'title' => 'Login - Admin Panel',
                'error' => 'Username atau password salah!'
            ];
            $this->view('admin/auth/login', $data);
        }
    }

    public function logout() {
        // Hapus semua session
        session_unset();
        session_destroy();

        // Redirect ke halaman login dengan pesan sukses
        session_start();
        $_SESSION['logout_success'] = 'Anda berhasil logout.';
        
        header('Location: ' . BASEURL . '/admin/auth');
        exit;
    }
}
<?php

class AdminUserController extends BaseController {
    private $adminModel;

    public function __construct() {
        AuthMiddleware::checkAuth();
        $this->adminModel = $this->model('AdminModel');
    }

    public function index($page = null) {
        // Flash messages
        $success_message = '';
        $error_message = '';
        if (isset($_SESSION['success_message'])) {
            $success_message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            $error_message = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        }

        $page = $page ? (int)$page : 1;
        $page = $page < 1 ? 1 : $page;
        $limit = 6;
        $offset = ($page - 1) * $limit;
        $search = '';

        $users = $this->adminModel->getAll($limit, $offset, $search);
        $totalUsers = $this->adminModel->countAll($search);
        $totalPages = $totalUsers > 0 ? ceil($totalUsers / $limit) : 1;

        if ($totalPages > 0 && $page > $totalPages) {
            header('Location: ' . BASEURL . '/admin/users/page/' . $totalPages);
            exit;
        }

        $data = [
            'title' => 'Kelola Admin',
            'users' => $users,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_users' => $totalUsers,
            'success_message' => $success_message,
            'error_message' => $error_message
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/users/index', $data);
        $this->view('templates/admin/footer');
    }

    public function search($search = '', $page = null) {
        $page = $page ? (int)$page : 1;
        $page = $page < 1 ? 1 : $page;
        $limit = 6;
        $offset = ($page - 1) * $limit;

        $users = $this->adminModel->getAll($limit, $offset, $search);
        $totalUsers = $this->adminModel->countAll($search);
        $totalPages = $totalUsers > 0 ? ceil($totalUsers / $limit) : 1;

        if ($totalPages > 0 && $page > $totalPages) {
            header('Location: ' . BASEURL . '/admin/users/search/' . urlencode($search) . '/page/' . $totalPages);
            exit;
        }

        $data = [
            'title' => 'Kelola Admin',
            'users' => $users,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_users' => $totalUsers,
            'search' => $search
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/users/index', $data);
        $this->view('templates/admin/footer');
    }

    public function add() {
        $data = [
            'title' => 'Tambah Admin'
        ];
        $this->view('templates/admin/header', $data);
        $this->view('admin/users/add', $data);
        $this->view('templates/admin/footer');
    }

    public function add_user() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $full_name = trim($_POST['full_name'] ?? '');
        $status = trim($_POST['status'] ?? 'active');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Basic validations
        if ($username === '' || $email === '' || $full_name === '' || $password === '') {
            $_SESSION['error_message'] = 'Semua field wajib diisi.';
            header('Location: ' . BASEURL . '/admin/users/add');
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = 'Format email tidak valid.';
            header('Location: ' . BASEURL . '/admin/users/add');
            exit;
        }
        if ($password !== $confirm_password) {
            $_SESSION['error_message'] = 'Konfirmasi password tidak cocok.';
            header('Location: ' . BASEURL . '/admin/users/add');
            exit;
        }
        if ($this->adminModel->isUsernameTaken($username)) {
            $_SESSION['error_message'] = 'Username sudah digunakan.';
            header('Location: ' . BASEURL . '/admin/users/add');
            exit;
        }
        if ($this->adminModel->isEmailTaken($email)) {
            $_SESSION['error_message'] = 'Email sudah digunakan.';
            header('Location: ' . BASEURL . '/admin/users/add');
            exit;
        }

        $created = $this->adminModel->create([
            'username' => $username,
            'email' => $email,
            'full_name' => $full_name,
            'status' => $status,
            'password' => $password
        ]);

        if ($created) {
            $_SESSION['success_message'] = 'Admin baru berhasil ditambahkan.';
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        $_SESSION['error_message'] = 'Gagal menambahkan admin.';
        header('Location: ' . BASEURL . '/admin/users/add');
        exit;
    }

    public function edit($id = null) {
        if ($id === null) {
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        $user = $this->adminModel->getById($id);
        if (!$user) {
            $_SESSION['error_message'] = 'Admin tidak ditemukan.';
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        $data = [
            'title' => 'Edit Admin',
            'user' => $user
        ];
        $this->view('templates/admin/header', $data);
        $this->view('admin/users/edit', $data);
        $this->view('templates/admin/footer');
    }

    public function edit_user() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $full_name = trim($_POST['full_name'] ?? '');
        $status = trim($_POST['status'] ?? 'active');

        if ($id <= 0 || $username === '' || $email === '' || $full_name === '') {
            $_SESSION['error_message'] = 'Semua field wajib diisi.';
            header('Location: ' . BASEURL . '/admin/users/edit/' . $id);
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = 'Format email tidak valid.';
            header('Location: ' . BASEURL . '/admin/users/edit/' . $id);
            exit;
        }
        if ($this->adminModel->isUsernameTaken($username, $id)) {
            $_SESSION['error_message'] = 'Username sudah digunakan.';
            header('Location: ' . BASEURL . '/admin/users/edit/' . $id);
            exit;
        }
        if ($this->adminModel->isEmailTaken($email, $id)) {
            $_SESSION['error_message'] = 'Email sudah digunakan.';
            header('Location: ' . BASEURL . '/admin/users/edit/' . $id);
            exit;
        }

        $updated = $this->adminModel->update([
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'full_name' => $full_name,
            'status' => $status
        ]);

        if ($updated) {
            $_SESSION['success_message'] = 'Data admin berhasil diperbarui.';
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        $_SESSION['error_message'] = 'Gagal memperbarui data admin.';
        header('Location: ' . BASEURL . '/admin/users/edit/' . $id);
        exit;
    }

    public function reset_password() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($id <= 0 || $password === '') {
            $_SESSION['error_message'] = 'Password wajib diisi.';
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }
        if ($password !== $confirm_password) {
            $_SESSION['error_message'] = 'Konfirmasi password tidak cocok.';
            header('Location: ' . BASEURL . '/admin/users/edit/' . $id);
            exit;
        }

        $updated = $this->adminModel->updatePassword($id, $password);
        if ($updated) {
            $_SESSION['success_message'] = 'Password berhasil direset.';
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        $_SESSION['error_message'] = 'Gagal mereset password.';
        header('Location: ' . BASEURL . '/admin/users/edit/' . $id);
        exit;
    }

    public function delete_user() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error_message'] = 'Data tidak valid.';
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        // Prevent deleting self
        if ($id === (int)AuthMiddleware::getAdminId()) {
            $_SESSION['error_message'] = 'Anda tidak dapat menghapus akun Anda sendiri.';
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        $deleted = $this->adminModel->delete($id);
        if ($deleted) {
            $_SESSION['success_message'] = 'Admin berhasil dihapus.';
            header('Location: ' . BASEURL . '/admin/users');
            exit;
        }

        $_SESSION['error_message'] = 'Gagal menghapus admin.';
        header('Location: ' . BASEURL . '/admin/users');
        exit;
    }
}
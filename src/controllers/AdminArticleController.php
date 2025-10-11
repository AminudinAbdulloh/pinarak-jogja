<?php

class AdminArticleController extends BaseController{
    private $articleModel;

    public function __construct() {
        $this->articleModel = $this->model('ArticleModel');
    }

    public function index() {
        $data = [
            'title' => 'Dashboard - Article',
            'articles' => $this->articleModel->getAll()
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/article/index', $data);
        $this->view('templates/admin/footer');
    }

    public function add() {
        $data = [
            'title' => 'Dashboard - Add Article',
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/article/add');
        $this->view('templates/admin/footer');
    }

    public function add_article() {
        try {
            // Handle upload gambar
            $imagePath = '';
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
                $uploadDir = '../public/uploads/articles/';
                
                // Buat folder jika belum ada
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Validasi tipe file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $fileType = $_FILES['featured_image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error_message'] = 'Format file tidak valid. Hanya JPG, PNG, dan WebP yang diperbolehkan.';
                    header('Location: ' . BASEURL . '/admin/article');
                    exit;
                }
                
                // Validasi ukuran file (max 5MB)
                $maxSize = 5 * 1024 * 1024; // 5MB dalam bytes
                if ($_FILES['featured_image']['size'] > $maxSize) {
                    $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    header('Location: ' . BASEURL . '/admin/article');
                    exit;
                }
                
                $fileName = time() . '_' . $_FILES['featured_image']['name'];
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/articles/' . $fileName;
                } else {
                    $_SESSION['error_message'] = 'Gagal mengupload gambar.';
                    header('Location: ' . BASEURL . '/admin/article');
                    exit;
                }
            }
            
            // Siapkan data untuk disimpan
            $articleData = [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'excerpt' => $_POST['excerpt'],
                'image' => $imagePath,
                'status' => $_POST['status'] ?? 'draft',
                'author_id' => AuthMiddleware::getAdminId()
            ];
            
            // Simpan ke database
            $result = $this->articleModel->add_article($articleData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Artikel berhasil ditambahkan!';
            } else {
                $_SESSION['error_message'] = 'Gagal menambahkan artikel. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        // Redirect ke halaman index
        header('Location: ' . BASEURL . '/admin/article');
        exit;
    }
}
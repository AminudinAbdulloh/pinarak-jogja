<?php

class AdminArticleController extends BaseController{
    private $articleModel;

    public function __construct() {
        AuthMiddleware::checkAuth();
        $this->articleModel = $this->model('ArticleModel');
    }

    public function index($page = null) {
        // Ambil notifikasi dari session jika ada
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
        
        // Ambil parameter pagination
        $page = $page ? (int)$page : 1;
        $page = $page < 1 ? 1 : $page;
        
        $limit = 6; // Jumlah data per halaman
        $offset = ($page - 1) * $limit;
        
        $search = ''; // Tidak ada search di method index
        
        // Ambil data dengan pagination
        $articles = $this->articleModel->getAllWithPagination($limit, $offset, $search);
        
        // Hitung total untuk pagination
        $totalArticles = $this->articleModel->countAll($search);
        $totalPages = ceil($totalArticles / $limit);
        
        // Pastikan tidak ada halaman kosong
        if ($totalPages > 0 && $page > $totalPages) {
            header('Location: ' . BASEURL . '/admin/article/page/' . $totalPages);
            exit;
        }
        
        $data = [
            'title' => 'Dashboard - Article',
            'articles' => $articles,
            'success_message' => $success_message,
            'error_message' => $error_message,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_articles' => $totalArticles,
            'search' => $search
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/article/index', $data);
        $this->view('templates/admin/footer');
    }

    public function search($search = null, $page = null) {
        // Ambil notifikasi dari session jika ada
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
        
        // Decode search parameter (karena di-encode di URL)
        $search = $search ? urldecode($search) : '';
        
        // Jika search kosong, redirect ke index
        if (empty($search)) {
            header('Location: ' . BASEURL . '/admin/article');
            exit;
        }
        
        // Ambil parameter pagination
        $page = $page ? (int)$page : 1;
        $page = $page < 1 ? 1 : $page;
        
        $limit = 6; // Jumlah data per halaman
        $offset = ($page - 1) * $limit;
        
        // Ambil data dengan pagination dan search
        $articles = $this->articleModel->getAllWithPagination($limit, $offset, $search);
        
        // Hitung total untuk pagination
        $totalArticles = $this->articleModel->countAll($search);
        $totalPages = ceil($totalArticles / $limit);
        
        // Pastikan tidak ada halaman kosong
        if ($totalPages > 0 && $page > $totalPages) {
            $redirectUrl = BASEURL . '/admin/article/search/' . urlencode($search);
            if ($totalPages > 1) {
                $redirectUrl .= '/page/' . $totalPages;
            }
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        $data = [
            'title' => 'Dashboard - Article',
            'articles' => $articles,
            'success_message' => $success_message,
            'error_message' => $error_message,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_articles' => $totalArticles,
            'search' => $search
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

    public function edit($id = null) {
        try {
            // Jika id tidak ada di parameter, coba ambil dari GET
            if ($id === null && isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            if (!$id) {
                $_SESSION['error_message'] = 'ID Artikel tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/article');
                exit;
            }

            // Ambil data dari database
            $article = $this->articleModel->getById($id);

            if (!$article) {
                $_SESSION['error_message'] = 'Artikel tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/article');
                exit;
            }

            $data = [
                'title' => 'Dashboard - Edit Article',
                'article' => $article
            ];

            $this->view('templates/admin/header', $data);
            $this->view('admin/article/edit', $data);
            $this->view('templates/admin/footer');

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            header('Location: ' . BASEURL . '/admin/article');
            exit;
        }
    }

    public function edit_article() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID Artikel tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/article');
                exit;
            }

            // Ambil data lama untuk cek gambar
            $oldArticle = $this->articleModel->getById($id);

            if (!$oldArticle) {
                $_SESSION['error_message'] = 'Artikel tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/article');
                exit;
            }

            // Handle upload gambar baru
            $imagePath = $oldArticle['image']; // Default gunakan gambar lama

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
                    header('Location: ' . BASEURL . '/admin/article/edit?id=' . $id);
                    exit;
                }
                
                // Validasi ukuran file (max 5MB)
                $maxSize = 5 * 1024 * 1024;
                if ($_FILES['featured_image']['size'] > $maxSize) {
                    $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    header('Location: ' . BASEURL . '/admin/article/edit?id=' . $id);
                    exit;
                }
                
                // Upload file baru
                $fileName = time() . '_' . $_FILES['featured_image']['name'];
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $targetPath)) {
                    // Hapus gambar lama jika ada
                    if (!empty($oldArticle['image'])) {
                        $oldImagePath = '../public/' . $oldArticle['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    
                    $imagePath = 'uploads/articles/' . $fileName;
                } else {
                    $_SESSION['error_message'] = 'Gagal mengupload gambar.';
                    header('Location: ' . BASEURL . '/admin/article/edit/' . $id);
                    exit;
                }
            }
            
            // Siapkan data untuk diupdate
            $articleData = [
                'id' => $id,
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'excerpt' => $_POST['excerpt'],
                'image' => $imagePath,
                'status' => $_POST['status'] ?? 'draft'
            ];
            
            // Update ke database
            $result = $this->articleModel->update_article($articleData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Artikel berhasil diperbarui!';
            } else {
                $_SESSION['error_message'] = 'Gagal memperbarui artikel. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/article');
        exit;
    }

    public function delete_article() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID Artikel tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/article');
                exit;
            }

            $result = $this->articleModel->delete_article($id);

            if ($result) {
                $_SESSION['success_message'] = 'Artikel berhasil dihapus.';
            } else {
                $_SESSION['error_message'] = 'Artikel tidak ditemukan atau gagal dihapus.';
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/article');
        exit;
    }
}
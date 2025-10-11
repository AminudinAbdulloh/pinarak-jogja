<?php

class AdminMediaPartnerController extends BaseController{
    private $mediaPartnerModel;

    public function __construct() {
        $this->mediaPartnerModel = $this->model('MediaPartnerModel');
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
        
        // Ambil data media partner dengan pagination
        $media_partners = $this->mediaPartnerModel->getAllWithPagination($limit, $offset, $search);
        
        // Hitung total media partner untuk pagination
        $totalMediaPartners = $this->mediaPartnerModel->countAll($search);
        $totalPages = ceil($totalMediaPartners / $limit);
        
        // Pastikan tidak ada halaman kosong
        if ($totalPages > 0 && $page > $totalPages) {
            header('Location: ' . BASEURL . '/admin/media-partner/page/' . $totalPages);
            exit;
        }

        $data = [
            'title' => 'Dashboard - Media Partner',
            'media_partners' => $media_partners,
            'success_message' => $success_message,
            'error_message' => $error_message,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_media_partners' => $totalMediaPartners,
            'search' => $search
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/media-partner/index', $data);
        $this->view('templates/admin/footer');
    }

    public function add() {
        $data = [
            'title' => 'Dashboard - Add Media Partner',
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/media-partner/add');
        $this->view('templates/admin/footer');
    }

    public function add_media_partner() {
        try {
            // Handle upload gambar
            $imagePath = '';
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
                $uploadDir = '../public/uploads/media-partners/';
                
                // Buat folder jika belum ada
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Validasi tipe file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $fileType = $_FILES['logo']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error_message'] = 'Format file tidak valid. Hanya JPG, PNG, dan WebP yang diperbolehkan.';
                    header('Location: ' . BASEURL . '/admin/media-partner');
                    exit;
                }
                
                // Validasi ukuran file (max 5MB)
                $maxSize = 5 * 1024 * 1024; // 5MB dalam bytes
                if ($_FILES['logo']['size'] > $maxSize) {
                    $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    header('Location: ' . BASEURL . '/admin/media-partner');
                    exit;
                }
                
                $fileName = time() . '_' . $_FILES['logo']['name'];
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/media-partners/' . $fileName;
                } else {
                    $_SESSION['error_message'] = 'Gagal mengupload gambar.';
                    header('Location: ' . BASEURL . '/admin/media-partner');
                    exit;
                }
            }
            
            // Siapkan data untuk disimpan
            $mediaPartnerData = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'website' => $_POST['website'],
                'logo' => $imagePath,
                'author_id' => AuthMiddleware::getAdminId()
            ];
            
            // Simpan ke database
            $result = $this->mediaPartnerModel->add_media_partner($mediaPartnerData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Media Partner berhasil ditambahkan!';
            } else {
                $_SESSION['error_message'] = 'Gagal menambahkan media partner. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        // Redirect ke halaman index media partner
        header('Location: ' . BASEURL . '/admin/media-partner');
        exit;
    }

    public function edit($id) {
        echo "Edit from article = " . $id;
    }
}
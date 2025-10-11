<?php

class AdminMediaPartnerController extends BaseController{
    private $mediaPartnerModel;

    public function __construct() {
        $this->mediaPartnerModel = $this->model('MediaPartnerModel');
    }

    public function index() {
        $data = [
            'title' => 'Dashboard - Media Partner',
            'media_partners' => $this->mediaPartnerModel->getAll()
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
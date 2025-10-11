<?php

class AdminMediaPartnerController extends BaseController{
    private $mediaPartnerModel;

    public function __construct() {
        AuthMiddleware::checkAuth();
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
            header('Location: ' . BASEURL . '/admin/media-partner');
            exit;
        }
        
        // Ambil parameter pagination
        $page = $page ? (int)$page : 1;
        $page = $page < 1 ? 1 : $page;
        
        $limit = 6; // Jumlah data per halaman
        $offset = ($page - 1) * $limit;
        
        // Ambil data media partner dengan pagination dan search
        $media_partners = $this->mediaPartnerModel->getAllWithPagination($limit, $offset, $search);
        
        // Hitung total media partner untuk pagination
        $totalMediaPartners = $this->mediaPartnerModel->countAll($search);
        $totalPages = ceil($totalMediaPartners / $limit);
        
        // Pastikan tidak ada halaman kosong
        if ($totalPages > 0 && $page > $totalPages) {
            $redirectUrl = BASEURL . '/admin/media-partner/search/' . urlencode($search);
            if ($totalPages > 1) {
                $redirectUrl .= '/page/' . $totalPages;
            }
            header('Location: ' . $redirectUrl);
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

    public function edit($id = null) {
        try {
            // Jika id tidak ada di parameter, coba ambil dari GET
            if ($id === null && isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            if (!$id) {
                $_SESSION['error_message'] = 'ID media partner tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/media-partner');
                exit;
            }

            // Ambil data database
            $media_partner = $this->mediaPartnerModel->getById($id);

            if (!$media_partner) {
                $_SESSION['error_message'] = 'Media Partner tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/media-partner');
                exit;
            }

            $data = [
                'title' => 'Dashboard - Edit Media Partner',
                'media_partner' => $media_partner
            ];

            $this->view('templates/admin/header', $data);
            $this->view('admin/media-partner/edit', $data);
            $this->view('templates/admin/footer');

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            header('Location: ' . BASEURL . '/admin/media-partner');
            exit;
        }
    }

    public function edit_media_partner() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID media partner tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/media-partner');
                exit;
            }

            // Ambil data lama untuk cek gambar
            $oldMediaPartner = $this->mediaPartnerModel->getById($id);

            if (!$oldMediaPartner) {
                $_SESSION['error_message'] = 'Media Partner tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/media-partner');
                exit;
            }

            // Handle upload gambar baru
            $imagePath = $oldMediaPartner['logo']; // Default gunakan gambar lama

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
                    header('Location: ' . BASEURL . '/admin/media-partner/edit/' . $id);
                    exit;
                }
                
                // Validasi ukuran file (max 5MB)
                $maxSize = 5 * 1024 * 1024;
                if ($_FILES['logo']['size'] > $maxSize) {
                    $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    header('Location: ' . BASEURL . '/admin/media-partner/edit/' . $id);
                    exit;
                }
                
                // Upload file baru
                $fileName = time() . '_' . $_FILES['logo']['name'];
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
                    // Hapus gambar lama jika ada
                    if (!empty($oldMediaPartner['logo'])) {
                        $oldImagePath = '../public/' . $oldMediaPartner['logo'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    
                    $imagePath = 'uploads/media-partners/' . $fileName;
                } else {
                    $_SESSION['error_message'] = 'Gagal mengupload gambar.';
                    header('Location: ' . BASEURL . '/admin/media-partner/edit?id=' . $id);
                    exit;
                }
            }
            
            // Siapkan data untuk diupdate
            $mediaPartnerData = [
                'id' => $id,
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'website' => $_POST['website'],
                'logo' => $imagePath,
            ];
            
            // Update ke database
            $result = $this->mediaPartnerModel->update_media_partner($mediaPartnerData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Media Partner berhasil diperbarui!';
            } else {
                $_SESSION['error_message'] = 'Gagal memperbarui media partner. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/media-partner');
        exit;
    }

    public function delete_media_partner() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID media partner tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/media-partner');
                exit;
            }

            $result = $this->mediaPartnerModel->delete_media_partner($id);

            if ($result) {
                $_SESSION['success_message'] = 'Media Partner berhasil dihapus.';
            } else {
                $_SESSION['error_message'] = 'Media Partner tidak ditemukan atau gagal dihapus.';
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/media-partner');
        exit;
    }
}
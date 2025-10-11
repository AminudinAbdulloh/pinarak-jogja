<?php

class AdminProfileController extends BaseController{
    
    private $profileModel;
    private $youtubeModel;
    
    public function __construct() {
        AuthMiddleware::checkAuth();
        $this->profileModel = $this->model('ProfileModel');
        $this->youtubeModel = $this->model('YoutubeModel');
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
        
        // Ambil data profiles dengan pagination
        $profiles = $this->profileModel->getAll($limit, $offset, $search);
        
        // Hitung total profiles untuk pagination
        $totalProfiles = $this->profileModel->countAll($search);
        $totalPages = ceil($totalProfiles / $limit);
        
        // Pastikan tidak ada halaman kosong
        if ($totalPages > 0 && $page > $totalPages) {
            header('Location: ' . BASEURL . '/admin/profile/page/' . $totalPages);
            exit;
        }

        $data = [
            'title' => 'Dashboard - Profile',
            'profiles' => $profiles,
            'youtube_links' => $this->youtubeModel->getAll(),
            'success_message' => $success_message,
            'error_message' => $error_message,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_profiles' => $totalProfiles,
            'search' => $search
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/profile/index', $data);
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
        
        // Decode search parameter
        $search = $search ? urldecode($search) : '';
        
        // Jika search kosong, redirect ke index
        if (empty($search)) {
            header('Location: ' . BASEURL . '/admin/profile');
            exit;
        }
        
        // Ambil parameter pagination
        $page = $page ? (int)$page : 1;
        $page = $page < 1 ? 1 : $page;
        
        $limit = 6;
        $offset = ($page - 1) * $limit;
        
        // Ambil data profiles dengan pagination dan search
        $profiles = $this->profileModel->getAll($limit, $offset, $search);
        
        // Hitung total profiles untuk pagination
        $totalProfiles = $this->profileModel->countAll($search);
        $totalPages = ceil($totalProfiles / $limit);
        
        // Pastikan tidak ada halaman kosong
        if ($totalPages > 0 && $page > $totalPages) {
            $redirectUrl = BASEURL . '/admin/profile/search/' . urlencode($search);
            if ($totalPages > 1) {
                $redirectUrl .= '/page/' . $totalPages;
            }
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        $data = [
            'title' => 'Dashboard - Profile',
            'profiles' => $profiles,
            'youtube_links' => $this->youtubeModel->getAll(),
            'success_message' => $success_message,
            'error_message' => $error_message,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_profiles' => $totalProfiles,
            'search' => $search
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/profile/index', $data);
        $this->view('templates/admin/footer');
    }

    // YOUTUBE LINK METHODS
    public function add_youtube_link() {
        $data = [
            'title' => 'Dashboard - Add Youtube Link'
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/profile/add_youtube_link', $data);
        $this->view('templates/admin/footer');
    }

    public function added_youtube_link() {
        try {
            $yt_link_data = [
                'title' => $_POST['title'],
                'url' => $_POST['url'],
                'author_id' => AuthMiddleware::getAdminId()
            ];

            $result = $this->youtubeModel->add_youtube_link($yt_link_data);

            if ($result) {
                $_SESSION['success_message'] = 'Link Youtube berhasil ditambahkan!';
            } else {
                $_SESSION['error_message'] = 'Gagal menambahkan link youtube. Silakan coba lagi.';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }

        header('Location: ' . BASEURL . '/admin/profile');
        exit;
    }

    public function edit_youtube_link($id = null) {
        try {
            if ($id === null && isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            if (!$id) {
                $_SESSION['error_message'] = 'ID link youtube tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/profile');
                exit;
            }

            $youtube_link = $this->youtubeModel->getById($id);

            if (!$youtube_link) {
                $_SESSION['error_message'] = 'Url Youtube tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/profile');
                exit;
            }

            $data = [
                'title' => 'Dashboard - Edit Youtube Link',
                'youtube_link' => $youtube_link
            ];

            $this->view('templates/admin/header', $data);
            $this->view('admin/profile/edit_youtube_link', $data);
            $this->view('templates/admin/footer');

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            header('Location: ' . BASEURL . '/admin/profile');
            exit;
        }
    }

    public function edited_youtube_link() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID Link Youtube tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/profile');
                exit;
            }

            $oldYoutubeLink = $this->youtubeModel->getById($id);

            if (!$oldYoutubeLink) {
                $_SESSION['error_message'] = 'Link Youtube tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/profile');
                exit;
            }
            
            // Siapkan data untuk diupdate
            $youtubeLinkData = [
                'id' => $id,
                'title' => $_POST['title'],
                'url' => $_POST['url'],
                'status' => $_POST['status'] ?? 'draft'
            ];
            
            $result = $this->youtubeModel->update_youtube_link($youtubeLinkData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Link Youtube berhasil diperbarui!';
            } else {
                $_SESSION['error_message'] = 'Gagal memperbarui Link Youtube. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/profile');
        exit;
    }

    public function delete_youtube_link() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID youtube link tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/profile');
                exit;
            }

            $result = $this->youtubeModel->delete_youtube_link($id);

            if ($result) {
                $_SESSION['success_message'] = 'Link Youtube berhasil dihapus.';
            } else {
                $_SESSION['error_message'] = 'Link Youtube tidak ditemukan atau gagal dihapus.';
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/profile');
        exit;
    }

    // PROFILE METHODS
    public function add() {
        $data = [
            'title' => 'Dashboard - Add Profile',
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/profile/add');
        $this->view('templates/admin/footer');
    }

    public function add_profile() {
        try {
            // Handle upload foto
            $photoPath = '';
            if (isset($_FILES['pass_photo']) && $_FILES['pass_photo']['error'] === 0) {
                $uploadDir = '../public/uploads/profiles/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Validasi tipe file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $fileType = $_FILES['pass_photo']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error_message'] = 'Format file tidak valid. Hanya JPG, PNG, dan WebP yang diperbolehkan.';
                    header('Location: ' . BASEURL . '/admin/profile');
                    exit;
                }
                
                // Validasi ukuran file (max 5MB)
                $maxSize = 5 * 1024 * 1024;
                if ($_FILES['pass_photo']['size'] > $maxSize) {
                    $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    header('Location: ' . BASEURL . '/admin/profile');
                    exit;
                }
                
                $fileName = time() . '_' . $_FILES['pass_photo']['name'];
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['pass_photo']['tmp_name'], $targetPath)) {
                    $photoPath = 'uploads/profiles/' . $fileName;
                } else {
                    $_SESSION['error_message'] = 'Gagal mengupload foto.';
                    header('Location: ' . BASEURL . '/admin/profile');
                    exit;
                }
            }
            
            // Siapkan data untuk disimpan
            $profileData = [
                'full_name' => $_POST['full_name'],
                'pass_photo' => $photoPath,
                'position' => $_POST['position'],
                'email' => $_POST['email'] ?? null,
                'phone_number' => $_POST['phone_number'] ?? null,
                'linkedin' => $_POST['linkedin'] ?? null,
                'instagram' => $_POST['instagram'] ?? null,
                'facebook' => $_POST['facebook'] ?? null,
                'tiktok' => $_POST['tiktok'] ?? null,
                'display_order' => $_POST['display_order'] ?? 0,
                'status' => $_POST['status'] ?? 'active',
                'author_id' => AuthMiddleware::getAdminId()
            ];
            
            // Simpan ke database
            $result = $this->profileModel->add_profile($profileData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Profile berhasil ditambahkan!';
            } else {
                $_SESSION['error_message'] = 'Gagal menambahkan profile. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/profile');
        exit;
    }

    public function edit($id = null) {
        try {
            if ($id === null && isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            if (!$id) {
                $_SESSION['error_message'] = 'ID profile tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/profile');
                exit;
            }

            $profile = $this->profileModel->getById($id);

            if (!$profile) {
                $_SESSION['error_message'] = 'Profile tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/profile');
                exit;
            }

            $data = [
                'title' => 'Dashboard - Edit Profile',
                'profile' => $profile
            ];

            $this->view('templates/admin/header', $data);
            $this->view('admin/profile/edit', $data);
            $this->view('templates/admin/footer');

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            header('Location: ' . BASEURL . '/admin/profile');
            exit;
        }
    }

    public function edit_profile() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID profile tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/profile');
                exit;
            }

            $oldProfile = $this->profileModel->getById($id);

            if (!$oldProfile) {
                $_SESSION['error_message'] = 'Profile tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/profile');
                exit;
            }

            // Handle upload foto baru
            $photoPath = $oldProfile['pass_photo'];

            if (isset($_FILES['pass_photo']) && $_FILES['pass_photo']['error'] === 0) {
                $uploadDir = '../public/uploads/profiles/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $fileType = $_FILES['pass_photo']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error_message'] = 'Format file tidak valid. Hanya JPG, PNG, dan WebP yang diperbolehkan.';
                    header('Location: ' . BASEURL . '/admin/profile/edit?id=' . $id);
                    exit;
                }
                
                $maxSize = 5 * 1024 * 1024;
                if ($_FILES['pass_photo']['size'] > $maxSize) {
                    $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    header('Location: ' . BASEURL . '/admin/profile/edit?id=' . $id);
                    exit;
                }
                
                $fileName = time() . '_' . $_FILES['pass_photo']['name'];
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['pass_photo']['tmp_name'], $targetPath)) {
                    // Hapus foto lama jika ada
                    if (!empty($oldProfile['pass_photo'])) {
                        $oldPhotoPath = '../public/' . $oldProfile['pass_photo'];
                        if (file_exists($oldPhotoPath)) {
                            unlink($oldPhotoPath);
                        }
                    }
                    
                    $photoPath = 'uploads/profiles/' . $fileName;
                } else {
                    $_SESSION['error_message'] = 'Gagal mengupload foto.';
                    header('Location: ' . BASEURL . '/admin/profile/edit?id=' . $id);
                    exit;
                }
            }
            
            // Siapkan data untuk diupdate
            $profileData = [
                'id' => $id,
                'full_name' => $_POST['full_name'],
                'pass_photo' => $photoPath,
                'position' => $_POST['position'],
                'email' => $_POST['email'] ?? null,
                'phone_number' => $_POST['phone_number'] ?? null,
                'linkedin' => $_POST['linkedin'] ?? null,
                'instagram' => $_POST['instagram'] ?? null,
                'facebook' => $_POST['facebook'] ?? null,
                'tiktok' => $_POST['tiktok'] ?? null,
                'display_order' => $_POST['display_order'] ?? 0,
                'status' => $_POST['status'] ?? 'active'
            ];
            
            $result = $this->profileModel->update_profile($profileData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Profile berhasil diperbarui!';
            } else {
                $_SESSION['error_message'] = 'Gagal memperbarui profile. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/profile');
        exit;
    }

    public function delete_profile() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID profile tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/profile');
                exit;
            }

            $result = $this->profileModel->delete_profile($id);

            if ($result) {
                $_SESSION['success_message'] = 'Profile berhasil dihapus.';
            } else {
                $_SESSION['error_message'] = 'Profile tidak ditemukan atau gagal dihapus.';
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/profile');
        exit;
    }
}
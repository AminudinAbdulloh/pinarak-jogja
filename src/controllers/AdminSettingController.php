<?php

class AdminSettingController extends BaseController {
    private $settingModel;

    public function __construct() {
        AuthMiddleware::checkAuth();
        $this->settingModel = $this->model('SettingModel');
    }

    public function index() {
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

        $settings = $this->settingModel->getSettings();

        // Jika tidak ada pengaturan, buat default
        if (!$settings) {
            $this->settingModel->createDefaultSettings();
            $settings = $this->settingModel->getSettings();
        }

        $data = [
            'title' => 'Dashboard - Pengaturan',
            'settings' => $settings,
            'success_message' => $success_message,
            'error_message' => $error_message
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/setting/index', $data);
        $this->view('templates/admin/footer');
    }

    public function edit($id = null) {
        try {
            if ($id === null && isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            if (!$id) {
                $_SESSION['error_message'] = 'ID pengaturan tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/setting');
                exit;
            }

            $settings = $this->settingModel->getById($id);

            if (!$settings) {
                $_SESSION['error_message'] = 'Pengaturan tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/setting');
                exit;
            }

            $data = [
                'title' => 'Dashboard - Edit Pengaturan',
                'settings' => $settings
            ];

            $this->view('templates/admin/header', $data);
            $this->view('admin/setting/edit', $data);
            $this->view('templates/admin/footer');

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            header('Location: ' . BASEURL . '/admin/setting');
            exit;
        }
    }

    public function edit_setting() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID pengaturan tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/setting');
                exit;
            }

            $oldSettings = $this->settingModel->getById($id);

            if (!$oldSettings) {
                $_SESSION['error_message'] = 'Pengaturan tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/setting');
                exit;
            }

            $uploadDir = '../public/uploads/settings/';
            $bannerDir = '../public/uploads/banners/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (!is_dir($bannerDir)) {
                mkdir($bannerDir, 0755, true);
            }

            // Handle Logo Pinarak upload
            $logoPinarak = $oldSettings['logo_pinarak'];
            if (isset($_FILES['logo_pinarak']) && $_FILES['logo_pinarak']['error'] === UPLOAD_ERR_OK) {
                $fileName = time() . '_pinarak_' . basename($_FILES['logo_pinarak']['name']);
                $targetFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo_pinarak']['tmp_name'], $targetFile)) {
                    // Hapus logo lama jika ada
                    if ($oldSettings['logo_pinarak'] && file_exists('../public/' . $oldSettings['logo_pinarak'])) {
                        unlink('../public/' . $oldSettings['logo_pinarak']);
                    }
                    $logoPinarak = 'uploads/settings/' . $fileName;
                }
            }

            // Handle Logo Dinpar upload
            $logoDinpar = $oldSettings['logo_dinpar'];
            if (isset($_FILES['logo_dinpar']) && $_FILES['logo_dinpar']['error'] === UPLOAD_ERR_OK) {
                $fileName = time() . '_dinpar_' . basename($_FILES['logo_dinpar']['name']);
                $targetFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo_dinpar']['tmp_name'], $targetFile)) {
                    // Hapus logo lama jika ada
                    if ($oldSettings['logo_dinpar'] && file_exists('../public/' . $oldSettings['logo_dinpar'])) {
                        unlink('../public/' . $oldSettings['logo_dinpar']);
                    }
                    $logoDinpar = 'uploads/settings/' . $fileName;
                }
            }

            // Handle Banner uploads
            $banners = is_array($oldSettings['banner']) ? $oldSettings['banner'] : [];
            
            if (isset($_FILES['banners']) && !empty($_FILES['banners']['name'][0])) {
                $uploadedBanners = [];
                
                foreach ($_FILES['banners']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['banners']['error'][$key] === UPLOAD_ERR_OK) {
                        $fileName = time() . '_banner_' . $key . '_' . basename($_FILES['banners']['name'][$key]);
                        $targetFile = $bannerDir . $fileName;
                        
                        if (move_uploaded_file($tmp_name, $targetFile)) {
                            $uploadedBanners[] = 'uploads/banners/' . $fileName;
                        }
                    }
                }
                
                // Gabungkan dengan banner lama jika ada
                if (!empty($uploadedBanners)) {
                    $banners = array_merge($banners, $uploadedBanners);
                }
            }

            // Handle banner deletion
            if (isset($_POST['delete_banners']) && is_array($_POST['delete_banners'])) {
                foreach ($_POST['delete_banners'] as $bannerToDelete) {
                    // Hapus dari array
                    $banners = array_filter($banners, function($banner) use ($bannerToDelete) {
                        return $banner !== $bannerToDelete;
                    });
                    
                    // Hapus file fisik
                    if (file_exists('../public/' . $bannerToDelete)) {
                        unlink('../public/' . $bannerToDelete);
                    }
                }
                $banners = array_values($banners); // Re-index array
            }

            // Siapkan data untuk diupdate
            $settingData = [
                'id' => $id,
                'logo_pinarak' => $logoPinarak,
                'logo_dinpar' => $logoDinpar,
                'banner' => json_encode($banners),
                'copyright' => $_POST['copyright'] ?? $oldSettings['copyright']
            ];

            $result = $this->settingModel->updateSettings($settingData);

            if ($result) {
                $_SESSION['success_message'] = 'Pengaturan berhasil diperbarui!';
            } else {
                $_SESSION['error_message'] = 'Gagal memperbarui pengaturan. Silakan coba lagi.';
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }

        header('Location: ' . BASEURL . '/admin/setting');
        exit;
    }
}
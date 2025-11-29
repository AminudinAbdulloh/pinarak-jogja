<?php

class AdminTouristObjectController extends BaseController {

    private $touristObjectModel;

    public function __construct() {
        AuthMiddleware::checkAuth();
        $this->touristObjectModel = $this->model('TouristObjectModel');
    }

    public function index($page = null) {
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
        
        $touristObjects = $this->touristObjectModel->getAll($limit, $offset, $search);
        $totalObjects = $this->touristObjectModel->countAll($search);
        $totalPages = ceil($totalObjects / $limit);
        
        if ($totalPages > 0 && $page > $totalPages) {
            header('Location: ' . BASEURL . '/admin/tourist-object/page/' . $totalPages);
            exit;
        }
        
        $data = [
            'title' => 'Dashboard - Tourist Objects',
            'touristObjects' => $touristObjects,
            'success_message' => $success_message,
            'error_message' => $error_message,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_objects' => $totalObjects,
            'search' => $search
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/tourist-object/index', $data);
        $this->view('templates/admin/footer');
    }

    public function search($search = null, $page = null) {
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
        
        $search = $search ? urldecode($search) : '';
        
        if (empty($search)) {
            header('Location: ' . BASEURL . '/admin/tourist-object');
            exit;
        }
        
        $page = $page ? (int)$page : 1;
        $page = $page < 1 ? 1 : $page;
        
        $limit = 6;
        $offset = ($page - 1) * $limit;
        
        $touristObjects = $this->touristObjectModel->getAll($limit, $offset, $search);
        $totalObjects = $this->touristObjectModel->countAll($search);
        $totalPages = ceil($totalObjects / $limit);
        
        if ($totalPages > 0 && $page > $totalPages) {
            $redirectUrl = BASEURL . '/admin/tourist-object/search/' . urlencode($search);
            if ($totalPages > 1) {
                $redirectUrl .= '/page/' . $totalPages;
            }
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        $data = [
            'title' => 'Dashboard - Tourist Objects',
            'touristObjects' => $touristObjects,
            'success_message' => $success_message,
            'error_message' => $error_message,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_objects' => $totalObjects,
            'search' => $search
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/tourist-object/index', $data);
        $this->view('templates/admin/footer');
    }

    public function add() {
        $data = [
            'title' => 'Dashboard - Add Tourist Object',
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/tourist-object/add');
        $this->view('templates/admin/footer');
    }

    public function add_tourist_object() {
        try {
            $imagePath = '';
            if (isset($_FILES['object_image']) && $_FILES['object_image']['error'] === 0) {
                $uploadDir = '../public/uploads/tourist-objects/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $fileType = $_FILES['object_image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error_message'] = 'Format file tidak valid. Hanya JPG, PNG, dan WebP yang diperbolehkan.';
                    header('Location: ' . BASEURL . '/admin/tourist-object');
                    exit;
                }
                
                $maxSize = 5 * 1024 * 1024;
                if ($_FILES['object_image']['size'] > $maxSize) {
                    $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    header('Location: ' . BASEURL . '/admin/tourist-object');
                    exit;
                }
                
                $fileName = time() . '_' . $_FILES['object_image']['name'];
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['object_image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/tourist-objects/' . $fileName;
                } else {
                    $_SESSION['error_message'] = 'Gagal mengupload gambar.';
                    header('Location: ' . BASEURL . '/admin/tourist-object');
                    exit;
                }
            }
            
            $objectData = [
                'title' => $_POST['title'],
                'article' => $_POST['article'],
                'image' => $imagePath,
                'category' => $_POST['category'],
                'address' => $_POST['address'],
                'google_map_link' => $_POST['google_map_link']
            ];
            
            $result = $this->touristObjectModel->add_tourist_object($objectData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Objek wisata berhasil ditambahkan!';
            } else {
                $_SESSION['error_message'] = 'Gagal menambahkan objek wisata. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/tourist-object');
        exit;
    }

    public function edit($id = null) {
        try {
            if ($id === null && isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            if (!$id) {
                $_SESSION['error_message'] = 'ID objek wisata tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/tourist-object');
                exit;
            }

            $touristObject = $this->touristObjectModel->getById($id);

            if (!$touristObject) {
                $_SESSION['error_message'] = 'Objek wisata tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/tourist-object');
                exit;
            }

            $data = [
                'title' => 'Dashboard - Edit Tourist Object',
                'touristObject' => $touristObject
            ];

            $this->view('templates/admin/header', $data);
            $this->view('admin/tourist-object/edit', $data);
            $this->view('templates/admin/footer');

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            header('Location: ' . BASEURL . '/admin/tourist-object');
            exit;
        }
    }

    public function edit_tourist_object() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID objek wisata tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/tourist-object');
                exit;
            }

            $oldObject = $this->touristObjectModel->getById($id);

            if (!$oldObject) {
                $_SESSION['error_message'] = 'Objek wisata tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/tourist-object');
                exit;
            }

            $imagePath = $oldObject['image'];

            if (isset($_FILES['object_image']) && $_FILES['object_image']['error'] === 0) {
                $uploadDir = '../public/uploads/tourist-objects/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $fileType = $_FILES['object_image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error_message'] = 'Format file tidak valid. Hanya JPG, PNG, dan WebP yang diperbolehkan.';
                    header('Location: ' . BASEURL . '/admin/tourist-object/edit?id=' . $id);
                    exit;
                }
                
                $maxSize = 5 * 1024 * 1024;
                if ($_FILES['object_image']['size'] > $maxSize) {
                    $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    header('Location: ' . BASEURL . '/admin/tourist-object/edit?id=' . $id);
                    exit;
                }
                
                $fileName = time() . '_' . $_FILES['object_image']['name'];
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['object_image']['tmp_name'], $targetPath)) {
                    if (!empty($oldObject['image'])) {
                        $oldImagePath = '../public/' . $oldObject['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    
                    $imagePath = 'uploads/tourist-objects/' . $fileName;
                } else {
                    $_SESSION['error_message'] = 'Gagal mengupload gambar.';
                    header('Location: ' . BASEURL . '/admin/tourist-object/edit/' . $id);
                    exit;
                }
            }
            
            $objectData = [
                'id' => $id,
                'title' => $_POST['title'],
                'article' => $_POST['article'],
                'image' => $imagePath,
                'category' => $_POST['category'],
                'address' => $_POST['address'],
                'google_map_link' => $_POST['google_map_link']
            ];
            
            $result = $this->touristObjectModel->update_tourist_object($objectData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Objek wisata berhasil diperbarui!';
            } else {
                $_SESSION['error_message'] = 'Gagal memperbarui objek wisata. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/tourist-object');
        exit;
    }

    public function delete_tourist_object() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID objek wisata tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/tourist-object');
                exit;
            }

            $result = $this->touristObjectModel->delete_tourist_object($id);

            if ($result) {
                $_SESSION['success_message'] = 'Objek wisata berhasil dihapus.';
            } else {
                $_SESSION['error_message'] = 'Objek wisata tidak ditemukan atau gagal dihapus.';
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/tourist-object');
        exit;
    }
}
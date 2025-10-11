<?php

class AdminEventController extends BaseController{

    private $eventModel;

    public function __construct() {
        AuthMiddleware::checkAuth();
        $this->eventModel = $this->model('EventModel');
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
        
        // Ambil data events dengan pagination
        $events = $this->eventModel->getAll($limit, $offset, $search);
        
        // Hitung total events untuk pagination
        $totalEvents = $this->eventModel->countAll($search);
        $totalPages = ceil($totalEvents / $limit);
        
        // Pastikan tidak ada halaman kosong
        if ($totalPages > 0 && $page > $totalPages) {
            header('Location: ' . BASEURL . '/admin/event/page/' . $totalPages);
            exit;
        }
        
        $data = [
            'title' => 'Dashboard - Event',
            'events' => $events,
            'success_message' => $success_message,
            'error_message' => $error_message,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_events' => $totalEvents,
            'search' => $search
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/event/index', $data);
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
            header('Location: ' . BASEURL . '/admin/event');
            exit;
        }
        
        // Ambil parameter pagination
        $page = $page ? (int)$page : 1;
        $page = $page < 1 ? 1 : $page;
        
        $limit = 6; // Jumlah data per halaman
        $offset = ($page - 1) * $limit;
        
        // Ambil data events dengan pagination dan search
        $events = $this->eventModel->getAll($limit, $offset, $search);
        
        // Hitung total events untuk pagination
        $totalEvents = $this->eventModel->countAll($search);
        $totalPages = ceil($totalEvents / $limit);
        
        // Pastikan tidak ada halaman kosong
        if ($totalPages > 0 && $page > $totalPages) {
            $redirectUrl = BASEURL . '/admin/event/search/' . urlencode($search);
            if ($totalPages > 1) {
                $redirectUrl .= '/page/' . $totalPages;
            }
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        $data = [
            'title' => 'Dashboard - Event',
            'events' => $events,
            'success_message' => $success_message,
            'error_message' => $error_message,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_events' => $totalEvents,
            'search' => $search
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/event/index', $data);
        $this->view('templates/admin/footer');
    }

    public function add() {
        $data = [
            'title' => 'Dashboard - Add Event',
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/event/add');
        $this->view('templates/admin/footer');
    }

    public function add_event() {
        try {
            // Handle upload gambar
            $imagePath = '';
            if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === 0) {
                $uploadDir = '../public/uploads/events/';
                
                // Buat folder jika belum ada
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Validasi tipe file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $fileType = $_FILES['event_image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error_message'] = 'Format file tidak valid. Hanya JPG, PNG, dan WebP yang diperbolehkan.';
                    header('Location: ' . BASEURL . '/admin/event');
                    exit;
                }
                
                // Validasi ukuran file (max 5MB)
                $maxSize = 5 * 1024 * 1024; // 5MB dalam bytes
                if ($_FILES['event_image']['size'] > $maxSize) {
                    $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    header('Location: ' . BASEURL . '/admin/event');
                    exit;
                }
                
                $fileName = time() . '_' . $_FILES['event_image']['name'];
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['event_image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/events/' . $fileName;
                } else {
                    $_SESSION['error_message'] = 'Gagal mengupload gambar.';
                    header('Location: ' . BASEURL . '/admin/event');
                    exit;
                }
            }
            
            // Siapkan data untuk disimpan
            $eventData = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'start_time' => $_POST['start_time'],
                'location' => $_POST['location'],
                'image' => $imagePath,
                'status' => $_POST['status'] ?? 'draft',
                'author_id' => AuthMiddleware::getAdminId()
            ];
            
            // Simpan ke database
            $result = $this->eventModel->add_event($eventData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Event berhasil ditambahkan!';
            } else {
                $_SESSION['error_message'] = 'Gagal menambahkan event. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        // Redirect ke halaman index event
        header('Location: ' . BASEURL . '/admin/event');
        exit;
    }

    public function delete_event() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID event tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/event');
                exit;
            }

            $result = $this->eventModel->delete_event($id);

            if ($result) {
                $_SESSION['success_message'] = 'Event berhasil dihapus.';
            } else {
                $_SESSION['error_message'] = 'Event tidak ditemukan atau gagal dihapus.';
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/event');
        exit;
    }

    public function edit($id = null) {
        try {
            // Jika id tidak ada di parameter, coba ambil dari GET
            if ($id === null && isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            if (!$id) {
                $_SESSION['error_message'] = 'ID event tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/event');
                exit;
            }

            // Ambil data event dari database
            $event = $this->eventModel->getById($id);

            if (!$event) {
                $_SESSION['error_message'] = 'Event tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/event');
                exit;
            }

            $data = [
                'title' => 'Dashboard - Edit Event',
                'event' => $event
            ];

            $this->view('templates/admin/header', $data);
            $this->view('admin/event/edit', $data);
            $this->view('templates/admin/footer');

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            header('Location: ' . BASEURL . '/admin/event');
            exit;
        }
    }

    public function edit_event() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID event tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/event');
                exit;
            }

            // Ambil data event lama untuk cek gambar
            $oldEvent = $this->eventModel->getById($id);

            if (!$oldEvent) {
                $_SESSION['error_message'] = 'Event tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/event');
                exit;
            }

            // Handle upload gambar baru
            $imagePath = $oldEvent['image']; // Default gunakan gambar lama

            if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === 0) {
                $uploadDir = '../public/uploads/events/';
                
                // Buat folder jika belum ada
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Validasi tipe file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $fileType = $_FILES['event_image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error_message'] = 'Format file tidak valid. Hanya JPG, PNG, dan WebP yang diperbolehkan.';
                    header('Location: ' . BASEURL . '/admin/event/edit?id=' . $id);
                    exit;
                }
                
                // Validasi ukuran file (max 5MB)
                $maxSize = 5 * 1024 * 1024;
                if ($_FILES['event_image']['size'] > $maxSize) {
                    $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    header('Location: ' . BASEURL . '/admin/event/edit?id=' . $id);
                    exit;
                }
                
                // Upload file baru
                $fileName = time() . '_' . $_FILES['event_image']['name'];
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['event_image']['tmp_name'], $targetPath)) {
                    // Hapus gambar lama jika ada
                    if (!empty($oldEvent['image'])) {
                        $oldImagePath = '../public/' . $oldEvent['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    
                    $imagePath = 'uploads/events/' . $fileName;
                } else {
                    $_SESSION['error_message'] = 'Gagal mengupload gambar.';
                    header('Location: ' . BASEURL . '/admin/event/edit/' . $id);
                    exit;
                }
            }
            
            // Siapkan data untuk diupdate
            $eventData = [
                'id' => $id,
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'start_time' => $_POST['start_time'],
                'location' => $_POST['location'],
                'image' => $imagePath,
                'status' => $_POST['status'] ?? 'draft'
            ];
            
            // Update ke database
            $result = $this->eventModel->update_event($eventData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Event berhasil diperbarui!';
            } else {
                $_SESSION['error_message'] = 'Gagal memperbarui event. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/event');
        exit;
    }
}
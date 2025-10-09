<?php

class AdminEventController extends BaseController{

    private $eventModel;

    public function __construct() {
        $this->eventModel = $this->model('EventModel');
    }

    public function index() {
        // Ambil notifikasi dari session jika ada
        $success_message = '';
        $error_message = '';
        
        if (isset($_SESSION['success_message'])) {
            $success_message = $_SESSION['success_message'];
            unset($_SESSION['success_message']); // Hapus setelah ditampilkan
        }
        
        if (isset($_SESSION['error_message'])) {
            $error_message = $_SESSION['error_message'];
            unset($_SESSION['error_message']); // Hapus setelah ditampilkan
        }
        
        $data = [
            'title' => 'Dashboard - Event',
            'events' => $this->eventModel->getAll(),
            'success_message' => $success_message,
            'error_message' => $error_message
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
                'author_id' => 1 // Hardcode untuk sementara, nanti bisa diganti dengan session user
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

    public function edit_event() {
        // Implementasi edit event (placeholder)
        $_SESSION['error_message'] = 'Fitur edit event belum diimplementasikan.';
        header('Location: ' . BASEURL . '/admin/event');
        exit;
    }
}
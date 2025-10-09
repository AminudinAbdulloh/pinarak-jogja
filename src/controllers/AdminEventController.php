<?php

class AdminEventController extends BaseController{

    private $eventModel;

    public function __construct() {
        $this->eventModel = $this->model('EventModel');
    }

    public function index() {
        $data = [
            'title' => 'Dashboard - Event',
            'events' => $this->eventModel->getAll()
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
        // Handle upload gambar
        $imagePath = '';
        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === 0) {
            $uploadDir = '../public/uploads/events/';
            
            // Buat folder jika belum ada
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = time() . '_' . $_FILES['event_image']['name'];
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['event_image']['tmp_name'], $targetPath)) {
                $imagePath = 'uploads/events/' . $fileName;
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
        $this->eventModel->add_event($eventData);
        
        // Redirect ke halaman add event
        header('Location: ' . BASEURL . '/admin/event/add');
        exit;
    }
}
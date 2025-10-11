<?php

class AdminContactController extends BaseController{
    private $contactModel;

    public function __construct() {
        AuthMiddleware::checkAuth();
        $this->contactModel = $this->model('ContactModel');
    }

    public function index() {
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

        $contacts = $this->contactModel->getAll();

        $data = [
            'title' => 'Dashboard - Contact',
            'contacts' => $contacts,
            'success_message' => $success_message,
            'error_message' => $error_message
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/contact/index', $data);
        $this->view('templates/admin/footer');
    }

    public function edit($id = null) {
        try {
            // Jika id tidak ada di parameter, coba ambil dari GET
            if ($id === null && isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            if (!$id) {
                $_SESSION['error_message'] = 'ID kontak tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/contact');
                exit;
            }

            // Ambil data dari database
            $contact = $this->contactModel->getById($id);

            if (!$contact) {
                $_SESSION['error_message'] = 'Kontak tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/contact');
                exit;
            }

            $data = [
                'title' => 'Dashboard - Edit Kontak',
                'contact' => $contact
            ];

            $this->view('templates/admin/header', $data);
            $this->view('admin/contact/edit', $data);
            $this->view('templates/admin/footer');

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            header('Location: ' . BASEURL . '/admin/contact');
            exit;
        }
    }

    public function edit_contact() {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error_message'] = 'ID contact tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/contact');
                exit;
            }

            // Ambil data lama untuk cek gambar
            $oldContact = $this->contactModel->getById($id);

            if (!$oldContact) {
                $_SESSION['error_message'] = 'Contact tidak ditemukan.';
                header('Location: ' . BASEURL . '/admin/contact');
                exit;
            }
            
            // Siapkan data untuk diupdate
            $contactData = [
                'id' => $id,
                'company_name' => $_POST['company_name'],
                'phone_number' => $_POST['phone_number'],
                'email' => $_POST['email'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'postal_code' => $_POST['postal_code'],
                'gmaps_embed_url' => $_POST['gmaps_embed_url'],
                'working_days' => $_POST['working_days'],
                'working_time' => $_POST['working_time'],
                'youtube' => $_POST['youtube'],
                'instagram' => $_POST['instagram'],
                'linkedin' => $_POST['linkedin'],
                'facebook' => $_POST['facebook'],
                'tiktok' => $_POST['tiktok'],
                'twitter' => $_POST['twitter'],
            ];
            
            // Update ke database
            $result = $this->contactModel->update_contact($contactData);
            
            if ($result) {
                $_SESSION['success_message'] = 'Kontak berhasil diperbarui!';
            } else {
                $_SESSION['error_message'] = 'Gagal memperbarui kontak. Silakan coba lagi.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        
        header('Location: ' . BASEURL . '/admin/contact');
        exit;
    }
}
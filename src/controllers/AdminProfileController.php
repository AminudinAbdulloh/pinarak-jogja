<?php

class AdminProfileController extends BaseController{
    
    private $profileModel;
    private $youtubeModel;
    
    public function __construct() {
        AuthMiddleware::checkAuth();
        $this->profileModel = $this->model('ProfileModel');
        $this->youtubeModel = $this->model('YoutubeModel');
    }

    public function index() {
        $data = [
            'title' => 'Dashboard - Profile',
            'profiles' => $this->profileModel->getAll(),
            'youtube_links' => $this->youtubeModel->getAll()
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/profile/index', $data);
        $this->view('templates/admin/footer');
    }

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
}
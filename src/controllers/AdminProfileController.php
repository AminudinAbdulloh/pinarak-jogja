<?php

class AdminProfileController extends BaseController{
    
    private $profileModel;
    private $youtubeModel;
    
    public function __construct() {
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
}
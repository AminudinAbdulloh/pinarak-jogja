<?php

class ProfileController extends BaseController{
    private $profileModel;
    private $youtubeModel;
    
    public function __construct() {
        $this->profileModel = $this->model('ProfileModel');
        $this->youtubeModel = $this->model('YoutubeModel');
    }   

    public function index() {
        $youtube_data = $this->youtubeModel->getAll();
        $profiles = $this->profileModel->getAll2();

        $data = [
            'title' => 'Profiles',
            'youtube_data' => $youtube_data,
            'profiles' => $profiles,
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/profile/index', $data);
        $this->view('templates/public/footer');
    }
}
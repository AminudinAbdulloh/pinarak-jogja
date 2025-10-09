<?php

class ProfileController extends BaseController{
    public function index() {
        $data = [
            'title' => 'Profiles',
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/profile/index', $data);
        $this->view('templates/public/footer');
    }
}
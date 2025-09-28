<?php

class DefaultApp extends BaseController {
    public function index() {
        $data = [
            'title' => 'Home',
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/home/index', $data);
        $this->view('templates/public/footer');
    }
}
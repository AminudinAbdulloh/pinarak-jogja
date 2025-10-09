<?php

class ContactController extends BaseController{
    public function index() {
        $data = [
            'title' => 'Articles',
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/contact/index', $data);
        $this->view('templates/public/footer');
    }
}
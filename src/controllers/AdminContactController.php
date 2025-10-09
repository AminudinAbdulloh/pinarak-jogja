<?php

class AdminContactController extends BaseController{
    public function index() {
        $data = [
            'title' => 'Dashboard - Contact',
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/contact/index');
        $this->view('templates/admin/footer');
    }
}
<?php

class AdminDashboardController extends BaseController{
    public function index() {
        $data = [
            'title' => 'Dashboard - Home',
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/home/index');
        $this->view('templates/admin/footer');
    }
}
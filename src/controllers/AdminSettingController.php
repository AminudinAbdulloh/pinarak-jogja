<?php

class AdminSettingController extends BaseController{
    public function index() {
        $data = [
            'title' => 'Dashboard - Setting',
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/setting/index');
        $this->view('templates/admin/footer');
    }
}
<?php

class InformationController extends BaseController{
    public function index() {
        $data = [
            'title' => 'Informations',
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/information/index', $data);
        $this->view('templates/public/footer');
    }
}
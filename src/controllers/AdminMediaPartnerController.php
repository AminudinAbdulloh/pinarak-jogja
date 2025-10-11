<?php

class AdminMediaPartnerController extends BaseController{
    private $mediaPartnerModel;

    public function __construct() {
        $this->mediaPartnerModel = $this->model('MediaPartnerModel');
    }

    public function index() {
        $data = [
            'title' => 'Dashboard - Media Partner',
            'media_partners' => $this->mediaPartnerModel->getAll()
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/media-partner/index', $data);
        $this->view('templates/admin/footer');
    }

    public function add() {
        $data = [
            'title' => 'Dashboard - Add Media Partner',
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/media-partner/add');
        $this->view('templates/admin/footer');
    }

    public function edit($id) {
        echo "Edit from article = " . $id;
    }
}
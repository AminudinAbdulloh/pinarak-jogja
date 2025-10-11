<?php

class InformationController extends BaseController{
    private $mediaPartnerModel;

    public function __construct() {
        $this->mediaPartnerModel = $this->model('MediaPartnerModel');
    }

    public function index() {
        $media_partners = $this->mediaPartnerModel->getAll();

        $data = [
            'title' => 'Informations',
            'media_partners' => $media_partners,
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/information/index', $data);
        $this->view('templates/public/footer');
    }
}
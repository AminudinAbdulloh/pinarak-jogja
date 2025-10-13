<?php

class InformationController extends BaseController{
    private $mediaPartnerModel;
    private $settingModel;
    private $contactModel;

    public function __construct() {
        $this->mediaPartnerModel = $this->model('MediaPartnerModel');
        $this->settingModel = $this->model('SettingModel');
        $this->contactModel = $this->model('ContactModel');
    }

    public function index() {
        $media_partners = $this->mediaPartnerModel->getAll();

        $data = [
            'title' => 'Informations',
            'media_partners' => $media_partners,
            'setting' => $this->settingModel->getSettings(),
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/information/index', $data);
        $this->view('templates/public/footer', $data);
    }
}
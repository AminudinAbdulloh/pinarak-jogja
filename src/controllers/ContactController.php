<?php

class ContactController extends BaseController{
    private $contactModel;
    private $settingModel;
    private $mediaPartnerModel;
    
    public function __construct() {
        $this->contactModel = $this->model('ContactModel');
        $this->settingModel = $this->model('SettingModel');
        $this->mediaPartnerModel = $this->model('MediaPartnerModel');
    }
    
    public function index() {
        $data = [
            'title' => 'Contacts',
            'contact' => $this->contactModel->getContacts(),
            'setting' => $this->settingModel->getSettings(),
            'media_partners' => $this->mediaPartnerModel->getAll()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/contact/index', $data);
        $this->view('templates/public/footer', $data);
    }
}
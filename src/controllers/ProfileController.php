<?php

class ProfileController extends BaseController{
    private $profileModel;
    private $coeModel;
    private $settingModel;
    private $contactModel;
    
    public function __construct() {
        $this->profileModel = $this->model('ProfileModel');
        $this->coeModel = $this->model('COEModel');
        $this->settingModel = $this->model('SettingModel');
        $this->contactModel = $this->model('ContactModel');
    }   

    public function index() {
        $coe_list = $this->coeModel->getAll();
        $profiles = $this->profileModel->getAll2();

        $data = [
            'title' => 'Profiles',
            'coe_list' => $coe_list,
            'profiles' => $profiles,
            'setting' => $this->settingModel->getSettings(),
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/profile/index', $data);
        $this->view('templates/public/footer', $data);
    }
}
<?php

class DefaultApp extends BaseController {
    private $eventModel;
    private $settingModel;
    private $mediaPartnerModel;
    private $contactModel;

    public function __construct() {
        $this->eventModel = $this->model('EventModel');
        $this->settingModel = $this->model('SettingModel');
        $this->mediaPartnerModel = $this->model('MediaPartnerModel');
        $this->contactModel = $this->model('ContactModel');
    }

    public function index() {
        $data = [
            'title' => 'Home',
            'highlight_event' => $this->eventModel->highlight_event(),
            'all_events' => $this->eventModel->all_events(),
            'setting' => $this->settingModel->getSettings(),
            'media_partners' => $this->mediaPartnerModel->getAll(),
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/home/index', $data);
        $this->view('templates/public/footer', $data);
    }
}
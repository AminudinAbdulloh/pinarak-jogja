<?php

class DefaultApp extends BaseController {
    private $eventModel;

    public function __construct() {
        $this->eventModel = $this->model('EventModel');
    }

    public function index() {
        $data = [
            'title' => 'Home',
            'highlight_event' => $this->eventModel->highlight_event(),
            'all_events' => $this->eventModel->all_events()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/home/index', $data);
        $this->view('templates/public/footer');
    }
}
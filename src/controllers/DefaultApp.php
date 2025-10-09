<?php

class DefaultApp extends BaseController {
    private $eventModel;

    public function __construct() {
        $this->eventModel = $this->model('EventModel');
    }

    public function index() {
        $data = [
            'title' => 'Home',
            'highlight_event' => $this->eventModel->highlightEvent(),
            'all_events' => $this->eventModel->allEvents()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/home/index', $data);
        $this->view('templates/public/footer');
    }
}
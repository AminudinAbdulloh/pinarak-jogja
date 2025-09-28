<?php

class AdminEventController extends BaseController{

    private $eventModel;

    public function __construct() {
        $this->eventModel = $this->model('EventModel');
    }

    public function index() {
        $data = [
            'title' => 'Dashboard - Event',
            'events' => $this->eventModel->getAll()
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/event/index', $data);
        $this->view('templates/admin/footer');
    }

    public function add() {
        $data = [
            'title' => 'Dashboard - Add Event',
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/event/add');
        $this->view('templates/admin/footer');
    }
}
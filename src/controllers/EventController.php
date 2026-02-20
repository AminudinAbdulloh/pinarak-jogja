<?php

class EventController extends BaseController{
    private $eventModel;
    private $settingModel;
    private $contactModel;
    private $itemsPerPage = 6;

    public function __construct() {
        $this->eventModel = $this->model('EventModel');
        $this->settingModel = $this->model('SettingModel');
        $this->contactModel = $this->model('ContactModel');
    }

    public function index($page = 1) {
        // Validasi halaman
        $page = (int)$page;
        if ($page < 1) {
            header('Location: ' . BASEURL . '/events/1');
            exit;
        }

        // Hitung offset
        $offset = ($page - 1) * $this->itemsPerPage;

        // Ambil events dengan paginasi
        $events = $this->eventModel->getAllPublished(
            $this->itemsPerPage,
            $offset
        );

        // Hitung total events
        $totalEvents = $this->eventModel->countAllPublished();
        $totalPages = ceil($totalEvents / $this->itemsPerPage);

        // Validasi halaman tidak melebihi total
        if ($page > $totalPages && $totalPages > 0) {
            header('Location: ' . BASEURL . '/events/' . $totalPages);
            exit;
        }

        $data = [
            'title' => 'Events',
            'events' => $events,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalEvents' => $totalEvents,
            'setting' => $this->settingModel->getSettings(),
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/event/index', $data);
        $this->view('templates/public/footer', $data);
    }
}


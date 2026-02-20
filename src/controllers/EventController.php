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

    public function detail($id = null) {
        if (!$id) {
            header('Location: ' . BASEURL . '/events');
            exit;
        }

        // Ambil event berdasarkan ID
        $event = $this->eventModel->getById($id);

        // Jika event tidak ditemukan atau belum dipublikasikan, arahkan kembali ke daftar event
        if (!$event || $event['status'] !== 'published') {
            header('Location: ' . BASEURL . '/events');
            exit;
        }

        // Ambil event terkait (3 event lain yang masih upcoming)
        $relatedEvents = $this->eventModel->getRelatedPublished($id, 3);

        $data = [
            'title' => 'Pinarak Jogja - ' . $event['title'],
            'event' => $event,
            'relatedEvents' => $relatedEvents,
            'setting' => $this->settingModel->getSettings(),
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/event/detail', $data);
        $this->view('templates/public/footer', $data);
    }
}


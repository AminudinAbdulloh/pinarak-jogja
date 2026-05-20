<?php

class DefaultApp extends BaseController {
    private $eventModel;
    private $settingModel;
    private $contactModel;

    public function __construct() {
        $this->eventModel = $this->model('EventModel');
        $this->settingModel = $this->model('SettingModel');
        $this->contactModel = $this->model('ContactModel');
    }

    public function index() {
        $data = [
            'title' => 'Home',
            'highlight_event' => $this->eventModel->highlight_event(),
            'all_events' => $this->eventModel->all_events(),
            'setting' => $this->settingModel->getSettings(),
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/home/index', $data);
        $this->view('templates/public/footer', $data);
    }

    /**
     * Pencarian event untuk section Event Terbaru (PHP/MySQL saja).
     */
    public function searchEvents() {
        header('Content-Type: application/json; charset=utf-8');

        $q = trim($_GET['q'] ?? '');
        $n = (int)($_GET['n'] ?? 6);
        $n = max(1, min($n, 10));

        if ($q === '') {
            echo json_encode(['success' => false, 'message' => 'Kata kunci pencarian kosong.']);
            return;
        }

        $results = $this->eventModel->searchPublishedUpcoming($q, $n);

        echo json_encode([
            'success' => true,
            'query'   => $q,
            'total'   => count($results),
            'results' => $results,
        ]);
    }
}
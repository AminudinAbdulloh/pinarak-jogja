<?php

class EventController extends BaseController{
    private $eventModel;
    private $settingModel;
    private $contactModel;
    private $itemsPerPage = 6;
    private $recoApiBase = 'http://127.0.0.1:5000';

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

        // Ambil rekomendasi dari Recommendation API (fallback ke query lokal jika gagal)
        $relatedEvents = $this->fetchRecommendedEvents((int)$id, 3);
        if ($relatedEvents === null) {
            $relatedEvents = $this->eventModel->getRelatedPublished($id, 3);
        }

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

    /**
     * Panggil GET /api/recommendations/<event_id>?n=3 di Recommendation API menggunakan cURL.
     * Mengembalikan array event rekomendasi atau null jika terjadi error.
     */
    private function fetchRecommendedEvents(int $eventId, int $n = 3): ?array
    {
        $n = max(1, min($n, 10));
        $url = rtrim($this->recoApiBase, '/') . '/api/recommendations/' . $eventId . '?n=' . $n;

        if (!function_exists('curl_init')) {
            return null;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
        ]);

        $raw = curl_exec($ch);
        if ($raw === false) {
            curl_close($ch);
            return null;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return null;
        }

        $data = json_decode($raw, true);
        if (!is_array($data) || empty($data['success']) || empty($data['recommendations']) || !is_array($data['recommendations'])) {
            return null;
        }

        return $data['recommendations'];
    }


}


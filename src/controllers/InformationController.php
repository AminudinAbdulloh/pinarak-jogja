<?php

class InformationController extends BaseController{
    private $touristObjectModel;
    private $mediaPartnerModel;
    private $settingModel;
    private $contactModel;

    public function __construct() {
        $this->touristObjectModel = $this->model('TouristObjectModel');
        $this->mediaPartnerModel = $this->model('MediaPartnerModel');
        $this->settingModel = $this->model('SettingModel');
        $this->contactModel = $this->model('ContactModel');
    }

    public function index($page = 1) {
        $page = (int)$page;
        if ($page < 1) $page = 1;
        
        $limit = 6;
        $offset = ($page - 1) * $limit;
        
        // Ambil data artikel
        $articles = $this->touristObjectModel->getAll($limit, $offset);
        
        // Hitung total halaman
        $totalArticles = $this->touristObjectModel->countAll();
        $totalPages = ceil($totalArticles / $limit);
        
        // Validasi halaman
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }
        
        $media_partners = $this->mediaPartnerModel->getAll();

        $data = [
            'title' => 'Informations',
            'articles' => $articles,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $totalArticles,
            'media_partners' => $media_partners,
            'setting' => $this->settingModel->getSettings(),
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/information/index', $data);
        $this->view('templates/public/footer', $data);
    }

    public function detail($id = null) {
        if (!$id) {
            header('Location: ' . BASEURL . '/informations');
            exit;
        }

        $informations = $this->touristObjectModel->getById($id);

        // Ambil artikel terkait (3 artikel terbaru selain artikel ini)
        // $relatedArticles = $this->touristObjectModel->getRelatedArticles($id, 3);

        $data = [
            'title' => 'Pinarak Jogja - ' . $informations['title'],
            'informations' => $informations,
            'setting' => $this->settingModel->getSettings(),
            'media_partners' => $this->mediaPartnerModel->getAll(),
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/information/detail', $data);
        $this->view('templates/public/footer', $data);
    }
}
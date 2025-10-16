<?php

class ArticleController extends BaseController{
    private $articleModel;
    private $settingModel;
    private $mediaPartnerModel;
    private $contactModel;

    public function __construct() {
        $this->articleModel = $this->model('ArticleModel');
        $this->settingModel = $this->model('SettingModel');
        $this->mediaPartnerModel = $this->model('MediaPartnerModel');
        $this->contactModel = $this->model('ContactModel');
    }

    public function index() {
        $articles = $this->articleModel->getAllPublished();

        $data = [
            'title' => 'Articles',
            'articles' => $articles,
            'setting' => $this->settingModel->getSettings(),
            'media_partners' => $this->mediaPartnerModel->getAll(),
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/article/index', $data);
        $this->view('templates/public/footer', $data);
    }

    public function detail($id = null) {
        if (!$id) {
            header('Location: ' . BASEURL . '/articles');
            exit;
        }

        // Ambil artikel berdasarkan ID
        $article = $this->articleModel->getById($id);

        if (!$article || $article['status'] !== 'published') {
            header('Location: ' . BASEURL . '/articles');
            exit;
        }

        // Ambil artikel terkait (3 artikel terbaru selain artikel ini)
        $relatedArticles = $this->articleModel->getRelatedArticles($id, 3);

        $data = [
            'title' => 'Pinarak Jogja - ' . $article['title'],
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'setting' => $this->settingModel->getSettings(),
            'media_partners' => $this->mediaPartnerModel->getAll(),
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/article/detail', $data);
        $this->view('templates/public/footer', $data);
    }
}
<?php

class ArticleController extends BaseController{
    private $articleModel;
    private $settingModel;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
    private $contactModel;
    private $itemsPerPage = 6;


    public function __construct() {
        $this->articleModel = $this->model('ArticleModel');
        $this->settingModel = $this->model('SettingModel');
        $this->contactModel = $this->model('ContactModel');
    }

    public function index($page = 1) {
        // Validasi halaman
        $page = (int)$page;
        if ($page < 1) {
            header('Location: ' . BASEURL . '/articles/1');
            exit;
        }

        // Hitung offset
        $offset = ($page - 1) * $this->itemsPerPage;

        // Ambil artikel dengan paginasi
        $articles = $this->articleModel->getAllPublishedWithPagination(
            $this->itemsPerPage,
            $offset
        );

        // Hitung total artikel
        $totalArticles = $this->articleModel->countAllPublished();
        $totalPages = ceil($totalArticles / $this->itemsPerPage);

        // Validasi halaman tidak melebihi total
        if ($page > $totalPages && $totalPages > 0) {
            header('Location: ' . BASEURL . '/articles/' . $totalPages);
            exit;
        }

        $data = [
            'title' => 'Articles',
            'articles' => $articles,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $totalArticles,
            'setting' => $this->settingModel->getSettings(),
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
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/article/detail', $data);
        $this->view('templates/public/footer', $data);
    }
}
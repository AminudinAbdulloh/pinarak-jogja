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

        // Increment views
        $this->articleModel->incrementViews($id);
        
        // Get user IP
        $userIp = $this->getUserIP();
        
        // Get user's rating if exists
        $userRating = $this->articleModel->getUserRating($id, $userIp);

        // Ambil artikel terkait (3 artikel terbaru selain artikel ini)
        $relatedArticles = $this->articleModel->getRelatedArticles($id, 3);

        $data = [
            'title' => 'Pinarak Jogja - ' . $article['title'],
            'article' => $article,
            'userRating' => $userRating,
            'relatedArticles' => $relatedArticles,
            'setting' => $this->settingModel->getSettings(),
            'contact' => $this->contactModel->getContacts()
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/article/detail', $data);
        $this->view('templates/public/footer', $data);
    }

    public function rate() {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        $articleId = $input['article_id'] ?? null;
        $rating = $input['rating'] ?? null;

        // Validation
        if (!$articleId || !$rating || $rating < 1 || $rating > 5) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            exit;
        }

        // Get user IP
        $userIp = $this->getUserIP();

        // Save rating
        try {
            $this->articleModel->addOrUpdateRating($articleId, $userIp, $rating);
            
            // Get updated stats
            $stats = $this->articleModel->getArticleStats($articleId);
            
            echo json_encode([
                'success' => true,
                'message' => 'Rating berhasil disimpan',
                'data' => [
                    'avg_rating' => round($stats['avg_rating'], 1),
                    'total_ratings' => $stats['total_ratings']
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan rating']);
        }
        exit;
    }

    private function getUserIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
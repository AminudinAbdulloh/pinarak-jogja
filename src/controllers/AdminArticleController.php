<?php

class AdminArticleController extends BaseController{
    private $articleModel;

    public function __construct() {
        $this->articleModel = $this->model('ArticleModel');
    }

    public function index() {
        $data = [
            'title' => 'Dashboard - Article',
            'articles' => $this->articleModel->getAll()
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/article/index', $data);
        $this->view('templates/admin/footer');
    }

    public function add() {
        $data = [
            'title' => 'Dashboard - Add Article',
        ];

        $this->view('templates/admin/header', $data);
        $this->view('admin/article/add');
        $this->view('templates/admin/footer');
    }
}
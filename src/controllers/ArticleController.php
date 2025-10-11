<?php

class ArticleController extends BaseController{
    private $articleModel;

    public function __construct() {
        $this->articleModel = $this->model('ArticleModel');
    }

    public function index() {
        $articles = $this->articleModel->getAll();

        $data = [
            'title' => 'Articles',
            'articles' => $articles,
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/article/index', $data);
        $this->view('templates/public/footer');
    }
}
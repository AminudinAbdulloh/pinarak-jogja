<?php

class ArticleController extends BaseController{
    public function index() {
        $data = [
            'title' => 'Articles',
        ];

        $this->view('templates/public/header', $data);
        $this->view('public/article/index', $data);
        $this->view('templates/public/footer');
    }
}
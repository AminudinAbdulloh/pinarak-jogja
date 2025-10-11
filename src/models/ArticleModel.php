<?php

class ArticleModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $query = "SELECT a.*, ad.username as author_name 
        FROM articles a 
        LEFT JOIN admins ad ON a.author_id = ad.id 
        ORDER BY a.created_at DESC";
        return $this->qry($query)->fetchAll();
    }

    public function add_article($data) {
        $query = "INSERT INTO articles (title, content, excerpt, image, status, author_id) 
                  VALUES (:title, :content, :excerpt, :image, :status, :author_id)";
        
        $params = [
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':excerpt' => $data['excerpt'],
            ':image' => $data['image'],
            ':status' => $data['status'],
            ':author_id' => $data['author_id']
        ];
        
        return $this->qry($query, $params);
    }
}
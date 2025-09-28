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
}
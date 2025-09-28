<?php

class YoutubeModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $query = "SELECT 
            e.id,
            e.title,
            e.url,
            a.username as publisher_name
        FROM youtube_link e
        LEFT JOIN admins a ON e.author_id = a.id
        ORDER BY e.created_at DESC";
        return $this->qry($query)->fetchAll();
    }
}
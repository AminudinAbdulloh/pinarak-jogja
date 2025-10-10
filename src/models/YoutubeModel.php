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

    public function getById($id) {
        $query = "SELECT * FROM youtube_link WHERE id = :id";
        return $this->qry($query, [':id' => $id])->fetch();
    }

    public function add_youtube_link($data) {
        $query = "INSERT INTO youtube_link (title, url, author_id)
                  VALUES (:title, :url, :author_id)";
        
        $params = [
            ':title' => $data['title'],
            ':url' => $data['url'],
            ':author_id' => $data['author_id']
        ];
        
        return $this->qry($query, $params);
    }

    public function update_youtube_link($data) {
        $query = "UPDATE youtube_link 
                  SET title = :title, 
                      url = :url,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $params = [
            ':id' => $data['id'],
            ':title' => $data['title'],
            ':url' => $data['url'],
        ];
        
        return $this->qry($query, $params);
    }

    public function delete_youtube_link($id) {
        $querySelect = "SELECT id FROM youtube_link WHERE id = :id";
        $event = $this->qry($querySelect, [':id' => $id])->fetch();

        if (!$event) {
            return false;
        }

        $queryDelete = "DELETE FROM youtube_link WHERE id = :id";
        $params = [':id' => $id];

        return $this->qry($queryDelete, $params);
    }
}
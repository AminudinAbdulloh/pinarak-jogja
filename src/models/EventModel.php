<?php

class EventModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $query = "SELECT 
            e.id,
            e.title,
            e.description,
            e.start_time,
            e.location,
            e.image,
            e.status,
            a.username as publisher_name
        FROM events e
        LEFT JOIN admins a ON e.author_id = a.id";
        return $this->qry($query)->fetchAll();
    }

    public function insert($data) {
        try {
            $query = "INSERT INTO events (id, title, description, start_time, location, image, author_id, status) 
                      VALUES (:id, :title, :description, :start_time, :location, :image, :author_id, :status)";
            
            $params = [
                ':id' => $data['id'],
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':start_time' => $data['start_time'],
                ':location' => $data['location'],
                ':image' => $data['image'],
                ':author_id' => $data['author_id'],
                ':status' => $data['status']
            ];
            
            $this->qry($query, $params);
            return true;
        } catch (PDOException $e) {
            error_log("Error inserting event: " . $e->getMessage());
            return false;
        }
    }

    public function add_event($data) {
        $query = "INSERT INTO events (title, description, start_time, location, image, status, author_id) 
                  VALUES (:title, :description, :start_time, :location, :image, :status, :author_id)";
        
        $params = [
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':start_time' => $data['start_time'],
            ':location' => $data['location'],
            ':image' => $data['image'],
            ':status' => $data['status'],
            ':author_id' => $data['author_id']
        ];
        
        return $this->qry($query, $params);
    }
}
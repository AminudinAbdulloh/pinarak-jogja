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

    public function highlight_event(){
        $query = "SELECT *
        FROM events
        WHERE status = 'published' AND start_time > NOW()
        ORDER BY start_time ASC
        LIMIT 1";
        return $this->qry($query)->fetch();
    }

    public function all_events() {
        $query = "SELECT *
        FROM events
        WHERE status = 'published' AND start_time > NOW() 
        ORDER BY start_time ASC 
        LIMIT 6";
        return $this->qry($query)->fetchAll();
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

    public function delete_event($id) {
        // Ambil data event dulu untuk cek apakah ada file image yang harus dihapus
        $querySelect = "SELECT image FROM events WHERE id = :id";
        $event = $this->qry($querySelect, [':id' => $id])->fetch();

        if (!$event) {
            // Jika event tidak ditemukan
            return false;
        }

        // Jika ada gambar, hapus file-nya dari folder uploads
        if (!empty($event['image'])) {
            $filePath = '../public/' . $event['image'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus data event dari database
        $queryDelete = "DELETE FROM events WHERE id = :id";
        $params = [':id' => $id];

        return $this->qry($queryDelete, $params);
    }
}
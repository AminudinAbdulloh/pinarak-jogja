<?php

class EventModel extends Database {
    public function __construct() {
        parent::__construct();
    }
    public function getAll($limit = 6, $offset = 0, $search = '') {
        // Pastikan limit dan offset adalah integer untuk keamanan
        $limit = (int)$limit;
        $offset = (int)$offset;
        
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
        
        $params = [];
        
        // Tambahkan kondisi search jika ada
        if (!empty($search)) {
            $query .= " WHERE (e.title LIKE ? 
                        OR e.description LIKE ? 
                        OR e.location LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        // Tambahkan LIMIT dan OFFSET langsung ke query (sudah aman karena di-cast ke int)
        $query .= " ORDER BY e.created_at DESC LIMIT $limit OFFSET $offset";
        
        return $this->qry($query, $params)->fetchAll();
    }

    public function countAll($search = '') {
        $query = "SELECT COUNT(*) as total FROM events e";
        
        // Tambahkan kondisi search jika ada
        if (!empty($search)) {
            $query .= " WHERE (e.title LIKE ? 
                        OR e.description LIKE ? 
                        OR e.location LIKE ?)";
            
            $searchParam = "%$search%";
            $result = $this->qry($query, [$searchParam, $searchParam, $searchParam])->fetch();
        } else {
            $result = $this->qry($query)->fetch();
        }
        
        return $result['total'];
    }

    public function getById($id) {
        $query = "SELECT 
            e.id,
            e.title,
            e.description,
            e.start_time,
            e.location,
            e.image,
            e.status,
            e.author_id,
            a.username as publisher_name
        FROM events e
        LEFT JOIN admins a ON e.author_id = a.id
        WHERE e.id = :id";
        
        return $this->qry($query, [':id' => $id])->fetch();
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

    public function update_event($data) {
        $query = "UPDATE events 
                  SET title = :title, 
                      description = :description, 
                      start_time = :start_time, 
                      location = :location, 
                      image = :image, 
                      status = :status,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $params = [
            ':id' => $data['id'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':start_time' => $data['start_time'],
            ':location' => $data['location'],
            ':image' => $data['image'],
            ':status' => $data['status']
        ];
        
        return $this->qry($query, $params);
    }
}
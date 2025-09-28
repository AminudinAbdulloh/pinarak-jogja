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
}
<?php

class ProfileModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $query = "SELECT 
            p.id,
            p.full_name,
            p.position,
            p.pass_photo,
            p.linkedin,
            p.instagram,
            p.facebook,
            p.tiktok,
            p.status,
            p.display_order,
            p.created_at,
            a.username as publisher_name
        FROM profiles p
        LEFT JOIN admins a ON p.author_id = a.id";
        return $this->qry($query)->fetchAll();
    }
}
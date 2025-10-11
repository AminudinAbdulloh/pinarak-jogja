<?php

class MediaPartnerModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $query = "SELECT 
            mp.id,
            mp.name,
            mp.logo,
            mp.description,
            mp.website,
            a.username as author_name
        FROM media_partners mp
        LEFT JOIN admins a ON mp.author_id = a.id";
        return $this->qry($query)->fetchAll();
    }

    public function add_media_partner($data) {
        $query = "INSERT INTO media_partners (name, logo, description, website, author_id)
                  VALUES (:name, :logo, :description, :website, :author_id)";
        
        $params = [
            ':name' => $data['name'],
            ':logo' => $data['logo'],
            ':description' => $data['description'],
            ':website' => $data['website'],
            ':author_id' => $data['author_id']
        ];
        
        return $this->qry($query, $params);
    }
}
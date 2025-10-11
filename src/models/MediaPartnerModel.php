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

    public function getAllWithPagination($limit = 6, $offset = 0, $search = '') {
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $query = "SELECT 
            mp.id,
            mp.name,
            mp.logo,
            mp.description,
            mp.website,
            a.username as author_name
        FROM media_partners mp
        LEFT JOIN admins a ON mp.author_id = a.id";
        
        $params = [];
        
        // Tambahkan kondisi search jika ada
        if (!empty($search)) {
            $query .= " WHERE (p.name LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        // Order by created_at
        $query .= " ORDER BY mp.created_at DESC LIMIT $limit OFFSET $offset";
        
        return $this->qry($query, $params)->fetchAll();
    }

    public function countAll($search = '') {
        $query = "SELECT COUNT(*) as total FROM media_partners p";
        
        // Tambahkan kondisi search jika ada
        if (!empty($search)) {
            $query .= " WHERE (p.full_name LIKE ?)";
            
            $searchParam = "%$search%";
            $result = $this->qry($query, [$searchParam, $searchParam])->fetch();
        } else {
            $result = $this->qry($query)->fetch();
        }
        
        return $result['total'];
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
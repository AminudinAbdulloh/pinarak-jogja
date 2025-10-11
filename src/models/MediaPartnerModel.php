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
            $query .= " WHERE mp.name LIKE ?";
            $searchParam = "%$search%";
            $params[] = $searchParam;
        }
        
        // Order by created_at
        $query .= " ORDER BY mp.created_at DESC LIMIT $limit OFFSET $offset";
        
        return $this->qry($query, $params)->fetchAll();
    }


    public function countAll($search = '') {
    $query = "SELECT COUNT(*) as total FROM media_partners mp";
        $params = [];

        if (!empty($search)) {
            $query .= " WHERE mp.name LIKE ?";
            $searchParam = "%$search%";
            $params[] = $searchParam;
        }

        $result = $this->qry($query, $params)->fetch();
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

    public function delete_media_partner($id) {
        // Ambil data media partner dulu untuk cek apakah ada file image yang harus dihapus
        $querySelect = "SELECT logo FROM media_partners WHERE id = :id";
        $media_partner = $this->qry($querySelect, [':id' => $id])->fetch();

        if (!$media_partner) {
            // Jika media partner tidak ditemukan
            return false;
        }

        // Jika ada gambar, hapus file-nya dari folder uploads
        if (!empty($media_partner['image'])) {
            // Dari src/models/ ke public/
            $filePath = __DIR__ . '/../../public/' . $media_partner['image'];
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus data media partner dari database
        $queryDelete = "DELETE FROM media_partners WHERE id = :id";
        $params = [':id' => $id];

        return $this->qry($queryDelete, $params);
    }
}
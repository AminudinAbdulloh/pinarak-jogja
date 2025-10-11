<?php

class ProfileModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAll2() {
        $query = "SELECT * FROM profiles ORDER BY created_at DESC";
        return $this->qry($query)->fetchAll();
    }

    public function getAll($limit = 6, $offset = 0, $search = '') {
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $query = "SELECT 
            p.id,
            p.full_name,
            p.position,
            p.pass_photo,
            p.email,
            p.phone_number,
            p.linkedin,
            p.instagram,
            p.facebook,
            p.tiktok,
            p.status,
            p.created_at,
            a.username as publisher_name
        FROM profiles p
        LEFT JOIN admins a ON p.author_id = a.id";
        
        $params = [];
        
        // Tambahkan kondisi search jika ada
        if (!empty($search)) {
            $query .= " WHERE (p.full_name LIKE ? 
                        OR p.position LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        // Order by created_at
        $query .= " ORDER BY p.created_at DESC LIMIT $limit OFFSET $offset";
        
        return $this->qry($query, $params)->fetchAll();
    }

    public function countAll($search = '') {
        $query = "SELECT COUNT(*) as total FROM profiles p";
        
        // Tambahkan kondisi search jika ada
        if (!empty($search)) {
            $query .= " WHERE (p.full_name LIKE ? 
                        OR p.position LIKE ?)";
            
            $searchParam = "%$search%";
            $result = $this->qry($query, [$searchParam, $searchParam])->fetch();
        } else {
            $result = $this->qry($query)->fetch();
        }
        
        return $result['total'];
    }

    public function getById($id) {
        $query = "SELECT 
            p.id,
            p.full_name,
            p.position,
            p.pass_photo,
            p.email,
            p.phone_number,
            p.linkedin,
            p.instagram,
            p.facebook,
            p.tiktok,
            p.status,
            p.author_id,
            a.username as publisher_name
        FROM profiles p
        LEFT JOIN admins a ON p.author_id = a.id
        WHERE p.id = :id";
        
        return $this->qry($query, [':id' => $id])->fetch();
    }

    public function add_profile($data) {
        $query = "INSERT INTO profiles 
                  (full_name, pass_photo, position, email, phone_number, 
                   linkedin, instagram, facebook, tiktok, status, author_id) 
                  VALUES 
                  (:full_name, :pass_photo, :position, :email, :phone_number, 
                   :linkedin, :instagram, :facebook, :tiktok, :status, :author_id)";
        
        $params = [
            ':full_name' => $data['full_name'],
            ':pass_photo' => $data['pass_photo'],
            ':position' => $data['position'],
            ':email' => $data['email'],
            ':phone_number' => $data['phone_number'],
            ':linkedin' => $data['linkedin'],
            ':instagram' => $data['instagram'],
            ':facebook' => $data['facebook'],
            ':tiktok' => $data['tiktok'],
            ':status' => $data['status'],
            ':author_id' => $data['author_id']
        ];
        
        return $this->qry($query, $params);
    }

    public function update_profile($data) {
        $query = "UPDATE profiles 
                  SET full_name = :full_name, 
                      pass_photo = :pass_photo,
                      position = :position, 
                      email = :email, 
                      phone_number = :phone_number,
                      linkedin = :linkedin,
                      instagram = :instagram,
                      facebook = :facebook,
                      tiktok = :tiktok,
                      status = :status,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $params = [
            ':id' => $data['id'],
            ':full_name' => $data['full_name'],
            ':pass_photo' => $data['pass_photo'],
            ':position' => $data['position'],
            ':email' => $data['email'],
            ':phone_number' => $data['phone_number'],
            ':linkedin' => $data['linkedin'],
            ':instagram' => $data['instagram'],
            ':facebook' => $data['facebook'],
            ':tiktok' => $data['tiktok'],
            ':status' => $data['status']
        ];
        
        return $this->qry($query, $params);
    }

    public function delete_profile($id) {
        // Ambil data profile dulu untuk cek apakah ada file foto yang harus dihapus
        $querySelect = "SELECT pass_photo FROM profiles WHERE id = :id";
        $profile = $this->qry($querySelect, [':id' => $id])->fetch();

        if (!$profile) {
            return false;
        }

        // Jika ada foto, hapus file-nya dari folder uploads
        if (!empty($profile['pass_photo'])) {
            $filePath = '../public/' . $profile['pass_photo'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus data profile dari database
        $queryDelete = "DELETE FROM profiles WHERE id = :id";
        $params = [':id' => $id];

        return $this->qry($queryDelete, $params);
    }

    public function getAllActive() {
        $query = "SELECT 
            p.id,
            p.full_name,
            p.position,
            p.pass_photo,
            p.email,
            p.phone_number,
            p.linkedin,
            p.instagram,
            p.facebook,
            p.tiktok,
        FROM profiles p
        WHERE p.status = 'active'
        ORDER BY p.created_at DESC";
        
        return $this->qry($query)->fetchAll();
    }
}
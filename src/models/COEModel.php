<?php

class CoeModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $query = "SELECT 
            id,
            image,
            created_at,
            updated_at
        FROM coe
        ORDER BY created_at DESC";
        return $this->qry($query)->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT * FROM coe WHERE id = :id";
        return $this->qry($query, [':id' => $id])->fetch();
    }

    public function add_coe($data) {
        $query = "INSERT INTO coe (image)
                  VALUES (:image)";
        
        $params = [
            ':image' => $data['image']
        ];
        
        return $this->qry($query, $params);
    }

    public function update_coe($data) {
        $query = "UPDATE coe 
                  SET image = :image,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $params = [
            ':id' => $data['id'],
            ':image' => $data['image']
        ];
        
        return $this->qry($query, $params);
    }

    public function delete_coe($id) {
        // Get image path before deleting
        $querySelect = "SELECT image FROM coe WHERE id = :id";
        $coe = $this->qry($querySelect, [':id' => $id])->fetch();

        if (!$coe) {
            return false;
        }

        // Delete the record
        $queryDelete = "DELETE FROM coe WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->qry($queryDelete, $params);

        // Delete the image file if deletion was successful
        if ($result && !empty($coe['image'])) {
            $imagePath = '../public/' . $coe['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        return $result;
    }
}
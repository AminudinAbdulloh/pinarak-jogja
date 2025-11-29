<?php

class TouristObjectModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAll($limit = 6, $offset = 0, $search = '') {
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $query = "SELECT 
            id,
            title,
            article,
            image,
            category,
            address,
            google_map_link,
            created_at,
            updated_at
        FROM tourist_objects";
        
        $params = [];
        
        if (!empty($search)) {
            $query .= " WHERE (article LIKE ? 
                        OR category LIKE ? 
                        OR address LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $query .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
        
        return $this->qry($query, $params)->fetchAll();
    }

    public function countAll($search = '') {
        $query = "SELECT COUNT(*) as total FROM tourist_objects";
        
        if (!empty($search)) {
            $query .= " WHERE (article LIKE ? 
                        OR category LIKE ? 
                        OR address LIKE ?)";
            
            $searchParam = "%$search%";
            $result = $this->qry($query, [$searchParam, $searchParam, $searchParam])->fetch();
        } else {
            $result = $this->qry($query)->fetch();
        }
        
        return $result['total'];
    }

    public function getById($id) {
        $query = "SELECT 
            id,
            title,
            article,
            image,
            category,
            address,
            google_map_link,
            created_at,
            updated_at
        FROM tourist_objects
        WHERE id = :id";
        
        return $this->qry($query, [':id' => $id])->fetch();
    }

    public function getByCategoryWithLimit($category, $limit = 6) {
        $query = "SELECT 
            id,
            title,
            article,
            image,
            category,
            address,
            google_map_link,
            created_at,
            updated_at
        FROM tourist_objects
        WHERE category = :category
        ORDER BY created_at DESC
        LIMIT :limit";
        
        $stmt = $this->qry($query, [':category' => $category, ':limit' => $limit]);
        return $stmt->fetchAll();
    }

    public function add_tourist_object($data) {
        $query = "INSERT INTO tourist_objects (title, article, image, category, address, google_map_link) 
                  VALUES (:title, :article, :image, :category, :address, :google_map_link)";
        
        $params = [
            ':title' => $data['title'],
            ':article' => $data['article'],
            ':image' => $data['image'],
            ':category' => $data['category'],
            ':address' => $data['address'],
            ':google_map_link' => $data['google_map_link']
        ];
        
        return $this->qry($query, $params);
    }

    public function update_tourist_object($data) {
        $query = "UPDATE tourist_objects 
                  SET title = :title,
                      article = :article, 
                      image = :image, 
                      category = :category, 
                      address = :address, 
                      google_map_link = :google_map_link
                  WHERE id = :id";
        
        $params = [
            ':id' => $data['id'],
            ':title' => $data['title'],
            ':article' => $data['article'],
            ':image' => $data['image'],
            ':category' => $data['category'],
            ':address' => $data['address'],
            ':google_map_link' => $data['google_map_link']
        ];
        
        return $this->qry($query, $params);
    }

    public function delete_tourist_object($id) {
        $querySelect = "SELECT image FROM tourist_objects WHERE id = :id";
        $object = $this->qry($querySelect, [':id' => $id])->fetch();

        if (!$object) {
            return false;
        }

        if (!empty($object['image'])) {
            $filePath = __DIR__ . '/../../public/' . $object['image'];
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $queryDelete = "DELETE FROM tourist_objects WHERE id = :id";
        $params = [':id' => $id];

        return $this->qry($queryDelete, $params);
    }

    public function getCategories() {
        return ['Nature', 'Culture', 'Culinary', 'Religious', 'Adventure', 'Historical'];
    }
}
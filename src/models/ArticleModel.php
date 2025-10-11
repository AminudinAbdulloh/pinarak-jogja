<?php

class ArticleModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $query = "SELECT a.*, ad.username as author_name 
        FROM articles a 
        LEFT JOIN admins ad ON a.author_id = ad.id 
        ORDER BY a.created_at DESC";
        return $this->qry($query)->fetchAll();
    }

    public function getAllPublished() {
        $query = "SELECT a.*, ad.username as author_name 
        FROM articles a 
        LEFT JOIN admins ad ON a.author_id = ad.id 
        WHERE a.status = 'published'
        ORDER BY a.created_at DESC";
        return $this->qry($query)->fetchAll();
    }

    public function getRelatedArticles($excludeId, $limit = 3) {
        // pastikan integer agar aman dari injection
        $limit = (int)$limit;

        $query = "SELECT a.*, ad.username as author_name 
                FROM articles a 
                LEFT JOIN admins ad ON a.author_id = ad.id 
                WHERE a.status = 'published' AND a.id != :excludeId
                ORDER BY a.created_at DESC 
                LIMIT $limit"; // limit disisipkan langsung

        $stmt = $this->qry($query, [
            ':excludeId' => $excludeId
        ]);

        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT 
            a.*,
            ad.username as publisher_name
        FROM articles a
        LEFT JOIN admins ad ON a.author_id = ad.id
        WHERE a.id = :id";
        
        return $this->qry($query, [':id' => $id])->fetch();
    }

    public function getAllWithPagination($limit = 6, $offset = 0, $search = '') {
        // Pastikan limit dan offset adalah integer untuk keamanan
        $limit = (int)$limit;
        $offset = (int)$offset;

        $query = "SELECT a.*, ad.username AS author_name 
                FROM articles a 
                LEFT JOIN admins ad ON a.author_id = ad.id";

        $params = [];

        // Tambahkan kondisi search jika ada
        if (!empty($search)) {
            $query .= " WHERE (a.title LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
        }

        // Tambahkan ORDER BY, LIMIT, dan OFFSET di akhir
        $query .= " ORDER BY a.created_at DESC LIMIT $limit OFFSET $offset";

        return $this->qry($query, $params)->fetchAll();
    }

    public function countAll($search = '') {
        $query = "SELECT COUNT(*) as total FROM articles a";
        
        // Tambahkan kondisi search jika ada
        if (!empty($search)) {
            $query .= " WHERE (a.title LIKE ?)";
            
            $searchParam = "%$search%";
            $result = $this->qry($query, [$searchParam])->fetch();
        } else {
            $result = $this->qry($query)->fetch();
        }
        
        return $result['total'];
    }

    public function add_article($data) {
        $query = "INSERT INTO articles (title, content, excerpt, image, status, author_id) 
                  VALUES (:title, :content, :excerpt, :image, :status, :author_id)";
        
        $params = [
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':excerpt' => $data['excerpt'],
            ':image' => $data['image'],
            ':status' => $data['status'],
            ':author_id' => $data['author_id']
        ];
        
        return $this->qry($query, $params);
    }

    public function delete_article($id) {
        // Ambil data dulu untuk cek apakah ada file image yang harus dihapus
        $querySelect = "SELECT image FROM articles WHERE id = :id";
        $article = $this->qry($querySelect, [':id' => $id])->fetch();

        if (!$article) {
            // Jika data tidak ditemukan
            return false;
        }

        // Jika ada gambar, hapus file-nya dari folder uploads
        if (!empty($article['image'])) {
            // Dari src/models/ ke public/
            $filePath = __DIR__ . '/../../public/' . $article['image'];
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus data dari database
        $queryDelete = "DELETE FROM articles WHERE id = :id";
        $params = [':id' => $id];

        return $this->qry($queryDelete, $params);
    }

    public function update_article($data) {
        $query = "UPDATE articles 
                  SET title = :title, 
                      content = :content, 
                      excerpt = :excerpt, 
                      image = :image, 
                      status = :status,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $params = [
            ':id' => $data['id'],
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':excerpt' => $data['excerpt'],
            ':image' => $data['image'],
            ':status' => $data['status']
        ];
        
        return $this->qry($query, $params);
    }
}
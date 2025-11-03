<?php

class AdminModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getByUsername($username) {
        $query = "SELECT * FROM admins WHERE username = :username OR email = :username LIMIT 1";
        $params = [':username' => $username];
        $result = $this->qry($query, $params)->fetch();
        return $result;
    }

    public function getById($id) {
        $query = "SELECT * FROM admins WHERE id = :id LIMIT 1";
        $params = [':id' => $id];
        $result = $this->qry($query, $params)->fetch();
        return $result;
    }

    public function updateLastLogin($id) {
        $query = "UPDATE admins SET last_login = NOW() WHERE id = :id";
        $params = [':id' => $id];
        return $this->qry($query, $params);
    }

    public function createAdmin($data) {
        $query = "INSERT INTO admins (username, email, password, created_at) 
                  VALUES (:username, :email, :password, NOW())";
        
        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ];
        
        return $this->qry($query, $params);
    }

    // List admins with pagination and optional search
    public function getAll($limit = 10, $offset = 0, $search = '') {
        $limit = (int)$limit;
        $offset = (int)$offset;

        $query = "SELECT id, username, email, full_name, status, created_at, last_login
                  FROM admins";
        $params = [];

        if (!empty($search)) {
            $query .= " WHERE (username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
            $searchParam = "%$search%";
            $params = [$searchParam, $searchParam, $searchParam];
        }

        $query .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";

        return $this->qry($query, $params)->fetchAll();
    }

    public function countAll($search = '') {
        $query = "SELECT COUNT(*) as total FROM admins";
        $params = [];

        if (!empty($search)) {
            $query .= " WHERE (username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
            $searchParam = "%$search%";
            $params = [$searchParam, $searchParam, $searchParam];
        }

        $result = $this->qry($query, $params)->fetch();
        return $result['total'] ?? 0;
    }

    public function create($data) {
        $query = "INSERT INTO admins (username, email, password, full_name, status, created_at)
                  VALUES (:username, :email, :password, :full_name, :status, NOW())";
        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':full_name' => $data['full_name'],
            ':status' => $data['status'] ?? 'active'
        ];
        return $this->qry($query, $params);
    }

    public function update($data) {
        $query = "UPDATE admins SET username = :username, email = :email, full_name = :full_name, status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $params = [
            ':id' => $data['id'],
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':full_name' => $data['full_name'],
            ':status' => $data['status']
        ];
        return $this->qry($query, $params);
    }

    public function updatePassword($id, $newPassword) {
        $query = "UPDATE admins SET password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $params = [
            ':id' => $id,
            ':password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ];
        return $this->qry($query, $params);
    }

    public function delete($id) {
        $query = "DELETE FROM admins WHERE id = :id";
        $params = [':id' => $id];
        return $this->qry($query, $params);
    }

    public function isUsernameTaken($username, $excludeId = null) {
        $query = "SELECT id FROM admins WHERE username = :username";
        $params = [':username' => $username];
        if ($excludeId !== null) {
            $query .= " AND id != :excludeId";
            $params[':excludeId'] = $excludeId;
        }
        $res = $this->qry($query, $params)->fetch();
        return $res ? true : false;
    }

    public function isEmailTaken($email, $excludeId = null) {
        $query = "SELECT id FROM admins WHERE email = :email";
        $params = [':email' => $email];
        if ($excludeId !== null) {
            $query .= " AND id != :excludeId";
            $params[':excludeId'] = $excludeId;
        }
        $res = $this->qry($query, $params)->fetch();
        return $res ? true : false;
    }
}
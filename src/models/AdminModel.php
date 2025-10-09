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
}
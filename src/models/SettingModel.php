<?php

class SettingModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getSettings() {
        $query = "SELECT 
            id,
            logo_pinarak,
            logo_dinpar,
            banner,
            copyright,
            created_at,
            updated_at
        FROM settings
        LIMIT 1";
        
        $result = $this->qry($query)->fetch();
        
        // Decode JSON banner jika ada
        if ($result && !empty($result['banner'])) {
            $result['banner'] = json_decode($result['banner'], true);
        }
        
        return $result;
    }

    public function getById($id) {
        $query = "SELECT 
            id,
            logo_pinarak,
            logo_dinpar,
            banner,
            copyright,
            created_at,
            updated_at
        FROM settings
        WHERE id = :id";
        
        $result = $this->qry($query, [':id' => $id])->fetch();
        
        // Decode JSON banner jika ada
        if ($result && !empty($result['banner'])) {
            $result['banner'] = json_decode($result['banner'], true);
        }
        
        return $result;
    }

    public function updateSettings($data) {
        $query = "UPDATE settings 
          SET logo_pinarak = :logo_pinarak,
              logo_dinpar = :logo_dinpar,
              banner = :banner,
              copyright = :copyright,
              updated_at = CURRENT_TIMESTAMP
          WHERE id = :id";
        
        $params = [
            ':id' => $data['id'],
            ':logo_pinarak' => $data['logo_pinarak'],
            ':logo_dinpar' => $data['logo_dinpar'],
            ':banner' => $data['banner'], // JSON string
            ':copyright' => $data['copyright']
        ];
        
        return $this->qry($query, $params);
    }

    public function createDefaultSettings() {
        $query = "INSERT INTO settings (logo_pinarak, logo_dinpar, banner, copyright) 
                  VALUES (:logo_pinarak, :logo_dinpar, :banner, :copyright)";
        
        $params = [
            ':logo_pinarak' => null,
            ':logo_dinpar' => null,
            ':banner' => '[]',
            ':copyright' => '© 2025 Pinarak Jogja'
        ];
        
        return $this->qry($query, $params);
    }
}
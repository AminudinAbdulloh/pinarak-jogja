<?php

class ContactModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $query = "SELECT 
            id,
            company_name,
            phone_number,
            email,
            address,
            city,
            postal_code,
            gmaps_embed_url,
            working_days,
            working_time,
            youtube
            linkedin,
            instagram,
            facebook,
            tiktok,
            twitter
        FROM contacts";
        return $this->qry($query)->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT 
            id,
            company_name,
            phone_number,
            email,
            address,
            city,
            postal_code,
            gmaps_embed_url,
            working_days,
            working_time,
            youtube,
            linkedin,
            instagram,
            facebook,
            tiktok,
            twitter
        FROM contacts
        WHERE id = :id";
        
        return $this->qry($query, [':id' => $id])->fetch();
    }

    public function getContacts() {
        $query = "SELECT 
            id,
            company_name,
            phone_number,
            email,
            address,
            city,
            postal_code,
            gmaps_embed_url,
            working_days,
            working_time,
            youtube
            linkedin,
            instagram,
            facebook,
            tiktok,
            twitter
        FROM contacts
        LIMIT 1";
        
        $result = $this->qry($query)->fetch();
        
        // Decode JSON banner jika ada
        if ($result && !empty($result['banner'])) {
            $result['banner'] = json_decode($result['banner'], true);
        }
        
        return $result;
    }

    public function update_contact($data) {
        $query = "UPDATE contacts 
          SET company_name = :company_name,
              phone_number = :phone_number,
              email = :email,
              address = :address,
              city = :city,
              postal_code = :postal_code,
              gmaps_embed_url = :gmaps_embed_url,
              working_days = :working_days,
              working_time = :working_time,
              youtube = :youtube,
              linkedin = :linkedin,
              instagram = :instagram,
              facebook = :facebook,
              tiktok = :tiktok,
              twitter = :twitter,
              updated_at = CURRENT_TIMESTAMP
          WHERE id = :id";
        
        $params = [
            ':id' => $data['id'],
            ':company_name' => $data['company_name'],
            ':phone_number' => $data['phone_number'],
            ':email' => $data['email'],
            ':address' => $data['address'],
            ':city' => $data['city'],
            ':postal_code' => $data['postal_code'],
            ':gmaps_embed_url' => $data['gmaps_embed_url'],
            ':working_days' => $data['working_days'],
            ':working_time' => $data['working_time'],
            ':youtube' => $data['youtube'],
            ':instagram' => $data['instagram'],
            ':linkedin' => $data['linkedin'],
            ':facebook' => $data['facebook'],
            ':tiktok' => $data['tiktok'],
            ':twitter' => $data['twitter'],
        ];
        
        return $this->qry($query, $params);
    }
}
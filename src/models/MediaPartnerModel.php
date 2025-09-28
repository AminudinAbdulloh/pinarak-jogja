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
            mp.partnership_type,
            mp.description,
            mp.website,
            mp.display_order,
            mp.status,
            a.username as author_name
        FROM media_partners mp
        LEFT JOIN admins a ON mp.author_id = a.id
        WHERE mp.status = 'active'";
        return $this->qry($query)->fetchAll();
    }
}
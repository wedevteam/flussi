<?php

class Functions extends Model {
     public $db1;
     public $id;
     public $createdAt;
     
     
     
     
     function __construct() {
        $this->db1 = new Database();
    }
    
    
    
}
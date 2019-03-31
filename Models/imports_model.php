<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Imports_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $createdAt;
    public $status;
    public $note;
    public $fileName;
    
    
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getImportsList() {
        $data = ' * ';
        $where = ' status!=:statusDeleted ';
            $parameters = array(
                ':statusDeleted' => 'deleted'
            );
        $orderBy = " id DESC ";
        $result = $this->db->selectWithOrder(TAB_IMPORTS, $data, $where, $parameters, false, $orderBy, NULL);
        return $result;
    }
    
    
    // INSERT - Nuovo record (Return ID)
    public function create($data) {
        // Execute
        $this->db->insert(TAB_IMPORTS, $data);
        return $this->db->lastInsertId();
    }
    
    
    
}



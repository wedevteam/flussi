<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class DbStrade_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $nome;
    public $status;
    public $note;
    
    
    // Costruttore
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getDbStradeList() {
        $data = ' * ';
        $where = ' status=:status ';
        $parameters = array();
        $parameters[":status"] = 'on';
        $orderBy = " nome ";
        
        $result = $this->db->selectWithOrder(TAB_DB_STRADE, $data, $where, $parameters, false, $orderBy, NULL);
        
        // Return
        return $result;
    }
    
}



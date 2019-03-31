<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class DbCucine_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $descrizione;
    public $status;
    
    
    // Costruttore
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getDbCucineList() {
        $data = ' * ';
        $where = ' status=:status ';
        $parameters = array();
        $parameters[":status"] = 'on';
        $orderBy = " descrizione ";
        
        $result = $this->db->selectWithOrder(TAB_DB_TIPO_CUCINE, $data, $where, $parameters, false, $orderBy, NULL);
        
        // Return
        return $result;
    }
    
}



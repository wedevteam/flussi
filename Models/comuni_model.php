<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Comuni_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $nome;
    public $siglaprovincia;
    public $codice_catastale;
    public $codice_istat;
    
    
    // Costruttore
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getComuniList() {
        $data = ' * ';
        $where = ' siglaprovincia!=:siglaprovincia ';
        $parameters = array();
        $parameters[":siglaprovincia"] = 'EE';
        $orderBy = " nome ";
        
        $result = $this->db->selectWithOrder(TAB_COMUNI, $data, $where, $parameters, false, $orderBy, NULL);
        
        // Return
        return $result;
    }
    
    
}



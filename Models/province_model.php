<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Province_Model extends Model {

    //Proprietà Oggetto
    public $sigla;
    public $nome;
    public $capoluogo_provincia;
    public $codiceistatregione_provincia;
    public $codiceistat_provincia;
    public $idRegione;
    
    
    // Costruttore
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getProvinceList() {
        $data = ' * ';
        $where = ' sigla!=:sigla ';
        $parameters = array();
        $parameters[":sigla"] = 'EE';
        $orderBy = " sigla ";
        
        $result = $this->db->selectWithOrder(TAB_PROVINCE, $data, $where, $parameters, false, $orderBy, NULL);
        
        // Return
        return $result;
    }
    
    
}




<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Cap_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $nomeComune;
    public $codiceIstat;
    public $cap;


    // Costruttore
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getCapList() {
        $data = ' * ';
        $where = '';
        $parameters = array();
        $orderBy = " cap ";

        $result = $this->db->selectWithOrder(TAB_CAP, $data, $where, $parameters, false, $orderBy, NULL);

        // Return
        return $result;
    }


}





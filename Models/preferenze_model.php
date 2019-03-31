<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Preferenze_Model extends Model {
    
        
    function __construct() {
        parent::__construct();
    }

    
    
    /* ----------------------------------------------
     * Funzioni di UPDATE
      ---------------------------------------------- */
    // Edit Dati 
    public function updateDataTabUsers($data,$parameters,$where) {
        $this->db->update(TAB_USERS, $data, $where, $parameters);
        return true;
    }
    
    
    
    
    
}



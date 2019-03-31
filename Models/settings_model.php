<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Settings_Model extends Model {
    
        
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getPlatformData() {
        $data = ' * ';
        $where = ' id=:id ';
            $parameters = array(
                ':id' => 1
            );
        $orderBy = " id ";
        return $this->db->selectWithOrder(TAB_PLATFORM, $data, $where, $parameters, false, $orderBy, NULL);
    }
    
    
    
    /* ----------------------------------------------
     * Funzioni di UPDATE
      ---------------------------------------------- */
    // Edit Dati 
    public function updateData($data,$parameters,$where) {
        $this->db->update(TAB_PLATFORM, $data, $where, $parameters);
        return true;
    }
    
    
    
    
    
}



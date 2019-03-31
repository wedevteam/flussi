<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class RelAgenziePref_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $idAgenzia;
    public $tipoPreferenza;
    public $idOggetto;
    public $status;
    
    // Costruttore
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getRelAgenziePrefList($idAgenzia,$tipoPreferenza,$orderBy) {
        $data = ' * ';
        $where = ' status!=:statusDeleted ';
        $parameters[":statusDeleted"] = 'deleted';
        if ($idAgenzia!=NULL ) {
            $where .= ' AND idAgenzia=:idAgenzia ';
            $parameters[":idAgenzia"] = $idAgenzia;
        }
        if ($tipoPreferenza!=NULL ) {
            $where .= ' AND tipoPreferenza=:tipoPreferenza ';
            $parameters[":tipoPreferenza"] = $tipoPreferenza;
        }
        if ($orderBy==NULL) {
            $orderBy = " id DESC ";
        }
        $result = $this->db->selectWithOrder(TAB_REL_AGENZIE_PREF, $data, $where, $parameters, false, $orderBy, NULL);
        
        // Return
        return $result;
    }
    
    
    // INSERT - Nuovo record (Return ID)
    public function create($data) {
        // Execute
        $this->db->insert(TAB_REL_AGENZIE_PREF, $data);
        return $this->db->lastInsertId();
    }
    
    // UPDATE - Aggiorna dati 
    public function updateData($data,$parameters,$where) {
        // Execute
        $this->db->update(TAB_REL_AGENZIE_PREF, $data, $where, $parameters);
        return true;
        
    }
    
    
    
    // SELECT - Get Data from ID
    public function getDataFromId($id) {
        $data = ' * ';
        $where = '  id=:id AND status!=:statusDeleted ';
        $parameters = array();
        $parameters[":id"] = $id;
        $parameters[":statusDeleted"] = "deleted";
        $result = $this->db->select(TAB_REL_AGENZIE_PREF, $data, $where, $parameters, true);
        return $result;
    }
    
    
    // SELECT - Get Data from ID
    public function getDataFromIdAgTipoPref($idAgenzia,$tipoPreferenza) {
        $data = ' * ';
        $where = '  id=:id AND status!=:statusDeleted AND idAgenzia=:idAgenzia AND tipoPreferenza=:tipoPreferenza ';
        $parameters = array();
        $parameters[":id"] = $id;
        $parameters[":statusDeleted"] = "deleted";
        $parameters[":idAgenzia"] = $idAgenzia;
        $parameters[":tipoPreferenza"] = $tipoPreferenza;
        $result = $this->db->select(TAB_REL_AGENZIE_PREF, $data, $where, $parameters, true);
        return $result;
    }
    
}



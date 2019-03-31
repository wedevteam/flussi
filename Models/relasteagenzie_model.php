<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class RelAsteAgenzie_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $idAsta;
    public $idAgenzia;
    public $riferimentoAnnuncio;
    public $descrizione;
    public $flagPubblicita;
    public $nomeAgente;
    public $noteAgenzia;
    public $commentiAgenzia;
    public $preferenzaPrezzo;
    public $prezzoPersonalizzato;
    public $flagRichiestaVisione;
    public $dataRichiestaVisione;
    public $oraRichiestaVisione;
    public $immagine_URL;
    public $isImgModificata;
    public $statusImportazione;
    public $dataUltimaEsportazione_d;
    public $dataUltimaEsportazione;
    public $DataModifica_d;
    public $DataModifica;
    public $status;
    public $isNoty;
    
    
    // Costruttore
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getRelAsteAgenzieList($idAgenzia,$idAsta,$orderBy) {
        $data = ' * ';
        $where = ' status!=:statusDeleted ';
        $parameters = array();
        $parameters[":statusDeleted"] = 'deleted';
        if ($idAgenzia!=NULL ) {
            $where .= ' AND idAgenzia=:idAgenzia ';
            $parameters[":idAgenzia"] = $idAgenzia;
        }
        if ($idAsta!=NULL ) {
            $where .= ' AND idAsta=:idAsta ';
            $parameters[":idAsta"] = $idAsta;
        }
        if ($orderBy==NULL) {
            $orderBy = " id DESC ";
        }
        $result = $this->db->selectWithOrder(TAB_REL_ASTE_AGENZIE, $data, $where, $parameters, false, $orderBy, NULL);
        
        // Return
        return $result;
    }
    
    
    // INSERT - Nuovo record (Return ID)
    public function create($data) {
        // Execute
        $this->db->insert(TAB_REL_ASTE_AGENZIE, $data);
        return $this->db->lastInsertId();
    }
    
    // UPDATE - Aggiorna dati 
    public function updateData($data,$parameters,$where) {
        // Execute
        $this->db->update(TAB_REL_ASTE_AGENZIE, $data, $where, $parameters);
        return true;
        
    }
    
    
    
    // SELECT - Get Data from ID
    public function getDataFromId($id) {
        $data = ' * ';
        $where = '  id=:id AND status!=:statusDeleted AND status!=:statusError ';
        $parameters = array();
        $parameters[":id"] = $id;
        $parameters[":statusDeleted"] = "deleted";
        $parameters[":statusError"] = "error";
        $result = $this->db->select(TAB_REL_ASTE_AGENZIE, $data, $where, $parameters, true);
        return $result;
    }
    
    
    // SELECT - Get Data from ID
    public function getDataFromIdAgIdAsta($idAgenzia,$idAsta) {
        $data = ' * ';
        $where = '  status!=:statusDeleted AND status!=:statusError AND idAgenzia=:idAgenzia AND idAsta=:idAsta ';
        $parameters = array();
        $parameters[":statusDeleted"] = "deleted";
        $parameters[":statusError"] = "error";
        $parameters[":idAgenzia"] = $idAgenzia;
        $parameters[":idAsta"] = $idAsta;
        $result = $this->db->select(TAB_REL_ASTE_AGENZIE, $data, $where, $parameters, true);
        return $result;
    }
    
}



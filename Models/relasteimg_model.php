<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class RelAsteImg_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $idAsta;
    public $idAgenzia;
    public $fonte;
    public $immagine_URL;
    public $immagine_Posizione;
    public $immagine_TipoFoto;
    public $immagine_Titolo;
    public $DataModifica_d;
    public $DataModifica;


    // Costruttore
    function __construct() {
        parent::__construct();
    }

    // INSERT - Nuovo record (Return ID)
    public function create($data) {
        // Execute
        $this->db->insert(TAB_REL_ASTE_IMG, $data);
        return $this->db->lastInsertId();
    }

    // Get User list
    public function getRelAsteImgList($idAsta,$idAgenzia,$userRole) {
        $data = ' * ';
        $where = ' idAsta=:idAsta ';
        $parameters = array();
        $parameters[":idAsta"] = $idAsta;
        if ($userRole=="admin") {
            $where .= ' AND idAgenzia=:idAgenzia ';
            $parameters[":idAgenzia"] = $idAgenzia;
        } else {
            $where .= ' AND (fonte=:fonteCsv OR idAgenzia=:idAgenziaZero OR (fonte=:fonteManuale AND idAgenzia=:idAgenzia) ) ';
            $parameters[":fonteCsv"] = "csv";
            $parameters[":idAgenziaZero"] = 0;
            $parameters[":fonteManuale"] = "manuale";
            $parameters[":idAgenzia"] = $idAgenzia;
        }
        $result = $this->db->selectWithOrder(TAB_REL_ASTE_IMG, $data, $where, $parameters, false, NULL, NULL);

        // Return
        return $result;
    }

    // Get User list
    public function getRelAsteImgAllList() {
        $data = ' * ';
        $where = '';
        $parameters = array();

        $result = $this->db->selectWithOrder(TAB_REL_ASTE_IMG, $data, $where, $parameters, false, NULL, NULL);

        // Return
        return $result;
    }


//
//    // UPDATE - Aggiorna dati
//    public function updateData($data,$parameters,$where) {
//        // Execute
//        $this->db->update(TAB_REL_ASTE_IMG, $data, $where, $parameters);
//        return true;
//
//    }
//
//
//
    // SELECT - Get Data from ID
    public function getDataFromId($id,$idAgenzia) {
        $data = ' * ';
        $where = '  id=:id AND idAgenzia=:idAgenzia AND fonte=:fonte ';
        $parameters = array();
        $parameters[":id"] = $id;
        $parameters[":idAgenzia"] = $idAgenzia;
        $parameters[":fonte"] = "manuale";
        $result = $this->db->select(TAB_REL_ASTE_IMG, $data, $where, $parameters, true);
        return $result;
    }
    // SELECT - GET DATA
    public function getDataFromIdPerAgenzia($id, $idAsta, $idAgenzia) {
        $data = ' * ';
        $where = '  id=:id AND idAsta=:idAsta AND idAgenzia=:idAgenzia AND fonte=:fonte ';
        $parameters = array();
        $parameters[":id"] = $id;
        $parameters[":idAsta"] = $idAsta;
        $parameters[":idAgenzia"] = $idAgenzia;
        $parameters[":fonte"] = "manuale";

        $result = $this->db->select(TAB_REL_ASTE_IMG, $data, $where, $parameters, true);
        return $result;
    }

    // SELECT - Get Data from ID
    public function getDataFromAdmin($id,$idAsta) {
        $data = ' * ';
        $where = '  id=:id AND idAsta=:idAsta AND idAgenzia=:idAgenzia ';
        $parameters = array();
        $parameters[":id"] = $id;
        $parameters[":idAsta"] = $idAsta;
        $parameters[":idAgenzia"] = 0;

        $result = $this->db->select(TAB_REL_ASTE_IMG, $data, $where, $parameters, true);
        return $result;
    }

    // DELETE - delete id
    public function deleteItem($where,$parameters) {
        $this->db->delete(TAB_REL_ASTE_IMG,$where,$parameters);
        return true;
    }



}



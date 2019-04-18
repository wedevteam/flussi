<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Exports_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $createdAt;
    public $status;
    public $numAgenzie;
    public $numAste;
    
    
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getExportsList() {
        $data = ' * ';
        $where = ' status!=:statusDeleted ';
        $parameters = array(
            ':statusDeleted' => 'deleted'
        );
        $orderBy = " id DESC ";
        return $this->db->selectWithOrder(TAB_EXPORTS, $data, $where, $parameters, false, $orderBy, NULL);
    }

    // Get User list
    public function getExportsListByStatus($status) {
        $data = ' * ';
        $where = ' status!=:statusDeleted ';
        $parameters = array();
        $parameters[":statusDeleted"] = 'deleted';
        if ($status!=null) {
            $where .= ' AND status=:status ';
            $parameters[":status"] = $status;
        }
        $orderBy = " id DESC ";
        return $this->db->selectWithOrder(TAB_EXPORTS, $data, $where, $parameters, false, $orderBy, NULL);
    }
    
    // INSERT - Nuovo record (Return ID)
    public function create($data) {
        // Execute
        $this->db->insert(TAB_EXPORTS, $data);
        return $this->db->lastInsertId();
    }

    // UPDATE - Aggiorna dati
    public function updateData($data,$parameters,$where) {
        // Execute
        $this->db->update(TAB_EXPORTS, $data, $where, $parameters);
        return true;

    }
    
    // SELECT - Get Data from ID
    public function getDataFromId($id) {
        $data = ' * ';
        $where = '  id=:id AND status!=:statusDeleted ';
        $parameters = array();
        $parameters[":id"] = $id;
        $parameters[":statusDeleted"] = "deleted";
        $result = $this->db->select(TAB_EXPORTS, $data, $where, $parameters, true);
        return $result;
    }
    
    
}





<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class ExportDetails_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $idExport;
    public $idAgenzia;
    public $idAsta;
    public $createdAt;
    
    
    function __construct() {
        parent::__construct();
    }

    // Get list
    public function getExportDetList($idExport) {
        $data = ' * ';
        $where = "";
        if ($idExport!=Null) {
            $where .= ' idExport=:idExport ';
            $parameters[":idExport"] = $idExport;
        }
        $orderBy = " id DESC ";
        return $this->db->selectWithOrder(TAB_EXPORT_DETAILS, $data, $where, $parameters, false, $orderBy, NULL);
    }
    
    
    // INSERT - Nuovo record (Return ID)
    public function create($data) {
        // Execute
        $this->db->insert(TAB_EXPORT_DETAILS, $data);
        return $this->db->lastInsertId();
    }
    
    
    
}






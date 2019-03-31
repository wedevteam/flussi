<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class ImportDetails_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $idImport;
    public $idAsta;
    public $uploadType;
    public $uploadValido;
    public $uploadErrorsTxt;
    
    
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getImportDetailsList($idImport) {
        $data = ' * ';
        $where = ' idImport!=:idImport ';
            $parameters = array(
                ':idImport' => $idImport
            );
        $orderBy = " id DESC ";
        $result = $this->db->selectWithOrder(TAB_IMPORT_DETAILS, $data, $where, $parameters, false, $orderBy, NULL);
        return $result;
    }
    
    
    // INSERT - Nuovo record (Return ID)
    public function create($data) {
        // Execute
        $this->db->insert(TAB_IMPORT_DETAILS, $data);
        return $this->db->lastInsertId();
    }
    
    
    
}




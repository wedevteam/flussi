<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Agency_Model extends Model {
    
        
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getUsersListByRole($role) {
        $data = ' * ';
        if ($role==null) {
            $where = ' status!=:statusDeleted ';
            $parameters = array(
                ':statusDeleted' => 'deleted'
            );
        } else {
            $where = ' status!=:statusDeleted AND role=:role ';
            $parameters = array(
                ':statusDeleted' => 'deleted',
                ':role' => $role
            );
        }
        $orderBy = " companyName ";
        return $this->db->selectWithOrder(TAB_USERS, $data, $where, $parameters, false, $orderBy, NULL);
    }
    
    
    // CHECK DUPLICATI (obbligatori: ROLE, EMAIL)
    public function checkDuplicate($role,$email,$idAgenzia,$id) {
        $data = ' * ';
        $where = '  email=:email ';
        $parameters = array();
        $parameters[":email"] = $email;
//        if ($role=="agency") {
//            $where .= ' OR IdAgenzia=:IdAgenzia ';
//            $parameters[":IdAgenzia"] = $idAgenzia;
//           
//        }
        if ($id!=null) {
            $where .= ' AND id!=:id ';
            $parameters[":id"] = $id;
        } 
        $numRecords = $this->db->selectCount(TAB_USERS, $data, $where, $parameters);
        if ($numRecords==0) {
            return true;
        } else {
            return false;
        }
    }
    
    
    // INSERT - Nuovo record (Return ID)
    public function create($data) {
        
        // Execute
        $this->db->insert(TAB_USERS, $data);
        return $this->db->lastInsertId();
    }
    
    
    /* ----------------------------------------------
     * Funzioni di UPDATE
      ---------------------------------------------- */
    // Edit Dati 
    public function updateData($data,$parameters,$where) {
        $this->db->update(TAB_USERS, $data, $where, $parameters);
        return true;
    } 
    
    
    // SELECT - Get Data from ID
    public function getDataFromId($id,$role) {
        $data = ' * ';
        $where = '  id=:id AND status!=:statusDeleted AND role=:role';
        $parameters = array();
        $parameters[":id"] = $id;
        $parameters[":statusDeleted"] = "deleted";
        $parameters[":role"] = $role;
        $result = $this->db->select(TAB_USERS, $data, $where, $parameters, true);
        return $result;
    }
    
    
    
    
}


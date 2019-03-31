<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Profile_Model extends Model {

    function __construct() {
        parent::__construct();
    }

    // Get User list
//    public function getUsersListByRole($role) {
//        $data = ' * ';
//        if ($role==null) {
//            $where = ' status!=:statusDeleted ';
//            $parameters = array(
//                ':statusDeleted' => 'deleted'
//            );
//        } else {
//            $where = 'status!=:statusDeleted AND role=:role';
//            $parameters = array(
//                ':statusDeleted' => 'deleted',
//                ':role' => $role
//            );
//        }
//        $orderBy = " companyName ";
//        $this->db->selectWithOrder(TAB_USERS, $data, $where, $parameters, false, $orderBy, NULL);
//    }
    
}


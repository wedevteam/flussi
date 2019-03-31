<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Dashboard_Model extends Model {

    function __construct() {
        parent::__construct();
    }

    // Set Page values
    function setPaginaDashboard() {
        // Functions
        $instance = new Functions();
        
        $result = array();
        
        return $result;
    }
    
    // Set Num. Utenti per ruolo (se null li prende tutti)
    public function getNumUsersPerRuolo($role) {
        $data = 'id';
        if ($role==null) {
            $where = 'status!=:statusDeleted';
            $parameters = array(
                ':statusDeleted' => 'deleted'
            );
        } else {
            $where = 'status!=:statusDeleted AND role=:role';
            $parameters = array(
                ':statusDeleted' => 'deleted',
                ':role' => $role
            );
        }
        
        return $this->db->selectCount(USER_TABLE, $data, $where, $parameters);
    }
    
    // Set Num. Poker rooms
    public function getNumPokerRooms() {
        $data = 'id';
        $where = 'status!=:statusDeleted';
        $parameters = array(
            ':statusDeleted' => 'deleted'
        );
        
        return $this->db->selectCount(POKER_ROOMS_TABLE, $data, $where, $parameters);
    }
    
    // Set Num. Conti gioco
    public function getNumGameAccounts() {
        $data = 'id';
        $where = 'status!=:statusDeleted';
        $parameters = array(
            ':statusDeleted' => 'deleted'
        );
        
        return $this->db->selectCount(GAME_ACCOUNTS_TABLE, $data, $where, $parameters);
    }
    
    // Set Num. Providers
    public function getNumProviders() {
        $data = ' id ';
        $where = 'status!=:statusDeleted';
        $parameters = array(
            ':statusDeleted' => 'deleted'
        );
        
        return $this->db->selectCount(PROVIDERS_TABLE, $data, $where, $parameters);
    }
    
    // Set Num. Servers
    public function getNumServers() {
        $data = 'id';
        $where = 'status!=:statusDeleted';
        $parameters = array(
            ':statusDeleted' => 'deleted'
        );
        
        return $this->db->selectCount(SERVERS_TABLE, $data, $where, $parameters);
    }
}

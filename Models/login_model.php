<?php

/*
 * Concesso in licenza d'uso a STAKING SCHOOL
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Login_Model extends Model {

    function __construct() {
        parent::__construct();
    }
    
    // Get dai Platform e Admin Settings
    public function getPlatform() {
        return $this->db->select(TABLE_PLATFORM, '*', 'id = 1', null, true);
    }
    
    
    
    
    
    // ACCEDI
    public function run($platform) {
        // Functions
        $instance = new Functions();
        // Trova Utente
        $data = '*';
        $where = 'username=:username AND password=:password ';
        $parameters = array(
            ':username' => $_POST['username'],
            ':password' => Hash::create('sha256', $_POST['password'], HASH_KEY)
        );
        
        
        
        $user = $this->db->select(USER_TABLE, $data, $where, $parameters, true);
        $_loginStatus = false;
        if ( $user!=null) {
            // Check Status
            if ( $instance->getStatusValueOfSpecificDate('user', $user['id'], date('Y-m-d'))=='on' ) {
                $_loginStatus=true;
                if ( $user['role']!='student' && $platform['checkIpAddress']=='on' ) {
                    // Get ipAddress
                    $ipAddress = Functions::getIpAddress();
                    if ( $user['ipAddress'] == $ipAddress) {
                        $_loginStatus=true;
                    } else {
                        $_loginStatus=false;
                    }
                } 
                if ( $user['role']=='student') {
                        $_loginStatus=true;
                }
            }
        } 
        
        // Accesso o Errore
        if ( $_loginStatus ) {
            // Accesso
            Session::init();
            Session::set('role', $user['role']);
            Session::set('loggedIn', true);
            Session::set(USER,$user);
            Functions::updateSessionTime();
            Functions::redirectToAction('dashboard/index');
        } else {
            // Error
            $error = ERROR_LOGIN;
            Functions::redirectToAction('login/index/'.($error));
        }
    }
    
    // Verifica Email
    public function verifyEmail($email) {
        $status = 'on';
        $data = 'id';
        $where = 'email=:email AND status=:status';
        $parameters = array(
            ':email' => $email,
            ':status' => $status
        );
        return $this->db->select(USER_TABLE, $data, $where, $parameters, true);
    }
    
    // Verifica HASH
    public function verifyHash($hash) {
        $data = 'id, username';
        $result = $this->db->select(USER_TABLE, $data, null, null, false);
        $found = false;
        $id = -1;
        foreach ($result as $value) {
            $hashUsername = Hash::create('sha256', $value['username'], HASH_GENERAL_KEY);
            if ($hash==$hashUsername) {
                return $value;
            }
        }
        return null;
    }
    
    // Reset Password
    public function reset($id,$pw){
        $data = array(
            ':password' => Hash::create('sha256', $pw, HASH_KEY)
        );
        $parameters = array(
            ':id' => $id
        );
        $where = 'id = :id';
        $this->db->update(USER_TABLE, $data, $where, $parameters);
    }
}

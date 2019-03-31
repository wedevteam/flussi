<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Login extends Controller {
    // Connessione
    public $dbConn;
    
    function __construct() {
        parent::__construct();
        // Connession
        $this->dbConn = new Database();
        // Get Basic Data
        $functions = new Functions();
        $this->view->platformData = $functions->getPlatformData();
    }

    // GET: Index
    public function index($error = null) {
        // Get Err
        $this->view->error = Functions::getError($error);
        
        // Init session
        Session::init();
        Session::set(PLATFORM,$this->view->platformData);
        
        // Return
        $this->view->render('login/index', true, HEADER_LOGIN);
    }
    // POST: Execute Login
    public function executeLogin(){
        // Trova Utente
        $data = '*';
        $where = ' email=:email AND password=:password AND status=:statusOn ';
        $parameters = array(
            ':email' => $_POST['email'],
            ':password' => Hash::create('sha256', $_POST['password'], HASH_KEY),
            ':statusOn' => 'on'
        ); 
        $loggedUser = $this->dbConn->select(TAB_USERS, $data, $where, $parameters, true);
        
        // Accesso o Errore
         if ( $loggedUser!=null) {
            // Accesso
            Session::init();
            Session::set('role', $loggedUser['role']);
            Session::set('loggedIn', true);
            Session::set(USER,$loggedUser);
            Functions::updateSessionTime();
            if ($loggedUser['role']=='admin') { 
                Functions::redirectToAction('dashboard/index');
                // Functions::redirectToAction('aste/index');
            } else {
                Functions::redirectToAction('aste/index');
            }
        } else {
            // Error
            $error = ERR_LOGIN;
            Functions::redirectToAction('login/index/'.($error));
        }
        
    }
    
    
    // GET: Forgot
    public function forgot($error = null) {
        // Get Err
        $this->view->error = Functions::getError($error);
        
        // Init Session
        Session::init();
        Session::set(PLATFORM,$this->view->platformData);
        
        // Return
        $this->view->render('login/forgot', true, HEADER_LOGIN);
    }

    // POST: 
    public function sendEmailForgot() {
        $emailToCheck = $_POST['email'];
        
        // CHeck email exists
        $data = '*';
        $where = ' email=:email AND password=:password AND status=:statusOn ';
        $parameters = array(
            ':email' => $emailToCheck,
            ':statusOn' => 'on'
        ); 
        $loggedUser = $this->dbConn->select(TAB_USERS, $data, $where, $parameters, true);
        if ($loggedUser==null) {
            Functions::redirectToAction('login/forgot/' . urlencode(ERROR_NOEMAIL));
            return FALSE;
        } 
        
        
        // Set valori per email
        $hash = Hash::create('sha256', $emailToCheck, HASH_GENERAL_KEY);
        $logo = $this->platformData['logo'];
        $siteTitle = $this->platformData['siteName'];
        $emailFirmaTesto = $this->platformData['emailSign'];
        if ( DEBUG_ATTIVO ) {
           $httpPath = $this->platformData['debugPathHttp']; 
        } else {
           $httpPath = $this->platformData['officialPathHttp']; 
        }
        // Invio EMAIL User
        $sbj = $this->platformData['siteName'].' | '.EMAIL_RECUPERO_PW;
        include ('public/templates/email_forgot.php');
        $headers = "From: " . $this->platformData['emailFrom'] . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($emailToCheck, $sbj, $msg, $headers);
        
        // Redirect
        Session::init();
        Session::set(PLATFORM,$this->view->platformData);
        Functions::redirectToAction('login/emailSent');
    }
    
    // GET: ResetPassword
    public function resetPassword($hash, $error = null) {
        $user = $this->model->verifyHash($hash);
        if ($user == null) {
            Functions::redirectToAction('login/err/' . ERROR_HASH);
            return FALSE;
        }
        $this->view->model = $user;
        $this->view->error = Functions::getError($error);
        $this->view->render('login/reset', true, HEADER_LOGIN);
    }

    // POST: Reset password
    public function reset() {
        $this->model->reset($_POST['id'], $_POST['password1']);
       $this->view->render('login/pwchanged', true, HEADER_LOGIN);
    }
    

    public function err() {
        $this->view->render('login/err', true, HEADER_LOGIN);
    }

    public function emailSent() {
        $this->view->render('login/sent', true, HEADER_LOGIN);
    }

}

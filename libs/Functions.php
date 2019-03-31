<?php

/*
 * Concesso in licenza d'uso a FA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */



class Functions extends Model {

    public $db1;

    function __construct() {
        $this->db1 = new Database();
    }

    /*
     * -----------------------------------------
     * FUNZIONI DI BASE
     * -----------------------------------------
     */

    // ROUTING - Redirect ($action è nella forma {controller}/action/{args}
    public static function redirectToAction($action) {
        header('location: ' . URL . $action);
    }

    // SESSION - Imposta la durata della sessione
    public static function updateSessionTime() {
        return;
        $time = time();
        $timeout_duration = SESSION_DURATION * 60;
        if (isset($_SESSION[SESSION_LAST]) &&
                ($time - Session::get(SESSION_LAST)) > $timeout_duration) {
            session_unset();
            Session::destroy();
            Session::init();
        }
        Session::set(SESSION_LAST, $time);
    }

    /*
     * -----------------------------------------
     * FUNZIONI GENERICHE
     * -----------------------------------------
     */
    /* Converte DATE IN VARI FORMATI */

    public static function transformDateFormat($requestType, $dateString) {
        switch ($requestType) {
            case 1:
                // From gg/mm/aaaa To yyyy-mm-dd
                return substr($dateString, 6, 4) . '-' . substr($dateString, 3, 2) . '-' . substr($dateString, 0, 2);
                break;
            case 2:
                // From yyyy-mm-dd To gg/mm/aaaa
                return substr($dateString, 8, 2) . '/' . substr($dateString, 5, 2) . '/' . substr($dateString, 0, 4);
                break;
            case 3:
                // From gg/mm To mm-dd
                return substr($dateString, 3, 2) . '-' . substr($dateString, 0, 2);
                break;
            case 4:
                // From mm/aaaa To aaaa-mm-dd (ultimo giorno del mese
                $aaaaMm = substr($dateString, 3, 4) . '-' . substr($dateString, 0, 2);
                $a_date = $aaaaMm . "-01";
                $result = date("Y-m-t", strtotime($a_date));
                return $result;
                break;
            default:
                return '';
                break;
        }

        // Return
        return null;
    }

    /* Restituisce desc RUOLO USER */

    public static function getRoleDesc($role) {
        switch ($role) {
            case 'superadmin':
                return 'Super Admin';
                break;
            case 'admin':
                return 'Admin';
                break;
            case 'student':
                return 'Allievo';
                break;
            case 'studentCoach':
                return 'Allievo-Coach';
                break;
            default:
                return '-';
                break;
        }
        // Return
        return null;
    }

   //=========================================================================
    //Trova COORDINATE GEOGRAFICHE da città(Prov)+Indirizzo
    //=========================================================================
    function getLatLonFromAddress($address) {
        $coordinate='';
        $addressNew = str_replace(" ", "+", $address); // replace all the white space with "+" sign to match with google search pattern
        $url = "https://maps.google.com/maps/api/geocode/json?sensor=false&address=$addressNew&key=AIzaSyBDZgf6d5IkFf4SQ2Oen9X_b0GzKX5_5jk";
        $response = file_get_contents($url);
        $json = json_decode($response,TRUE); //generate array object from the response from the web
        $coordinate= ($json['results'][0]['geometry']['location']['lat'].",".$json['results'][0]['geometry']['location']['lng']);
        if ($coordinate==',') {
            $coordinate='';
        } 
        return $coordinate;
    }

    
    /* Restituisce Indirizzo IP con cui è connesso utente */

    public static function getIpAddress() {
        $ipAddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipAddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipAddress = 'UNKNOWN';
        return $ipAddress;
    }

    /* Gestione MESSAGGI ERRORE */

    public static function getError($errCode) {
        switch ($errCode) {
            case ERR_LOGIN:
                return ERR_LOGIN_TEXT;
                break;
            case ER_AGENCY_DUPLICATE:
                return ER_AGENCY_DUPLICATE_TEXT;
                break;
            case ER_AGENCY_COMUNE_INVALID:
                return ER_AGENCY_COMUNE_INVALID_TEXT;
                break;
            case ER_AGENCY_COMUNE_INVALID:
                return ER_AGENCY_COMUNE_INVALID_TEXT;
                break;
            case ER_IMPORT_INVALID:
                return ER_IMPORT_INVALID_TEXT;
                break;
            case ER_IMPORT_NORECORDS:
                return ER_IMPORT_NORECORDS_TEXT;
                break;
            case ER_IMPORT_SAVE:
                return ER_IMPORT_SAVE_TEXT;
                break;
            case ER_EXPORT_INVALID:
                return ER_EXPORT_INVALID_TEXT;
                break;
            case ER_EXPORT_INVALID_SENDXML:
                return ER_EXPORT_INVALID_SENDXML_TEXT;
                break;
            case ER_EXPORT_AGENZIE_NONPRESENTI:
                return ER_EXPORT_AGENZIE_NONPRESENTI_TEXT;
                break;
            case ER_EXPORT_IMM_NONPRESENTI:
                return ER_EXPORT_IMM_NONPRESENTI_TEXT;
                break;
            case ER_ASTA_EDIT_GENERIC:
                return ER_ASTA_EDIT_GENERIC_TEXT;
                break;
            case ER_ASTA_EDIT_DATAVISIONE:
                return ER_ASTA_EDIT_DATAVISIONE_TEXT;
                break;
            case ER_UPLOADFILE_FILENONVALIDO:
                return ER_UPLOADFILE_FILENONVALIDO_TEXT;
                break;
            case ER_UPLOADFILE_ESTENSIONENONVALIDA:
                return ER_UPLOADFILE_ESTENSIONENONVALIDA_TEXT;
                break;
            case ER_UPLOADFILE_SIZENONVALIDA:
                return ER_UPLOADFILE_SIZENONVALIDA_TEXT;
                break;
            case ER_UPLOADFILE_PROBLEMAUPLOAD:
                return ER_UPLOADFILE_PROBLEMAUPLOAD_TEXT;
                break;
            case ER_AGENCY_COORD_INVALID:
                return ER_AGENCY_COORD_INVALID_TEXT;
                break;
            case ER_IMPORT_INVALID_MOVEFILE:
                return ER_IMPORT_INVALID_MOVEFILE_TEXT;
                break;
            case ER_ASTA_EDIT_ORAVISIONE:
                return ER_ASTA_EDIT_ORAVISIONE_TEXT;
                break;
                
                



            case ER_GENERICO:
                return ER_GENERICO_TEXT;





                break;

            default:
                break;
        }
        return NULL;
    }

    /* Gestione MESSAGGI SUCCESS */

    public static function getMessages($messCode) {
        switch ($messCode) {
            case MESS_MODIFICHE_SALVATE:
                return MESS_MODIFICHE_SALVATE_TEXT;
                break;

            default:
                break;
        }
        return NULL;
    }

     /* Gestione MENU ATTIVO */

    public static function setActiveMenu($activeMenu) {
        $result = array();
        $result['menuDashboard'] = '';
        $result['menuAste'] = '';
        $result['menuImports'] = '';
        $result['menuExports'] = '';
        $result['menuAgency'] = '';
        $result['menuProfile'] = '';
        $result['menuSettings'] = '';
        
        switch ($activeMenu) {
            case "dashboard":
                 $result['menuDashboard']  = ' active ';
                break;
            case "aste":
                 $result['menuAste']  = ' active ';
                break;
            case "exports":
                 $result['menuExports']  = ' active ';
                break;
            case "imports":
                 $result['menuImports']  = ' active ';
                break;
            case "agency":
                 $result['menuAgency']  = ' active ';
                break;
            case "profile":
                 $result['menuProfile']  = ' active ';
                break;
            case "settings":
                 $result['menuSettings']  = ' active ';
                break;
            default:
                break;
        }
        return $result;
    }
    
    //=========================================================================
    //GENERA HASH
    //=========================================================================
    function genera_hash () { 
        $hash = md5(mt_rand(0,10000000).microtime());
        return $hash;
    } 


    //=========================================================================
    //INVIO EMAIL CRON
    //=========================================================================
    function sendEmail ($to, $subject, $message, $headers) { 
        mail($to, $subject, $message, $headers); 
    } 
    
    /*
     * -----------------------------------------
     * JSON COMUNI X SELECT
     * -----------------------------------------
     */
    public function GetCities() {
        // Prepara dati per select
        $siglaProvincia = 'EE';
        $data = 'id, nome, siglaprovincia, codice_istat';
        $where = 'siglaprovincia!=:siglaprovincia';
        $orderBy = 'nome';
        $parameters = array(
            ':siglaprovincia' => $siglaProvincia
        );

        // Esegue select di + record
        $result = $this->db1->selectWithOrder(TAB_COMUNI, $data, $where, $parameters, false, $orderBy, null);
        
        return json_encode($result);
    }
    
    /*
     * -----------------------------------------
     * FUNZIONI CON INTERROGAZIONE SU DB
     * -----------------------------------------
    
    /* |
      | PLATFORM
      | */
    /* PLATFORM - SELECT - Info piattaforma */

    public function getPlatformData() {
        // Prepara dati per select
        $data = ' * ';
        $where = 'id=:id';
        $parameters = array(
            ':id' => 1
        );

        // Esegue select di + record
        $result = $this->db1->selectWithOrder(TAB_PLATFORM, $data, $where, $parameters, true, null, null);

        // Return
        return $result;
    }

    /* |
      | TBCOMUNI
      | */
    /* TBCOMUNI - SELECT - Lista Comuni */

    public function getComuni() {
        // Prepara dati per select
        $siglaProvincia = 'EE';
        $data = 'id, nome, siglaprovincia, codice_istat';
        $where = 'siglaprovincia!=:siglaprovincia';
        $orderBy = 'nome';
        $parameters = array(
            ':siglaprovincia' => $siglaProvincia
        );

        // Esegue select di + record
        $result = $this->db1->selectWithOrder(TAB_COMUNI, $data, $where, $parameters, false, $orderBy, null);

        // Return
        return $result;
    }

    /* |
      | TBPROVINCE
      | */
    /* TBPROVINCE - SELECT - Lista Province */

    public function getProvince() {
        // Prepara dati per select
        $sigla = 'EE';
        $data = 'sigla, nome';
        $where = 'sigla!=:sigla';
        $orderBy = 'nome';
        $parameters = array(
            ':sigla' => $sigla
        );

        // Esegue select di + record
        $result = $this->db1->selectWithOrder(PROVINCE_TABLE, $data, $where, $parameters, false, $orderBy, null);

        // Return
        return $result;
    }

    
    // CONVERTE CODICE ISTAT NUMERICO IN STRINGA CON 0 DAVANTI
    public function ConvertCodiceIstat($CodiceComune) {
        $codiceIstatNew = $CodiceComune;
        if (strlen($CodiceComune)<6) {
            if (strlen($CodiceComune)==5) {
                $codiceIstatNew ='0'.$CodiceComune;	
            }
            if (strlen($CodiceComune)==4) {
                $codiceIstatNew='00'.$CodiceComune;	
            }
            if (strlen($CodiceComune)==3) {
                $codiceIstatNew='000'.$CodiceComune;	
            }
            if (strlen($CodiceComune)==2) {
                $codiceIstatNew='0000'.$CodiceComune;	
            }
            if (strlen($CodiceComune)==1) {
                $codiceIstatNew='00000'.$CodiceComune;	
            }
        }
        return $codiceIstatNew;
    }

    

}

<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

// Modelli
require 'Models/aste_model.php';
require 'Models/agency_model.php';
require 'Models/comuni_model.php';
require 'Models/cap_model.php';
require 'Models/province_model.php';
require 'Models/dbcucine_model.php';
require 'Models/dbstrade_model.php';
require 'Models/imports_model.php';
require 'Models/importdetails_model.php';
require 'Models/exportdetails_model.php';
require 'Models/relasteagenzie_model.php';
require 'Models/relagenziepref_model.php';
require 'Models/relagenzieprefview_model.php';

class Preferenze extends Controller {

    function __construct() {
        parent::__construct();
        Session::init();
        Functions::updateSessionTime();
        // User Logged
        $logged = Session::get('loggedIn');
        $userLogged = Session::get(USER);
        if ($logged == false || Session::get(USER)==null || Session::get(PLATFORM)==null ) {
            Session::destroy();
            $this->func->redirectToAction("login/index");
            exit;
        }
        if ($userLogged["role"]== "admin") {
            Session::destroy();
            $this->func->redirectToAction("login/index");
            exit;
        }
        $this->view->userLogged = $userLogged;
        $this->view->js = array('dashboard/js/default.js');
        
        // Connession
        $this->dbConn = new Database();
        // Get Basic Data
        $functions = new Functions();
        $this->view->platformData = $functions->getPlatformData();
    }

    // GET: Index
    public function index($error=null,$message=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("settings");
        // Get Errors
        $this->view->error = Functions::getError($error);
        $this->view->message = Functions::getMessages($message);
        
        // Get Data
        $functionsModel = new Functions();
        
        // PrefView
        $relAgPrefViewModel = new RelAgenziePrefView_Model();
        $this->view->relAgPrefViewList = $relAgPrefViewModel->getRelAgenziePrefList($this->view->userLogged["id"], Null, Null);
        // Pref
        $relAgPrefModel = new RelAgenziePref_Model();
        $arrRelAgPrefList = $relAgPrefModel->getRelAgenziePrefList($this->view->userLogged["id"], Null, Null);
        $this->view->relAgPrefList = $arrRelAgPrefList;
        
        $comuniModel = new Comuni_Model();
        $comuniList = $comuniModel->getComuniList();
        $arrComuni = array();
        $arrComuniTribunale = array();
        if (is_array($comuniList) || is_object($comuniList)) {
            // Inserisci solo Comuni presenti nelle PrefView
            if (is_array($this->view->relAgPrefViewList) || is_object($this->view->relAgPrefViewList)) {
                foreach ($comuniList as $comune) { 
                    $arrItem = array(
                        "nome"=>$comune["nome"],
                        "siglaprovincia"=>$comune["siglaprovincia"],
                        "codice_istat"=>$comune["codice_istat"],
                        "id"=>$comune["id"]
                    );
                    foreach ( $this->view->relAgPrefViewList as $pref ) { 
                        if ($pref["tipoPreferenza"]=="comune" && $pref["idOggetto"]==$comune["codice_istat"] ) { 
                            array_push($arrComuni, $arrItem);
                        }
                        if ($pref["tipoPreferenza"]=="provincia" && $pref["idOggetto"]==$comune["siglaprovincia"] ) {
                            array_push($arrComuni, $arrItem);
                        }
                        if ($pref["tipoPreferenza"]=="comuneTribunale" && $pref["idOggetto"]==$comune["codice_istat"]) {
                            array_push($arrComuniTribunale, $arrItem);
                        }
                    }
                }
            }
        } 
        $this->view->comuniList = $arrComuni;
        $this->view->comuniTribunaleList = $arrComuniTribunale;
        $provModel = new Province_Model();
        $proviceList = $provModel->getProvinceList();
        $arrProvince = array();
        if (is_array($proviceList) || is_object($proviceList)) {
            // Inserisci solo Province presenti nelle PrefView
            if (is_array($this->view->relAgPrefViewList) || is_object($this->view->relAgPrefViewList)) {
                foreach ($proviceList as $provincia) { 
                    $arrItem = array(
                        "sigla"=>$provincia["sigla"],
                        "nome"=>$provincia["nome"]
                    );
                    foreach ( $this->view->relAgPrefViewList as $pref ) { 
                        if ($pref["tipoPreferenza"]=="provincia" && $pref["idOggetto"]==$provincia["sigla"] ) {
                            array_push($arrProvince, $arrItem);
                        }
                    }
                }
            }
        } 
        $this->view->provinceList = $arrProvince;
        // Cap
        $capModel = new Cap_Model();
        $capList = $capModel->getCapList();
        $arrCap = array();
        if (is_array($capList) || is_object($capList)) {
            // Inserisci solo Cap presenti nelle PrefView
            if (is_array($this->view->relAgPrefViewList) || is_object($this->view->relAgPrefViewList)) {
                foreach ($capList as $cap) {
                    $arrItem = array(
                        "cap"=>$cap["cap"],
                        "codiceIstat"=>$cap["codiceIstat"]
                    );
                    foreach ( $this->view->relAgPrefViewList as $pref ) {
                        if ($pref["tipoPreferenza"]=="cap" && $pref["idOggetto"]==$cap["cap"] ) {
                            array_push($arrCap, $arrItem);
                        }
                    }
                }
            }
        }
        $arrCapList = array();
        if (sizeof($arrCap)>0) {
            $tempArr = array_unique(array_column($arrCap, 'cap'));
            $arrCapList = array_intersect_key($arrCap, $tempArr);
        }
        $this->view->capList = $arrCapList;
        
        
        
        
        
        // View
        $this->view->render('preferenze/index', true, HEADER_MAIN);
    }

    
    
    // POST: Edit
    public function executeEdit() {
        
        // Rimuovi TUTTE le preferenze
        $prefModel = new RelAgenziePref_Model();
        $data = array(
            ':status' => "deleted"
        );
        $parameters[":idAgenzia"] = $this->view->userLogged["id"];
        $where = " idAgenzia=:idAgenzia ";
        $prefModel->updateData($data, $parameters, $where);
        
        // -------->>>>> Update su Tab. Rel_AgencyPref
        if (isset($_POST["idComuni"]) && $_POST["idComuni"]!="") {
            foreach($_POST["idComuni"] as $pref) {
                // Set values
                $data = array(
                    ':idAgenzia' => $this->view->userLogged["id"],
                    ':tipoPreferenza' => "comune",
                    ':idOggetto' => $pref,
                    ':status' => "on"
                );
                $prefModel = new RelAgenziePref_Model();
                $idRecord = $prefModel->create($data);
            }
        }
        if (isset($_POST["idProvince"]) && $_POST["idProvince"]!="") {
            foreach($_POST["idProvince"] as $pref) {
                // Set values
                $data = array(
                    ':idAgenzia' => $this->view->userLogged["id"],
                    ':tipoPreferenza' => "provincia",
                    ':idOggetto' => $pref,
                    ':status' => "on"
                );
                $prefModel = new RelAgenziePref_Model();
                $idRecord = $prefModel->create($data);
            }
        }
        if (isset($_POST["capComuni"]) && $_POST["capComuni"]!="") {
            foreach($_POST["capComuni"] as $pref) {
                // Set values
                $data = array(
                    ':idAgenzia' => $this->view->userLogged["id"],
                    ':tipoPreferenza' => "cap",
                    ':idOggetto' => $pref,
                    ':status' => "on"
                );
                $prefModel = new RelAgenziePref_Model();
                $idRecord = $prefModel->create($data);
            }
        }
        if (isset($_POST["idComuniTribunale"]) && $_POST["idComuniTribunale"]!="") {
            foreach($_POST["idComuniTribunale"] as $pref) {
                // Set values
                $data = array(
                    ':idAgenzia' => $this->view->userLogged["id"],
                    ':tipoPreferenza' => "comuneTribunale",
                    ':idOggetto' => $pref,
                    ':status' => "on"
                );
                $prefModel = new RelAgenziePref_Model();
                $idRecord = $prefModel->create($data);
            }
        }
        
        // -------->>>>> Update su Tab. Users
        // Set values
        $data22 = array(
            ':prefFlagPubblicita' => $_POST['prefFlagPubblicita'],
            ':prefFlagPrezzo' => $_POST['prefFlagPrezzo']
        ); 
        $where = ' id=:id ';
        $parameters = array(
            ':id' => $this->view->userLogged["id"]
        );
        
        // Update
        if ($this->model->updateDataTabUsers($data22,$parameters,$where)) {
            // View
            $this->index(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->index(ER_GENERICO,null);
            return false;
        }
        
    }
    
    
    
}



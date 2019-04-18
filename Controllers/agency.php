<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */
 error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

require 'Models/aste_model.php';
require 'Models/comuni_model.php';
require 'Models/province_model.php';
require 'Models/cap_model.php';
require 'Models/dbcucine_model.php';
require 'Models/dbstrade_model.php';
require 'Models/relasteagenzie_model.php';
require 'Models/relagenziepref_model.php';
require 'Models/relagenzieprefview_model.php';

class Agency extends Controller {

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
        if ($userLogged["role"]!= "admin") {
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
    public function index($error=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("agency");
        // Get Errors
        $this->view->error = Functions::getError($error);
        
        // Get Data
        $agency = new Agency_Model();
        $this->view->agencyList = $agency->getUsersListByRole("agency");
        $this->view->numItems = 0;
        if (is_array($this->view->agencyList) || is_object($this->view->agencyList)) {
            $this->view->numItems = sizeof($this->view->agencyList);
        }
        
        
        // View
        $this->view->render('agency/index', true, HEADER_MAIN);
    }

    // GET: Create
    public function create($error=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("agency");
        // Get Errors
        $this->view->error = Functions::getMessages($error);
        
        // View
        $this->view->render('agency/create', true, HEADER_MAIN);
    }
    // POST: Create
    public function executeCreate() {
        // Check POSTS
        if (!isset($_POST["companyName"]) || !isset($_POST["firstName"]) || !isset($_POST["lastName"]) || !isset($_POST["email"])
                || !isset($_POST["IdGestionale"]) || !isset($_POST["IdAgenzia"])) {
            $this->create(ER_GENERICO);
            return false;
        }
        
        // Check duplicato
        if (!$this->model->checkDuplicate("agency",$_POST["email"],$_POST["IdAgenzia"],NULL)) {
            $this->create(ER_AGENCY_DUPLICATE);
            return false;
        }
        
        
        
                        
        // Get Comune
        $comuneCompleto = $_POST["Comune"];
        $Comune = "";
        $SiglaProvincia = "";
        $IdComune = 0;
        $CodiceComune = "";
        $functions = new Functions();
        $comuniList = $functions->getComuni();
        foreach ($comuniList as $item) {
            $completo = $item["nome"] . " (" . $item["siglaprovincia"] . ")";
            if ($completo==$_POST["Comune"]) {
                $Comune = $item["nome"];
                $SiglaProvincia = $item["siglaprovincia"];
                $IdComune = $item["id"];
                $CodiceComune = $item["codice_istat"];
            }
        }
        if ($IdComune==0) {
            $this->create(ER_AGENCY_COMUNE_INVALID);
            return false;
        }
        
        // TROVA COORDINATE
        $Latitudine = "";
        $Longitudine = "";
        $address = $comuneCompleto.', '. $_POST['Indirizzo'].' '.$_POST['Civico'].' '.$_POST['Cap'].', Italy';
        // echo '<br>indirizzo: '.$address;
        $functions = new Functions();
        $coordinate = $functions->getLatLonFromAddress($address);
        $arrCoo = explode(",", $coordinate);
        $Latitudine  = $arrCoo[0];
        $Longitudine  = $arrCoo[1];
        if (!isset($Latitudine) || trim($Latitudine) === ''
            || !isset($Longitudine) || trim($Longitudine) === '' ) { 
            $Latitudine  = "";
            $Longitudine = "";
        }  
        
        if ($Latitudine=="" || $Longitudine=="") {
            $this->create(ER_AGENCY_COMUNE_INVALID);
            return false;
        }

        // Set last edit
        $lastEdit = date('Y-m-d H:i:s');
        $DataModifica = substr($lastEdit,0,10).'T'.substr($lastEdit,11,8);
        // Set values
        $data = array(
            ':createdAt' => $lastEdit,
            ':firstName' => $_POST['firstName'],
            ':lastName' => $_POST['lastName'],
            ':companyName' => $_POST['companyName'],
            ':tipoContratto' => $_POST['tipoContratto'],
            ':email' => $_POST['email'],
            ':password' => "",
            ':forgotHash' => "",
            ':role' => "agency",
            ':status' => "on",
            ':note' => "",
            ':lastEdit' => $lastEdit,
            ':IdGestionale' => $_POST['IdGestionale'],
            ':IdAgenzia' => $_POST['IdAgenzia'],
            ':NomePubblicita' => $_POST['NomePubblicita'],
            ':DescrizioneAgenzia' => "",
            ':CodiceNazione' => "IT",
            ':CodiceComune' => $CodiceComune,
            ':Comune' => $Comune,
            ':SiglaProvincia' => $SiglaProvincia,
            ':IdComune' => $IdComune,
            ':Indirizzo' => $_POST['Indirizzo'],
            ':Civico' => $_POST['Civico'],
            ':Cap' => $_POST['Cap'],
            ':Latitudine' => $Latitudine,
            ':Longitudine' => $Longitudine,
            ':Telefono' => $_POST['Telefono'],
            ':Cellulare' => $_POST['Cellulare'],
            ':Fax' => $_POST['Fax'],
            ':CodiceFiscale' => $_POST['CodiceFiscale'],
            ':URLLogo' => "",
            ':URLImmagine' => "",
            ':DataModifica' => $DataModifica,
            ':CodicePortale' => 1, // Immobiliare.it
            ':CodiceRelazione' => "",
            ':DataInzio_d' => $_POST['DataInzio_d'],
            ':DataFine_d' => $_POST['DataFine_d'],
            ':DataInizio' => $_POST['DataInzio_d']."T00:00:00",
            ':DataFine' => $_POST['DataFine_d']."T00:00:00",
            ':NrAnnunci' => $_POST['NrAnnunci'],
            ':ftp_user' =>  "",
            ':ftp_password' => "",
            ':isSuperAdmin' =>  false
        );
        
        // Insert
        $idRecord = $this->model->create($data);
        if ($idRecord>0) {
            // View
            Functions::redirectToAction('agency/index');
        } else {
            $this->create(ER_GENERICO);
            return false;
        }
        
    }
    
    // GET: Edit
    public function edit($error=null, $message=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("agency");
        // Get Errors
        $this->view->error = Functions::getError($error);
        $this->view->message = Functions::getMessages($message);
        
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"],"agency")) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"],"agency");
        
        
        // View
        $this->view->render('agency/edit', true, HEADER_MAIN);
    }
    // POST: Edit
    public function executeEdit() {
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"],"agency")) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"],"agency");
        // Check POSTS
        if (!isset($_POST["companyName"]) || !isset($_POST["firstName"]) || !isset($_POST["lastName"]) || !isset($_POST["email"])
                || !isset($_POST["IdGestionale"]) || !isset($_POST["IdAgenzia"])) {
            $this->edit(ER_GENERICO);
            return false;
        }
        
        // Check duplicato
        if (!$this->model->checkDuplicate("agency",$_POST["email"],$_POST["IdAgenzia"],$_GET["iditem"])) {
            $this->edit(ER_AGENCY_DUPLICATE);
            return false;
        }
        
        // Get Comune
        $comuneCompleto = $_POST["Comune"];
        $Comune = "";
        $SiglaProvincia = "";
        $IdComune = 0;
        $CodiceComune = "";
        $functions = new Functions();
        $comuniList = $functions->getComuni();
        foreach ($comuniList as $item) {
            $completo = $item["nome"] . " (" . $item["siglaprovincia"] . ")";
            if ($completo==$_POST["Comune"]) {
                $Comune = $item["nome"];
                $SiglaProvincia = $item["siglaprovincia"];
                $IdComune = $item["id"];
                $CodiceComune = $item["codice_istat"];
            }
        }
        if ($IdComune==0) {
            $this->edit(ER_AGENCY_COMUNE_INVALID);
            return false;
        }
        // TROVA COORDINATE
        $Latitudine = "";
        $Longitudine = "";
        $address = $comuneCompleto.', '. $_POST['Indirizzo'].' '.$_POST['Civico'].' '.$_POST['Cap'].', Italy';
        // echo '<br>indirizzo: '.$address;
        $functions = new Functions();
        $coordinate = $functions->getLatLonFromAddress($address);
        $arrCoo = explode(",", $coordinate);
        $Latitudine  = $arrCoo[0];
        $Longitudine  = $arrCoo[1];
        if (!isset($Latitudine) || trim($Latitudine) === ''
            || !isset($Longitudine) || trim($Longitudine) === '' ) {
            $Latitudine  = "";
            $Longitudine = "";
        }  
        
        if ($Latitudine=="" || $Longitudine=="") {
            $this->edit(ER_AGENCY_COMUNE_INVALID);
            return false;
        }
        
        // Set last edit
        $lastEdit = date('Y-m-d H:i:s');
        $DataModifica = substr($lastEdit,0,10).'T'.substr($lastEdit,11,8);
        // Set values
        $data = array(
            ':firstName' => $_POST['firstName'],
            ':lastName' => $_POST['lastName'],
            ':companyName' => $_POST['companyName'],
            ':tipoContratto' => $_POST['tipoContratto'],
            ':email' => $_POST['email'],
            ':lastEdit' => $lastEdit,
            ':IdGestionale' => $_POST['IdGestionale'],
            ':IdAgenzia' => $_POST['IdAgenzia'],
            ':NomePubblicita' => $_POST['NomePubblicita'],
            ':CodiceComune' => $CodiceComune,
            ':Comune' => $Comune,
            ':SiglaProvincia' => $SiglaProvincia,
            ':IdComune' => $IdComune,
            ':Indirizzo' => $_POST['Indirizzo'],
            ':Civico' => $_POST['Civico'],
            ':Cap' => $_POST['Cap'],
            ':Latitudine' => $Latitudine,
            ':Longitudine' => $Longitudine,
            ':Telefono' => $_POST['Telefono'],
            ':Cellulare' => $_POST['Cellulare'],
            ':Fax' => $_POST['Fax'],
            ':CodiceFiscale' => $_POST['CodiceFiscale'],
            ':DataModifica' => $DataModifica,
            ':DataInzio_d' => $_POST['DataInzio_d'],
            ':DataFine_d' => $_POST['DataFine_d'],
            ':DataInizio' => $_POST['DataInzio_d']."T00:00:00",
            ':DataFine' => $_POST['DataFine_d']."T00:00:00",
            ':NrAnnunci' => $_POST['NrAnnunci']
        );
        $where = ' id=:id ';
        $parameters = array(
            ':id' => $_GET["iditem"]
        );
        
        
        // Update
        if ($this->model->updateData($data,$parameters,$where)) {
            // View
            $this->edit(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->edit(ER_GENERICO,null);
            return false;
        }
        
    }
    // POST: ResetCred
    public function resetCred() {
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"],"agency")) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"],"agency");
        // Check POSTS
        if (!isset($_POST["btnResetCred"]) || $_POST["btnResetCred"]=="") {
            $this->edit(ER_GENERICO);
            return false;
        }
        
        // Nuova Passw
        $functions = new Functions();
        $password = substr($functions->genera_hash(),0,12);
        $passwordCript = Hash::create('sha256', $password, HASH_KEY);
        
        // Set values
        $data = array(
            ':password' => $passwordCript
        );
        $where = ' id=:id ';
        $parameters = array(
            ':id' => $_GET["iditem"]
        );

        // Update
        if ($this->model->updateData($data,$parameters,$where)) {
            //Invia email
            $to      = $this->view->data["email"]; 
            $subject = 'Credenziali Accesso | '.$this->view->platformData["siteName"];
            include ('public/template/utente_credenziali.php');
            $headers = "From: ".$this->view->platformData["emailFromDesc"]." <".$this->view->platformData["emailFrom"].">". "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8";
            $functions->sendEmail ($to, $subject, $emailText, $headers) ;
            
            // View
            $this->edit(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->edit(ER_GENERICO,null);
            return false;
        }
        
    }
    
    // GET: Edit Preferenze Vista 
    public function editPrefView($error=null, $message=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("agency");
        // Get Errors
        $this->view->error = Functions::getError($error);
        $this->view->message = Functions::getMessages($message);
        
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"],"agency")) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"],"agency");
        $functionsModel = new Functions();
        $relAgPrefViewModel = new RelAgenziePrefView_Model();
        $this->view->relAgPrefViewList = $relAgPrefViewModel->getRelAgenziePrefList($_GET["iditem"], Null, Null);
        $comuniModel = new Comuni_Model();
        $this->view->comuniList = $comuniModel->getComuniList();
        $provModel = new Province_Model();
        $this->view->provinceList = $provModel->getProvinceList();
        // Lista Cap
        $capModel = new Cap_Model();
        $capList = $capModel->getCapList();
        $arrCapList = array();
        if (is_array($capList) || is_object($capList)) {
            $tempArr = array_unique(array_column($capList, 'cap'));
            $arrCapList = array_intersect_key($capList, $tempArr);
        }
        $this->view->capList = $arrCapList;

            // View
        $this->view->render('agency/editPrefView', true, HEADER_MAIN);
    }
    // POST: Edit Pref Vista
    public function executeEditPrefView() { 
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"],"agency")) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"],"agency");
        
        
        // Rimuovi TUTTE le preferenze View
        $prefViewModel = new RelAgenziePrefView_Model();
        $data = array(
            ':status' => "deleted"
        );
        $parameters[":idAgenzia"] = $_GET["iditem"];
        $where = " idAgenzia=:idAgenzia ";
        $prefViewModel->updateData($data, $parameters, $where);
        
        // -------->>>>> Update su Tab. Rel_AgencyPref
        if (isset($_POST["idComuni"]) && $_POST["idComuni"]!="") {
            foreach($_POST["idComuni"] as $pref) {
                // Set values
                $data = array(
                    ':idAgenzia' => $_GET["iditem"],
                    ':tipoPreferenza' => "comune",
                    ':idOggetto' => $pref,
                    ':status' => "on"
                );
                $prefViewModel = new RelAgenziePrefView_Model();
                $idRecord = $prefViewModel->create($data);
            }
        }
        if (isset($_POST["idProvince"]) && $_POST["idProvince"]!="") {
            foreach($_POST["idProvince"] as $pref) {
                // Set values
                $data = array(
                    ':idAgenzia' => $_GET["iditem"],
                    ':tipoPreferenza' => "provincia",
                    ':idOggetto' => $pref,
                    ':status' => "on"
                );
                $prefViewModel = new RelAgenziePrefView_Model();
                $idRecord = $prefViewModel->create($data);
            }
        }
        if (isset($_POST["capComuni"]) && $_POST["capComuni"]!="") {
            foreach($_POST["capComuni"] as $pref) {
                // Set values
                $data = array(
                    ':idAgenzia' => $_GET["iditem"],
                    ':tipoPreferenza' => "cap",
                    ':idOggetto' => $pref,
                    ':status' => "on"
                );
                $prefViewModel = new RelAgenziePrefView_Model();
                $idRecord = $prefViewModel->create($data);
            }
        }
        if (isset($_POST["idComuniTribunale"]) && $_POST["idComuniTribunale"]!="") {
            foreach($_POST["idComuniTribunale"] as $pref) {
                // Set values
                $data = array(
                    ':idAgenzia' =>$_GET["iditem"],
                    ':tipoPreferenza' => "comuneTribunale",
                    ':idOggetto' => $pref,
                    ':status' => "on"
                );
                $prefViewModel = new RelAgenziePrefView_Model();
                $idRecord = $prefViewModel->create($data);
            }
        }
        
        // Verifica Tabella preferenze (ripulisci ciò che è fuori)
        $relAgPrefViewModel = new RelAgenziePrefView_Model();
        $relAgPrefViewList = $relAgPrefViewModel->getRelAgenziePrefList($_GET["iditem"], Null, Null);
        if(is_array($relAgPrefViewList) || is_object($relAgPrefViewList)){
            foreach($relAgPrefViewList as $pref){
                foreach($_POST["idComuni"] as $prefView) {
                    
                }
            }
        }
        
        // View
        $this->editPrefView(null,MESS_MODIFICHE_SALVATE);
    }
    
    // GET: Edit Preferenze Export
    public function editPref($error=null, $message=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("agency");
        // Get Errors
        $this->view->error = Functions::getError($error);
        $this->view->message = Functions::getMessages($message);
        
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"],"agency")) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"],"agency");
        $functionsModel = new Functions();
        
        // PrefView
        $relAgPrefViewModel = new RelAgenziePrefView_Model();
        $this->view->relAgPrefViewList = $relAgPrefViewModel->getRelAgenziePrefList($_GET["iditem"], Null, Null);
        // Pref
        $relAgPrefModel = new RelAgenziePref_Model();
        $arrRelAgPrefList = $relAgPrefModel->getRelAgenziePrefList($_GET["iditem"], Null, Null);
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
        // Province
        $provModel = new Province_Model();
        $proviceList = $provModel->getProvinceList();
        $arrProvince = array();
        if (is_array($proviceList) || is_object($proviceList)) {
            // Inserisci solo Comuni presenti nelle PrefView
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
        $this->view->render('agency/editPref', true, HEADER_MAIN);
    }
    // POST: Edit Pref Export
    public function executeEditPref() { 
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"],"agency")) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"],"agency");
        
        
        // Rimuovi TUTTE le preferenze
        $prefModel = new RelAgenziePref_Model();
        $data = array(
            ':status' => "deleted"
        );
        $parameters[":idAgenzia"] = $_GET["iditem"];
        $where = " idAgenzia=:idAgenzia ";
        $prefModel->updateData($data, $parameters, $where);
        
        // -------->>>>> Update su Tab. Rel_AgencyPref
        if (isset($_POST["idComuni"]) && $_POST["idComuni"]!="") {
            foreach($_POST["idComuni"] as $pref) {
                // Set values
                $data = array(
                    ':idAgenzia' => $_GET["iditem"],
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
                    ':idAgenzia' => $_GET["iditem"],
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
                    ':idAgenzia' =>$_GET["iditem"],
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
                    ':idAgenzia' =>$_GET["iditem"],
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
            ':id' => $_GET["iditem"]
        );
        
        
        // Update
        if ($this->model->updateData($data22,$parameters,$where)) {
            // View
            $this->editPref(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->editPref(ER_GENERICO,null);
            return false;
        }
        
    }
    
    
    
    
    // GET: Edit Img
    public function editImg($error=null, $message=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("agency");
        // Get Errors
        $this->view->error = Functions::getError($error);
        $this->view->message = Functions::getMessages($message);
        
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"],"agency")) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"],"agency");
        
        
        // View
        $this->view->render('agency/editImg', true, HEADER_MAIN);
    }
    // POST: Edti Img 
    public function executeEditImg() { 
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"],"agency")) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"],"agency");
        
        
        // ====================================================
        //CHECKS FILES
        $imageName = "";
        if (basename($_FILES["avatar"]["name"])!='') {
            if ($_FILES["avatar"]["error"] > 0) {
                $this->index(ER_UPLOADFILE_FILENONVALIDO,null);
                return false;
            }
            if ($_FILES["avatar"]["type"] != "image/png" 
                && $_FILES["avatar"]["type"]!= "image/jpeg" 
                && $_FILES["avatar"]["type"]!= "image/jpg") {
                $this->editImg(ER_UPLOADFILE_ESTENSIONENONVALIDA,null);
                return false;
            }
            if ( ($_FILES["avatar"]["size"]) > 5000000 ) {
                $this->editImg(ER_UPLOADFILE_SIZENONVALIDA,null);
                return false;
            }
            // UPLOAD EFFETTIVO
            if ($fileName0 = basename($_FILES["avatar"]["name"])==""){$imageName=$_POST['avatar'];}
            else{
                $fileName0 = basename($_FILES["avatar"]["name"]);
                $temp0 = explode(".", $_FILES["avatar"]["name"]);
                $imageName = 'img-'.$_GET["iditem"]."-".round(microtime(true)). '.' . end($temp0);
            }
            if ($fileName0 = basename($_FILES["avatar"]["name"])!=""){ 
                $target_path = $_SERVER['DOCUMENT_ROOT'] . "/flussi/public/images/".$imageName;
                if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_path)){
                    $this->editImg(ER_UPLOADFILE_PROBLEMAUPLOAD,null);
                    return false;
                }
            } 
        }
        // ====================================================
        
        if ( $imageName=="" ) {
            $this->edit(ER_ASTA_EDIT_DATAVISIONE);
            return false;
        }
        $immagine_URL = URL .'public/images/'. $imageName;
        
        
        // -------->>>>> Update su Tab. Users
        // Set values
        $data22 = array(
            ':URLImmagine' => $immagine_URL
        ); 
        $where = ' id=:id ';
        $parameters = array(
            ':id' => $_GET["iditem"]
        );
        
        
        // Update
        if ($this->model->updateData($data22,$parameters,$where)) {
            // View
            $this->editImg(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->editImg(ER_GENERICO,null);
            return false;
        }
        
    }
    
    // Check IdItem exists
    function CheckIdItemExists($idItem) {
        if (!isset($idItem)) {
            return false;
        }
        if ($idItem==null || $idItem=="") {
            return false;
        } else {
            if ($this->model->getDataFromId($idItem,"agency")!=NULL) {
                return true;
            }
        }
        return false;
    }
    
    
}

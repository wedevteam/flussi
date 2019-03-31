<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Settings extends Controller {

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
    public function index($error=null,$message=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("settings");
        // Get Errors
        $this->view->error = Functions::getError($error);
        $this->view->message = Functions::getMessages($message);
        
        
        
        // View
        $this->view->render('settings/index', true, HEADER_MAIN);
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
        
        // Coordinate
        $Latitudine = "";
        $Longitudine = "";
        
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


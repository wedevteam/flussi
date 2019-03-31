<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

// Modelli
require 'Models/relasteagenzie_model.php';
require 'Models/relagenziepref_model.php';
require 'Models/relagenzieprefview_model.php';
require 'Models/agency_model.php';
require 'Models/comuni_model.php';
require 'Models/dbcucine_model.php';
require 'Models/dbstrade_model.php';
require 'Models/importdetails_model.php';

class Aste extends Controller {

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
        $this->view->mainMenu = Functions::setActiveMenu("aste");
        // Get Errors
        $this->view->error = Functions::getError($error);
        
        // Get Data
        $asteModel = new Aste_Model();
        $arrAsteListAll = $asteModel->getAsteList(null,null);
        if ($this->view->userLogged["role"]!="admin") {
            $relAsAgModel = new RelAsteAgenzie_Model();
            $arrAsteAgenziaList = $relAsAgModel->getRelAsteAgenzieList($this->view->userLogged["id"], Null, Null);
            $relAgPrefViewModel = new RelAgenziePrefView_Model();
            $relAgPrefViewList = $relAgPrefViewModel->getRelAgenziePrefList($this->view->userLogged["id"], NULL,NULL);
            $arrAsteListAgPreFilter =array();
        }
        $functionsModel = new Functions();
        
        $arrAsteList = array();
        if (is_array($arrAsteListAll) || is_object($arrAsteListAll)) {
            foreach ($arrAsteListAll as $item) {
                $toInsert = true;
                // Prezzo
                if ($this->view->platformData["prefFlagPrezzo"]=="OffertaMinima") {
                    $Prezzo = $item["importoOffertaMinima"];
                } else {
                    $Prezzo = $item["importoBaseAsta"];
                }
                // Status
                $Status = $item["status"];
                // Rif. Annuncio
                $RifAnnuncio = "";
                // Testo
                $Testo = $item["Testo"];
                // Nome Agente
                $nomeAgente = "";
                // Rich.Visita
                $flagRichiestaVisione = "false";
                // data.Visita
                $dataRichiestaVisione = Null;
                // Img
                $immagineURL = $item["immagine_URL"];
                if ($this->view->userLogged["role"]!="admin") {
                    // Pref View
                    if (is_array($relAgPrefViewList) || is_object($relAgPrefViewList)) {
                        $toInsert = false;
                        foreach ($relAgPrefViewList as $prefView) {
                            // Comune
                            if ($prefView["tipoPreferenza"]=="comune") {
                                $prefViewComune = $functionsModel->ConvertCodiceIstat($prefView["idOggetto"]);
                                if ($prefViewComune==$item["CodiceComune"]) {
                                    $toInsert = true;
                                }
                            }
                            // Provincia
                            if ($prefView["tipoPreferenza"]=="provincia" && $prefView["idOggetto"]==$item["Provincia"]) {
                                $toInsert = true;
                            }
                            // ComuneTribunale
                            if ($prefView["tipoPreferenza"]=="comuneTribunale") {
                                $prefViewComuneTribunale = $functionsModel->ConvertCodiceIstat($prefView["idOggetto"]);
                                if ($prefViewComuneTribunale==$item["codiceComuneTribunale"]) {
                                    $toInsert = true;
                                }
                            }
                        }
                    }
                    
                    if ($toInsert) {
                        if (is_array($arrAsteAgenziaList) || is_object($arrAsteAgenziaList)) {
                            foreach ($arrAsteAgenziaList as $rel) {
                                if ($rel["idAsta"]==$item["id"]) { 
                                    // Prezzo
                                    if ($rel["preferenzaPrezzo"]=="OffertaMinima") {
                                        $Prezzo = $item["importoOffertaMinima"];
                                    } else {
                                        $Prezzo = $item["importoBaseAsta"];
                                    }
                                    // Status
                                    $Status =  $rel["status"];
                                    // Rif.Annuncio
                                    $RifAnnuncio = $rel["riferimentoAnnuncio"];
                                    // Testo
                                    $Testo = $rel["descrizione"];
                                    // Nome Agente
                                    $nomeAgente = $rel["nomeAgente"];
                                    // Rich.Visita
                                    $flagRichiestaVisione = $rel["flagRichiestaVisione"];
                                    // Data richiesta visione
                                    $dataRichiestaVisione =  $rel["dataRichiestaVisione"];
                                    // Immagine
                                    $immagineURL = $rel["immagine_URL"];
                                }
                            }
                        }
                    }
                }
                
                // Set values
                $arrItem = array(
                    "id" =>$item["id"],
                    "ComuneTribunale" =>$item["ComuneTribunale"],
                    "SiglaProvTribunale" =>$item["SiglaProvTribunale"],
                    "codiceComuneTribunale" =>$item["codiceComuneTribunale"],
                    "linkTribunale" =>$item["linkTribunale"],
                    "rge" =>$item["rge"],
                    "lotto" =>$item["lotto"],
                    "tipoProcedura" =>$item["tipoProcedura"],
                    "rito" =>$item["rito"],
                    "giudice" =>$item["giudice"],
                    "delegato" =>$item["delegato"],
                    "custode" =>$item["custode"],
                    "curatore" =>$item["curatore"],
                    "valorePerizia" =>$item["valorePerizia"],
                    "dataPubblicazione" =>$item["dataPubblicazione"],
                    "noteGeneriche" =>$item["noteGeneriche"],
                    "datiCatastali" =>$item["datiCatastali"],
                    "disponibilita" =>$item["disponibilita"],
                    "importoBaseAsta" =>$item["importoBaseAsta"],
                    "importoOffertaMinima" =>$item["importoOffertaMinima"],
                    "noteAggiuntive" =>$item["noteAggiuntive"],
                    "dataAsta" =>$item["dataAsta"],
                    "linkAllegati" =>$item["linkAllegati"],
                    "CodiceComune" =>$item["CodiceComune"],
                    "Comune" =>$item["Comune"],
                    "Provincia" =>$item["Provincia"],
                    "ComuneProvinciaCompleto" =>$item["ComuneProvinciaCompleto"],
                    "Strada" =>$item["Strada"],
                    "Strada_testo" =>$item["Strada_testo"],
                    "Indirizzo" =>$item["Indirizzo"],
                    "Civico" =>$item["Civico"],
                    "Cap" =>$item["Cap"],
                    "Latitudine" =>$item["Latitudine"],
                    "Longitudine" =>$item["Longitudine"],
                    "Categoria" =>$item["Categoria"],
                    "IDTipologia" =>$item["IDTipologia"],
                    "NrLocali" =>$item["NrLocali"],
                    "MQSuperficie" =>$item["MQSuperficie"],
                    "TipoProprieta" =>$item["TipoProprieta"],
                    "ClasseCatastale" =>$item["ClasseCatastale"],
                    "Titolo" =>$item["Titolo"],
                    "Testo" =>$Testo,
                    "TestoBreve" =>$item["TestoBreve"],
                    "StatoImmobile" =>$item["StatoImmobile"],
                    "immagine_URL" =>$immagineURL ,
                    "status" =>$Status,
                    "Prezzo" =>$Prezzo,
                    "riferimentoAnnuncio" =>$RifAnnuncio,
                    "nomeAgente" =>$nomeAgente,
                    "riferimentoAnnuncio" =>$RifAnnuncio,
                    "flagRichiestaVisione" =>$flagRichiestaVisione,
                    "dataRichiestaVisione" =>$dataRichiestaVisione
                );
                
                if ($this->view->userLogged["role"]!="admin" && $toInsert) {
                    array_push($arrAsteListAgPreFilter, $arrItem);
                }
                
                // Filtri
                if (isset($_POST["btnSearch"])) {
                    // Comune
                    if (isset($_POST["codiceComuneFilter"]) && $_POST["codiceComuneFilter"]!="0") {
                        $toInsert = false;
                        if ($functionsModel->ConvertCodiceIstat($_POST["codiceComuneFilter"])==$item["CodiceComune"]) {
                            $toInsert = true;
                        }
                    }
                    // Comune Tribunale
                    if (isset($_POST["codiceComuneTribunaleFilter"]) && $_POST["codiceComuneTribunaleFilter"]!="0") {
                        $toInsert = false;
                        if ($functionsModel->ConvertCodiceIstat($_POST["codiceComuneTribunaleFilter"])==$item["codiceComuneTribunale"]) {
                            $toInsert = true;
                        }
                    }
                }
                
                // Add
                if ($toInsert) {
                    array_push($arrAsteList, $arrItem);
                }
            }
        }
        $this->view->asteList = $arrAsteList;
        
        // Lista Comuni
        $comuniModel = new Comuni_Model();
        $comuniList = $comuniModel->getComuniList();
        $arrComuni = array();
        $arrComuniTribunale = array();
        if (is_array($comuniList) || is_object($comuniList)) {
            if ($this->view->userLogged["role"]=="admin") {
                // Inserisci solo Comuni presenti nelle aste
                if (is_array($arrAsteListAll) || is_object($arrAsteListAll)) {
                    foreach ($comuniList as $comune) {
                        foreach ( $arrAsteListAll as $asta ) {
                            $arrItem = array(
                                "nome"=>$comune["nome"],
                                "siglaprovincia"=>$comune["siglaprovincia"],
                                "codice_istat"=>$comune["codice_istat"],
                                "id"=>$comune["id"]
                            );
                            if ($asta["CodiceComune"] == $functionsModel->ConvertCodiceIstat($comune["codice_istat"]) ) {
                                array_push($arrComuni, $arrItem);
                            }
                            if ($asta["codiceComuneTribunale"] == $functionsModel->ConvertCodiceIstat($comune["codice_istat"])) {
                                array_push($arrComuniTribunale, $arrItem);
                            }
                        }
                    }
                }
            } else {
                // Inserisci solo Comuni presenti nelle PrefVIEW dell'Agenzia
                if (is_array($arrAsteListAgPreFilter) || is_object($arrAsteListAgPreFilter)) {
                    foreach ($comuniList as $comune) {
                        foreach ( $arrAsteListAgPreFilter as $asta ) {
                            $arrItem = array(
                                "nome"=>$comune["nome"],
                                "siglaprovincia"=>$comune["siglaprovincia"],
                                "codice_istat"=>$comune["codice_istat"],
                                "id"=>$comune["id"]
                            );
                            if ($asta["CodiceComune"] == $functionsModel->ConvertCodiceIstat($comune["codice_istat"]) ) {
                                array_push($arrComuni, $arrItem);
                            }
                            if ($asta["codiceComuneTribunale"] == $functionsModel->ConvertCodiceIstat($comune["codice_istat"])) {
                                array_push($arrComuniTribunale, $arrItem);
                            }
                        }
                    }
                }
            }
        }
        $arrComuniList = array();
        if (sizeof($arrComuni)>0) {
            $tempArr = array_unique(array_column($arrComuni, 'codice_istat'));
            $arrComuniList = array_intersect_key($arrComuni, $tempArr);
        }
        $this->view->comuniList = $arrComuniList;
        $arrComuniTribunaleList = array();
        if (sizeof($arrComuniTribunale)>0) {
            $tempArr2 = array_unique(array_column($arrComuniTribunale, 'codice_istat'));
            $arrComuniTribunaleList = array_intersect_key($arrComuniTribunale, $tempArr2);
        }
        $this->view->comuniTribunaleList = $arrComuniTribunaleList;
        
        // View
        $this->view->render('aste/index', true, HEADER_MAIN);
    }

    // GET: Overview
    public function overview($error=null, $message=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("aste");
        // Get Errors
        $this->view->error = Functions::getError($error);
        $this->view->message = Functions::getMessages($message);
        
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"])) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
        if ($this->view->userLogged["role"]!="admin") {
            $relAsteAgModel = new RelAsteAgenzie_Model();
            // Get Record RelAsteAgenzie
            // Checks
            if (!$this->CheckIdItemExistsRel($this->view->userLogged["id"], $_GET["iditem"])) {
                $this->index();
                return false;
            }
            $this->view->relAsteAgenzia = $relAsteAgModel->getDataFromIdAgIdAsta($this->view->userLogged["id"], $_GET["iditem"]);
        }
        $this->view->Coordinate = "Ok";
        
        // View
        $this->view->render('aste/overview', true, HEADER_MAIN);
    }
    
    // GET: Edit
    public function edit($error=null, $message=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("aste");
        // Get Errors
        $this->view->error = Functions::getError($error);
        $this->view->message = Functions::getMessages($message);
        
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"])) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
        
        if ($this->view->userLogged["role"]!="admin") {
            // Dati specifici agenzia
             $relAsteAgModel = new RelAsteAgenzie_Model();
            // Get Record RelAsteAgenzie
            // Checks
            if (!$this->CheckIdItemExistsRel($this->view->userLogged["id"], $_GET["iditem"])) {
                $this->index();
                return false;
            }
            $this->view->relAsteAgenzia = $relAsteAgModel->getDataFromIdAgIdAsta($this->view->userLogged["id"], $_GET["iditem"]);
        }
        
        // View
        $this->view->render('aste/edit', true, HEADER_MAIN);
    }
        // POST: Execut EDIT (SOLO AGENZIA)
    public function executeEdit() {
        // Check
        if ($this->view->userLogged["role"]=="admin") {
            Session::destroy();
            $this->func->redirectToAction("login/index");
            exit;
        }
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"])) {
            $this->edit(ER_ASTA_EDIT_GENERIC);
            return false;
        }// Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
        $relAsteAgModel = new RelAsteAgenzie_Model();
        // Get Record RelAsteAgenzie
        // Checks
        if (!$this->CheckIdItemExistsRel($this->view->userLogged["id"], $_GET["iditem"])) {
            $this->edit(ER_ASTA_EDIT_GENERIC);
            return false;
        }
        $this->view->relAsteAgenzia = $relAsteAgModel->getDataFromIdAgIdAsta($this->view->userLogged["id"], $_GET["idrel"]);
        
        // Checks
        $dataRichiestaVisione = "0000-00-00";
        $oraRichiestaVisione = "00:00:00";
        if ($_POST['flagRichiestaVisione']=="true") {
            if (!isset($_POST['dataRichiestaVisione']) || $_POST['dataRichiestaVisione']=="") {
                $this->edit(ER_ASTA_EDIT_DATAVISIONE);
                return false;
            }
            if (!isset($_POST['oraRichiestaVisione']) || $_POST['oraRichiestaVisione']=="") {
                $this->edit(ER_ASTA_EDIT_ORAVISIONE);
                return false;
            }
            $dataRichiestaVisione = $_POST['dataRichiestaVisione'];
            $oraRichiestaVisione = $_POST['oraRichiestaVisione'].":00";
        } 
        
        // Verifica se Prima era in visione e se lo era, se la data/ora erano diverse
        $isEmailVisione = false;
        if ( $_POST['flagRichiestaVisione']=="true") {
            if ( $this->view->relAsteAgenzia["flagRichiestaVisione"]=="false" ) {
                $isEmailVisione = true;
            } else {
                if ( $this->view->relAsteAgenzia["dataRichiestaVisione"]!=$dataRichiestaVisione 
                    || $this->view->relAsteAgenzia["oraRichiestaVisione"]!=$oraRichiestaVisione ) {
                    $isEmailVisione = true;
                }
            }
        }
        
        
        // Set last edit
        $lastEdit = date('Y-m-d H:i:s');
        $DataModifica = substr($lastEdit,0,10).'T'.substr($lastEdit,11,8);
        // Set values
        $data = array(
            ':riferimentoAnnuncio' => $_POST['riferimentoAnnuncio'],
            ':nomeAgente' => $_POST['nomeAgente'],
            ':flagPubblicita' => $_POST['flagPubblicita'],
            ':preferenzaPrezzo' => $_POST['preferenzaPrezzo'],
            ':descrizione' => $_POST['descrizione'],
            ':noteAgenzia' => $_POST['noteAgenzia'],
            ':commentiAgenzia' => $_POST['commentiAgenzia'],
            ':flagRichiestaVisione' => $_POST['flagRichiestaVisione'],
            ':dataRichiestaVisione' => $dataRichiestaVisione,
            ':oraRichiestaVisione' => $oraRichiestaVisione,
            ':DataModifica' => $DataModifica,
            ':DataModifica_d' => $lastEdit
        );
        $where = ' id=:id  ';
        $parameters = array(
            ':id' => $_GET["idrel"]
        );
        
        
        // Update
        if ($relAsteAgModel->updateData($data,$parameters,$where)) {
            
            // Invio App.to Email
            if ($isEmailVisione) {
                // Predisponi dati per Email//Convert MYSQL datetime and construct iCal start, end and issue dates
                $meeting_date = $dataRichiestaVisione." ".$oraRichiestaVisione;
                $meeting_duration = 3600; // 1h
                $meetingstamp = STRTOTIME($meeting_date . " UTC");    
                $dtstart= GMDATE("Ymd\THis\Z",$meetingstamp);
                $dtend= GMDATE("Ymd\THis\Z",$meetingstamp+$meeting_duration);
                $todaystamp = GMDATE("Ymd\THis\Z");

                //Create unique identifier
                $cal_uid = DATE('Ymd').'T'.DATE('His')."-".RAND()."@mydomain.com";

                //Create Mime Boundry
                $mime_boundary = "----Meeting Booking----".MD5(TIME());
                
                //Create ICAL Content (Google rfc 2445 for details and examples of usage) 
                $ical =    'BEGIN:VCALENDAR
                    PRODID:-//Microsoft Corporation//Outlook 11.0 MIMEDIR//EN
                    VERSION:2.0
                    METHOD:PUBLISH
                    BEGIN:VEVENT
                    ORGANIZER:MAILTO:'.$from_address.'
                    DTSTART:'.$dtstart.'
                    DTEND:'.$dtend.'
                    LOCATION:'.$meeting_location.'
                    TRANSP:OPAQUE
                    SEQUENCE:0
                    UID:'.$cal_uid.'
                    DTSTAMP:'.$todaystamp.'
                    DESCRIPTION:'.$meeting_description.'
                    SUMMARY:'.$subject.'
                    PRIORITY:5
                    CLASS:PUBLIC
                    END:VEVENT
                    END:VCALENDAR';   
                
                
                
                //Invia email
                $to      = $this->view->userLogged["email"]; 
                $subject = 'Appuntamento Asta | '.$this->view->platformData["siteName"];
                include ('public/template/utente_apptoasta.php');
                $headers = "From: ".$this->view->platformData["emailFromDesc"]." <".$this->view->platformData["emailFrom"].">". "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8";
                $functions->sendEmail ($to, $subject, $emailText, $headers) ;
            }
            
            
            // View
            $this->edit(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->edit(ER_GENERICO,null);
            return false;
        }
    }
    // POST: Execut EDIT (SOLO ADMIN)
    public function executeEditA() {
        // Check
        if ($this->view->userLogged["role"]!="admin") {
            Session::destroy();
            $this->func->redirectToAction("login/index");
            exit;
        }
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"])) {
            $this->edit(ER_ASTA_EDIT_GENERIC);
            return false;
        }// Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
                
        // Set last edit
        $lastEdit = date('Y-m-d H:i:s');
        $DataModifica = substr($lastEdit,0,10).'T'.substr($lastEdit,11,8);
        // Set values
        $data = array(
            ':Testo' => $_POST['Testo'],
            ':TestoBreve' => $_POST['TestoBreve'],
            ':adminNote' => $_POST['adminNote'],
            ':DataModifica' => $DataModifica,
            ':DataModifica_d' => $lastEdit
        );
        $where = ' id=:id  ';
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
    
    // POST: Remove Asta (SOLO ADMIN)
    public function removeAsta() {
        // Check
        if ($this->view->userLogged["role"]!="admin") { 
            Session::destroy();
            $this->func->redirectToAction("login/index");
            exit;
        }
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"])) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
        // Check POSTS
        if (!isset($_POST["btnRemoveAsta"]) || $_POST["btnRemoveAsta"]=="" || $_GET["iditem"]!=$_POST["btnRemoveAsta"] ) {
            $this->overview(ER_GENERICO);
            return false;
        }
        
        // Elimina Asta
        // Set values
        $data = array(
            ':status' => "deleted"
        );
        $where = ' id=:id ';
        $parameters = array(
            ':id' => $_GET["iditem"]
        );
        
        // Update
        if ($this->model->updateData($data,$parameters,$where)) {
            
            // View
            $this->index();
        } else {
            $this->overview(ER_GENERICO,null);
            return false;
        }
        
    }
    
    // GET: EditImg 
    public function editImg($error=null, $message=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("aste");
        // Get Errors
        $this->view->error = Functions::getError($error);
        $this->view->message = Functions::getMessages($message);
        
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"])) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
        
        if ($this->view->userLogged["role"]!="admin") {
            $relAsteAgModel = new RelAsteAgenzie_Model();
            // Get Record RelAsteAgenzie
            // Checks
            if (!$this->CheckIdItemExistsRel($this->view->userLogged["id"], $_GET["iditem"])) {
                $this->index();
                return false;
            }
            $this->view->relAsteAgenzia = $relAsteAgModel->getDataFromIdAgIdAsta($this->view->userLogged["id"], $_GET["iditem"]);
        }
        
        
        // View
        $this->view->render('aste/editImg', true, HEADER_MAIN);
    }
    // POST: Execut EDIT IMG (SOLO AGENZIA)
    public function executeEditImg() {
        // Check
        if ($this->view->userLogged["role"]=="admin") {
            Session::destroy();
            $this->func->redirectToAction("login/index");
            exit;
        }
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"])) {
            $this->edit(ER_ASTA_EDIT_GENERIC);
            return false;
        }// Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
        $relAsteAgModel = new RelAsteAgenzie_Model();
        // Get Record RelAsteAgenzie
        // Checks
        if (!$this->CheckIdItemExistsRel($this->view->userLogged["id"], $_GET["iditem"])) {
            $this->edit(ER_ASTA_EDIT_GENERIC);
            return false;
        }
        $this->view->relAsteAgenzia = $relAsteAgModel->getDataFromIdAgIdAsta($this->view->userLogged["id"], $_GET["idrel"]);
        
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
        
        // Set last edit
        $lastEdit = date('Y-m-d H:i:s');
        $DataModifica = substr($lastEdit,0,10).'T'.substr($lastEdit,11,8);
        // Set values
        $data = array(
            ':immagine_URL' => $immagine_URL,
            ':DataModifica' => $DataModifica,
            ':DataModifica_d' => $lastEdit
        );
        $where = ' id=:id  ';
        $parameters = array(
            ':id' => $_GET["idrel"]
        );
        
        
        // Update
        if ($relAsteAgModel->updateData($data,$parameters,$where)) {
            // View
            $this->editImg(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->editImg(ER_GENERICO,null);
            return false;
        }
    }
    // POST: Execut EDIT IMG (SOLO ADMIN)
    public function executeEditImgA() {
        // Check
        if ($this->view->userLogged["role"]!="admin") {
            Session::destroy();
            $this->func->redirectToAction("login/index");
            exit;
        }
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"])) {
            $this->edit(ER_ASTA_EDIT_GENERIC);
            return false;
        }// Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
        
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
        
        // Set last edit
        $lastEdit = date('Y-m-d H:i:s');
        $DataModifica = substr($lastEdit,0,10).'T'.substr($lastEdit,11,8);
        // Set values
        $data = array(
            ':immagine_URL' => $immagine_URL,
            ':DataModifica' => $DataModifica,
            ':DataModifica_d' => $lastEdit
        );
        $where = ' id=:id  ';
        $parameters = array(
            ':id' => $_GET["iditem"]
        );
        
        
        // Update
        if ($this->model->updateData($data,$parameters,$where)) {
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
    
    // Check IdItem exists
    function CheckIdItemExistsRel($idAgenzia,$idAsta) {
        if (!isset($idAgenzia) || !isset($idAsta)) {
            return false;
        }
        if ($idAgenzia==null || $idAgenzia=="" || $idAsta==null || $idAsta=="") {
            return false;
        } else {
            $relAsteAgModel = new RelAsteAgenzie_Model();
            if ($relAsteAgModel->getDataFromIdAgIdAsta($idAgenzia,$idAsta)!=NULL) {
                return true;
            }
        }
        return false;
    }
    
    
    
    

}


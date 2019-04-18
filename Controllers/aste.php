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
require 'Models/cap_model.php';
require 'Models/dbcucine_model.php';
require 'Models/dbstrade_model.php';
require 'Models/importdetails_model.php';
require 'Models/relasteimg_model.php';

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
        $functionsModel = new Functions();
        $asteModel = new Aste_Model();

        // GET ASTE LIST
        if ($this->view->userLogged["role"]=="admin") {
            $arrAsteListAll = $asteModel->getAsteList(null," id DESC ");
        } else {
            $relAsAgModel = new RelAsteAgenzie_Model();
            $arrAsteAgenziaList = $relAsAgModel->getRelAsteAgenzieList($this->view->userLogged["id"], Null, Null);
            $relAgPrefViewModel = new RelAgenziePrefView_Model();
            $relAgPrefViewList = $relAgPrefViewModel->getRelAgenziePrefList($this->view->userLogged["id"], NULL,NULL);
            $arrAsteListAgPreFilter =array();
            if (is_array($relAgPrefViewList) || is_object($relAgPrefViewList)) {
                $relAgPrefViewListComuni = $relAgPrefViewModel->getRelAgenziePrefList($this->view->userLogged["id"], "comune",NULL);
                $relAgPrefViewListProv = $relAgPrefViewModel->getRelAgenziePrefList($this->view->userLogged["id"], "provincia",NULL);
                $relAgPrefViewListCap = $relAgPrefViewModel->getRelAgenziePrefList($this->view->userLogged["id"], "cap",NULL);
                $relAgPrefViewListComuniT = $relAgPrefViewModel->getRelAgenziePrefList($this->view->userLogged["id"], "comuneTribunale",NULL);

                // Setup query
                $data = ' * ';
                $where = ' status!=:statusDeleted ';
                $parameters = array();
                $parameters[":statusDeleted"] = 'deleted';

                // Comune
                $whereComuni = "";
                if (is_array($relAgPrefViewListComuni) || is_object($relAgPrefViewListComuni)) {
                    $whereComuni = " ( ";
                    $i = 0;
                    foreach($relAgPrefViewListComuni as $prefView){
                        $i++;
                        $prefViewComune = $functionsModel->ConvertCodiceIstat($prefView["idOggetto"]);
                        $whereComuni .= ' CodiceComune=:CodiceComune'.$i." ";
                        $parameters[":CodiceComune".$i] = $prefViewComune;
                        if ($i==sizeof($relAgPrefViewListComuni)) {
                            $whereComuni .= ") ";
                        } else {
                            $whereComuni .= " OR ";
                        }
                    }
                }
                // Provincia
                $whereProv = "";
                if (is_array($relAgPrefViewListProv) || is_object($relAgPrefViewListProv)) {
                    $whereProv = " ( ";
                    $i = 0;
                    foreach($relAgPrefViewListProv as $prefView){
                        $i++;
                        $whereProv .= ' Provincia=:Provincia'.$i." ";
                        $parameters[":Provincia".$i] = $prefView["idOggetto"];
                        if ($i==sizeof($relAgPrefViewListProv)) {
                            $whereProv .= ") ";
                        } else {
                            $whereProv .= " OR ";
                        }
                    }
                }
                // Cap
                $whereCap = "";
                if (is_array($relAgPrefViewListCap) || is_object($relAgPrefViewListCap)) {
                    $whereCap = " ( ";
                    $i = 0;
                    foreach($relAgPrefViewListCap as $prefView){
                        $i++;
                        $whereCap .= ' Cap=:Cap'.$i." ";
                        $parameters[":Cap".$i] = $prefView["idOggetto"];
                        if ($i==sizeof($relAgPrefViewListCap)) {
                            $whereCap .= ") ";
                        } else {
                            $whereCap .= " OR ";
                        }
                    }
                }
                // ComuneT
                $whereComuniT = "";
                if (is_array($relAgPrefViewListComuniT) || is_object($relAgPrefViewListComuniT)) {
                    $whereComuniT = " ( ";
                    $i = 0;
                    foreach($relAgPrefViewListComuniT as $prefView){
                        $i++;
                        $prefViewComune = $functionsModel->ConvertCodiceIstat($prefView["idOggetto"]);
                        $whereComuniT .= ' codiceComuneTribunale=:codiceComuneTribunale'.$i." ";
                        $parameters[":codiceComuneTribunale".$i] = $prefViewComune;
                        if ($i==sizeof($relAgPrefViewListComuniT)) {
                            $whereComuniT .= ") ";
                        } else {
                            $whereComuniT .= " OR ";
                        }
                    }
                }

                if ($whereComuni!="") {
                    $where .= " AND " . $whereComuni;
                }
                if ($whereProv!="") {
                    $where .= " AND " . $whereProv;
                }
                if ($whereCap!="") {
                    $where .= " AND " . $whereCap;
                }
                if ($whereComuniT!="") {
                    $where .= " AND " . $whereComuniT;
                }

                // Get data
                $arrAsteListAll = $asteModel->getAsteListByFiltering($data,$where,$parameters,null);
            }
        }
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
                                $Status =  $rel["statusImportazione"];
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
                    // Rge
                    if (isset($_POST["rgeFilter"]) && $_POST["rgeFilter"]!=""  && $_POST["rgeFilter"]!=null) {
                        $toInsert = false;
                        if ($_POST["rgeFilter"]==$item["rge"]) {
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

        // Leggi Img Aggiuntive rispetto alla prima
        $relImgModel = new RelAsteImg_Model();
        if ($this->view->userLogged["role"]=="admin") {
            $arrImg = $relImgModel->getRelAsteImgList($_GET["iditem"],0,$this->view->userLogged["role"]);
        } else {
            $arrImg = $relImgModel->getRelAsteImgList($_GET["iditem"],$this->view->userLogged["id"],$this->view->userLogged["role"]);
        }
        $this->view->relImg = $arrImg;


        
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
            $dataRichiestaVisione = substr($_POST['dataRichiestaVisione'],6,4)."-".substr($_POST['dataRichiestaVisione'],3,2)."-".substr($_POST['dataRichiestaVisione'],0,2) ;
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
                $funcionsModel = new Functions();

                // Predisponi dati per Email//Convert MYSQL datetime and construct iCal start, end and issue dates
                $meeting_date = $dataRichiestaVisione." ".$oraRichiestaVisione;
                $meeting_duration = 3600; // 1h
                $meetingstamp = STRTOTIME($meeting_date . " UTC");    
                $dtstart= GMDATE("Ymd\THis\Z",$meetingstamp);
                $dtend= GMDATE("Ymd\THis\Z",$meetingstamp+$meeting_duration);
                $todaystamp = GMDATE("Ymd\THis\Z");
                $meeting_location = $this->view->data["ComuneProvinciaCompleto"]." - ".$this->view->data["Strada_testo"]." ".$this->view->data["Indirizzo"]." ".$this->view->data["Civico"];
                $meeting_description = "VISIONE ".$meeting_location;

                //Create unique identifier
                $cal_uid = DATE('Ymd').'T'.DATE('His')."-".RAND()."@ym-dev.com";

                //Create Mime Boundry
                $mime_boundary = "----Meeting Booking----".MD5(TIME());

                // Predisponi INVIO
                $to      = $this->view->userLogged["email"]; // "support@wedevteam.com";
                $subject = 'Appuntamento VISIONE | '.$this->view->platformData["siteName"];
                include ('public/template/utente_apptoasta.php');
                $headers = "From: ".$this->view->platformData["emailFromDesc"]." <".$this->view->platformData["emailFrom"].">". "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                // $headers .= "Content-type: text/html; charset=UTF-8";
                $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
                $headers .= "Content-class: urn:content-classes:calendarmessage\n";

                //Create Email Body (HTML)
                $message = "";
                $message .= "--$mime_boundary\n";
                $message .= "Content-Type: text/html; charset=UTF-8\n";
                $message .= "Content-Transfer-Encoding: 8bit\n\n";

                $message .= "<html>\n";
                $message .= "<body>\n";
                $message .= '<p>Gentile Agenzia,</p>';
                $message .= '<p>'.$this->view->platformData["siteName"]." ti invia appuntamento per la Visione dell'Immobile all'Asta di </p>".$meeting_location;
                $message .= "</body>\n";
                $message .= "</html>\n";
                $message .= "--$mime_boundary\n";

                //Create ICAL Content (Google rfc 2445 for details and examples of usage) 
                $ical =    'BEGIN:VCALENDAR
                    PRODID:-//Microsoft Corporation//Outlook 11.0 MIMEDIR//EN
                    VERSION:2.0
                    METHOD:PUBLISH
                    BEGIN:VEVENT
                    ORGANIZER:MAILTO:'.$this->view->platformData["emailFrom"].'
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
                $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST\n';
                $message .= "Content-Transfer-Encoding: 8bit\n\n";
                $message .= $ical;


                //Invia email
                if (!$funcionsModel->sendEmailWithResult ($to, $subject, $message, $headers) ){
                    $this->edit(ER_ASTA_INVIOMAIL_VISIONE,null);
                    return false;
                }
            }
            
            
            // View
            $this->edit(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->edit(ER_GENERICO,null);
            return false;
        }
    }
    // POST: ABILITA RICEZIONE APP.TO ASTA e INVIO EMAIL    (SOLO AGENZIA)
    public function setNotyOn() {
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

        // Set values
        $data = array(
            ':isNoty' => "on"
        );
        $where = ' id=:id  ';
        $parameters = array(
            ':id' => $_GET["idrel"]
        );


        // Update
        if ($relAsteAgModel->updateData($data,$parameters,$where)) {

            // Invio App.to Email

            $funcionsModel = new Functions();

            // Predisponi dati per Email//Convert MYSQL datetime and construct iCal start, end and issue dates
            $meeting_date = $this->view->data["dataAsta"]." 10:00:00";
            $meeting_duration = 3600; // 1h
            $meetingstamp = STRTOTIME($meeting_date . " UTC");
            $dtstart= GMDATE("Ymd\THis\Z",$meetingstamp);
            $dtend= GMDATE("Ymd\THis\Z",$meetingstamp+$meeting_duration);
            $todaystamp = GMDATE("Ymd\THis\Z");
            $meeting_location = $this->view->data["ComuneProvinciaCompleto"]." - ".$this->view->data["Strada_testo"]." ".$this->view->data["Indirizzo"]." ".$this->view->data["Civico"];
            $meeting_description = "ASTA ".$meeting_location;

            //Create unique identifier
            $cal_uid = DATE('Ymd').'T'.DATE('His')."-".RAND()."@ym-dev.com";

            //Create Mime Boundry
            $mime_boundary = "----Meeting Booking----".MD5(TIME());

            // Predisponi INVIO
            $to      = $this->view->userLogged["email"];  // "pamela.palazzini@wedevteam.com";
            $subject = 'Appuntamento ASTA | '.$this->view->platformData["siteName"];
            include ('public/template/utente_apptoasta.php');
            $headers = "From: ".$this->view->platformData["emailFromDesc"]." <".$this->view->platformData["emailFrom"].">". "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            // $headers .= "Content-type: text/html; charset=UTF-8";
            $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
            $headers .= "Content-class: urn:content-classes:calendarmessage\n";

            //Create Email Body (HTML)
            $message = "";
            $message .= "--$mime_boundary\n";
            $message .= "Content-Type: text/html; charset=UTF-8\n";
            $message .= "Content-Transfer-Encoding: 8bit\n\n";

            $message .= "<html>\n";
            $message .= "<body>\n";
            $message .= '<p>Gentile Agenzia,</p>';
            $message .= '<p>'.$this->view->platformData["siteName"]." ti invia appuntamento della data dell'Asta dell'Immobile di </p>".$meeting_location;
            $message .= "</body>\n";
            $message .= "</html>\n";
            $message .= "--$mime_boundary\n";

            //Create ICAL Content (Google rfc 2445 for details and examples of usage)
            $ical =    'BEGIN:VCALENDAR
                PRODID:-//Microsoft Corporation//Outlook 11.0 MIMEDIR//EN
                VERSION:2.0
                METHOD:PUBLISH
                BEGIN:VEVENT
                ORGANIZER:MAILTO:'.$this->view->platformData["emailFrom"].'
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
            $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST\n';
            $message .= "Content-Transfer-Encoding: 8bit\n\n";
            $message .= $ical;


            //Invia email
            if (!$funcionsModel->sendEmailWithResult ($to, $subject, $message, $headers) ){
                $this->edit(ER_ASTA_INVIOMAIL_VISIONE,null);
                return false;
            }



            // View
            $this->edit(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->edit(ER_GENERICO,null);
            return false;
        }
    }
    // POST: DISABILITA RICEZIONE APP.TO ASTA   (SOLO AGENZIA)
    public function setNotyOff() {
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

        // Set values
        $data = array(
            ':isNoty' => "off"
        );
        $where = ' id=:id  ';
        $parameters = array(
            ':id' => $_GET["idrel"]
        );


        // Update
        if ($relAsteAgModel->updateData($data,$parameters,$where)) {
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

        // Leggi Img Aggiuntive rispetto alla prima
        $relImgModel = new RelAsteImg_Model();
        if ($this->view->userLogged["role"]=="admin") {
            $arrImg = $relImgModel->getRelAsteImgList($_GET["iditem"],0,$this->view->userLogged["role"]);
        } else {
            $arrImg = $relImgModel->getRelAsteImgList($_GET["iditem"],$this->view->userLogged["id"],$this->view->userLogged["role"]);
        }
        $this->view->relImg = $arrImg;
        
        // View
        $this->view->render('aste/editImg', true, HEADER_MAIN);
    }
    // POST: Execut EDIT IMG - IMMAGINE PRINCIPALE -(SOLO AGENZIA)
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
                $this->index(ER_UPLOADFILE_FILENONVALIDO);
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
    // POST: Execut EDIT IMG - IMMAGINI AGGIUNTIVE -(SOLO AGENZIA)
    public function executeEditImages() {
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
        // Count total files
        $countfiles = count($_FILES['avatar']['name']);
        // Looping all files
        for($i=0;$i<$countfiles;$i++){
            if (basename($_FILES["avatar"]["name"][$i])!='') {
                if ($_FILES["avatar"]["error"][$i] > 0) {
                    $this->index(ER_UPLOADFILE_FILENONVALIDO);
                    return false;
                }
                if ($_FILES["avatar"]["type"][$i] != "image/png"
                    && $_FILES["avatar"]["type"][$i]!= "image/jpeg"
                    && $_FILES["avatar"]["type"][$i]!= "image/jpg") {
                    $this->editImg(ER_UPLOADFILE_ESTENSIONENONVALIDA,null);
                    return false;
                }
                if ( ($_FILES["avatar"]["size"][$i]) > 5000000 ) {
                    $this->editImg(ER_UPLOADFILE_SIZENONVALIDA,null);
                    return false;
                }
                // UPLOAD EFFETTIVO
                if ($fileName0 = basename($_FILES["avatar"]["name"][$i])==""){$imageName=$_POST['avatar'];}
                else{
                    $fileName0 = basename($_FILES["avatar"]["name"][$i]);
                    $temp0 = explode(".", $_FILES["avatar"]["name"][$i]);
                    $imageName = 'img-'.$_GET["iditem"]."-".round(microtime(true)).$i. '.' . end($temp0);
                }
                if ($fileName0 = basename($_FILES["avatar"]["name"][$i])!=""){
                    $target_path = $_SERVER['DOCUMENT_ROOT'] . "/flussi/public/images/".$imageName;
                    if (!move_uploaded_file($_FILES["avatar"]["tmp_name"][$i], $target_path)){
                        $this->editImg(ER_UPLOADFILE_PROBLEMAUPLOAD,null);
                        return false;
                    }
                }
            }

            if ( $imageName=="" ) {
                $this->edit(ER_UPLOADFILE_FILESNONVALIDI);
                return false;
            }
            $immagine_URL = URL .'public/images/'. $imageName;

            // Set last edit
            $lastEdit = date('Y-m-d H:i:s');
            $DataModifica = substr($lastEdit,0,10).'T'.substr($lastEdit,11,8);
            // Set values
            $data = array(
                ':idAsta' =>  $_GET["iditem"],
                ':idAgenzia' => $this->view->userLogged["id"],
                ':fonte' => "manuale",
                ':immagine_URL' => $immagine_URL,
                ':IDImmagine' => 0,
                ':immagine_Titolo' => "",
                ':immagine_TipoFoto' => "F",
                ':immagine_Posizione' => 1,
                ':immagine_Titolo' => "",
                ':DataModifica' => $DataModifica,
                ':DataModifica_d' => $lastEdit
            );
            $relAsteImgModel = new RelAsteImg_Model();
            $idRecord = $relAsteImgModel->create($data);
        }


        // ====================================================



        // UPdate Last edit su rel
        // Set values
        $data = array(
            ':DataModifica_d' => $lastEdit,
            ':DataModifica' => $DataModifica
        );
        $where = " id=:id ";
        $parameters = array();
        $parameters[":id"] = $_GET["idrel"];


        // Update
        if ($relAsteAgModel->updateData($data,$parameters,$where)) {
            // View
            $this->editImg(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->editImg(ER_GENERICO,null);
            return false;
        }
    }
    // POST: Remove IMG AGGIUNTIVA (SOLO AGENZIA)
    public function removeImgAgency() {
        // Check
        if ($this->view->userLogged["role"]=="admin") {
            Session::destroy();
            $this->func->redirectToAction("login/index");
            exit;
        }
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"])) {
            $this->editImg();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
        // Check POSTS
        if (!isset($_GET["idrel"]) || $_GET["idrel"]=="") {
            $this->editImg(ER_GENERICO);
            return false;
        }
        // Check id rel
        if (! $this->CheckIdImgAgenziaExists($_GET["idrel"],$_GET["iditem"],$this->view->userLogged["id"])) {
            $this->editImg(ER_GENERICO);
            return false;
        }

        // Elimina Immagine
        $where = '  id=:id AND idAgenzia=:idAgenzia AND idAsta=:idAsta AND fonte=:fonte ';
        $parameters = array();
        $parameters[":id"] = $_GET["idrel"];
        $parameters[":idAsta"] = $_GET["iditem"];
        $parameters[":idAgenzia"] = $this->view->userLogged["id"];
        $parameters[":fonte"] = "manuale";


        // Delete
        $relAsteImgModel = new RelAsteImg_Model();
        if ($relAsteImgModel->deleteItem($where,$parameters)) {
            // View
            $this->editImg(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->editImg(ER_GENERICO,null);
            return false;
        }

    }

    // POST: Execut EDIT IMG - IMMAGINE PRINCIPALE (SOLO ADMIN)
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
                $this->index(ER_UPLOADFILE_FILENONVALIDO);
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
    // POST: Execut EDIT IMG - IMMAGINI AGGIUNTIVE -(SOLO ADMIN)
    public function executeEditImagesA() {
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
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);

        // ====================================================
        //CHECKS FILES
        // Count total files
        $countfiles = count($_FILES['avatar']['name']);
        // Looping all files
        for($i=0;$i<$countfiles;$i++){
            if (basename($_FILES["avatar"]["name"][$i])!='') {
                if ($_FILES["avatar"]["error"][$i] > 0) {
                    $this->index(ER_UPLOADFILE_FILENONVALIDO);
                    return false;
                }
                if ($_FILES["avatar"]["type"][$i] != "image/png"
                    && $_FILES["avatar"]["type"][$i]!= "image/jpeg"
                    && $_FILES["avatar"]["type"][$i]!= "image/jpg") {
                    $this->editImg(ER_UPLOADFILE_ESTENSIONENONVALIDA,null);
                    return false;
                }
                if ( ($_FILES["avatar"]["size"][$i]) > 5000000 ) {
                    $this->editImg(ER_UPLOADFILE_SIZENONVALIDA,null);
                    return false;
                }
                // UPLOAD EFFETTIVO
                if ($fileName0 = basename($_FILES["avatar"]["name"][$i])==""){$imageName=$_POST['avatar'];}
                else{
                    $fileName0 = basename($_FILES["avatar"]["name"][$i]);
                    $temp0 = explode(".", $_FILES["avatar"]["name"][$i]);
                    $imageName = 'img-'.$_GET["iditem"]."-".round(microtime(true)).$i. '.' . end($temp0);
                }
                if ($fileName0 = basename($_FILES["avatar"]["name"][$i])!=""){
                    $target_path = $_SERVER['DOCUMENT_ROOT'] . "/flussi/public/images/".$imageName;
                    if (!move_uploaded_file($_FILES["avatar"]["tmp_name"][$i], $target_path)){
                        $this->editImg(ER_UPLOADFILE_PROBLEMAUPLOAD,null);
                        return false;
                    }
                }
            }

            if ( $imageName=="" ) {
                $this->edit(ER_UPLOADFILE_FILESNONVALIDI);
                return false;
            }
            $immagine_URL = URL .'public/images/'. $imageName;

            // Set last edit
            $lastEdit = date('Y-m-d H:i:s');
            $DataModifica = substr($lastEdit,0,10).'T'.substr($lastEdit,11,8);
            // Set values
            $data = array(
                ':idAsta' =>  $_GET["iditem"],
                ':idAgenzia' => 0,
                ':fonte' => "manuale",
                ':immagine_URL' => $immagine_URL,
                ':IDImmagine' => 0,
                ':immagine_Titolo' => "",
                ':immagine_TipoFoto' => "F",
                ':immagine_Posizione' => 1,
                ':immagine_Titolo' => "",
                ':DataModifica' => $DataModifica,
                ':DataModifica_d' => $lastEdit
            );
            $relAsteImgModel = new RelAsteImg_Model();
            $idRecord = $relAsteImgModel->create($data);
        }


        // ====================================================



        // UPdate Last edit su rel
        // Set values
        $data = array(
            ':DataModifica_d' => $lastEdit,
            ':DataModifica' => $DataModifica
        );
        $where = " id=:id ";
        $parameters = array();
        $parameters[":id"] = $_GET["idrel"];


        // Update
        if ($this->model->updateData($data,$parameters,$where)) {
            // View
            $this->editImg(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->editImg(ER_GENERICO,null);
            return false;
        }
    }
    // POST: Remove IMG AGGIUNTIVA (SOLO ADMIN)
    public function removeImgA() {
        // Check
        if ($this->view->userLogged["role"]!="admin") {
            Session::destroy();
            $this->func->redirectToAction("login/index");
            exit;
        }
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"])) {
            $this->editImg();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
        // Check POSTS
        if (!isset($_GET["idrel"]) || $_GET["idrel"]=="") {
            $this->editImg(ER_GENERICO);
            return false;
        }
        // Check id rel
        if (! $this->CheckIdImgAdminExists($_GET["idrel"],$_GET["iditem"])) {
            $this->editImg(ER_GENERICO);
            return false;
        }

        // Elimina Immagine
        $where = '  id=:id ';
        $parameters = array();
        $parameters[":id"] = $_GET["idrel"];

        // Delete
        $relAsteImgModel = new RelAsteImg_Model();
        if ($relAsteImgModel->deleteItem($where,$parameters)) {
            // View
            $this->editImg(null,MESS_MODIFICHE_SALVATE);
        } else {
            $this->editImg(ER_GENERICO,null);
            return false;
        }

    }


    // GET: Gallery
    public function gallery($error=null, $message=null) {
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

        // Leggi Img Aggiuntive rispetto alla prima
        $relImgModel = new RelAsteImg_Model();
        if ($this->view->userLogged["role"]=="admin") {
            $arrImg = $relImgModel->getRelAsteImgList($_GET["iditem"],0,$this->view->userLogged["role"]);
        } else {
            $arrImg = $relImgModel->getRelAsteImgList($_GET["iditem"],$this->view->userLogged["id"],$this->view->userLogged["role"]);
        }
        $this->view->relImg = $arrImg;

        // View
        $this->view->render('aste/gallery', true, HEADER_MAIN);
    }



    // GET: Export
    public function export($error=null) {
        // Check user role
        if ($this->view->userLogged["role"]=="admin") {
            Session::destroy();
            $this->func->redirectToAction("login/index");
            exit;
        }

        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("aste");
        // Get Errors
        $this->view->error = Functions::getError($error);

        // Get Data
        $asteModel = new Aste_Model();
        $arrAsteListAll = $asteModel->getAsteList(null," id DESC ");
        $relAsAgModel = new RelAsteAgenzie_Model();
        $arrAsteAgenziaList = $relAsAgModel->getRelAsteAgenzieList($this->view->userLogged["id"], Null, Null);
        $relAgPrefViewModel = new RelAgenziePrefView_Model();
        $relAgPrefViewList = $relAgPrefViewModel->getRelAgenziePrefList($this->view->userLogged["id"], NULL,NULL);
        $arrAsteListAgPreFilter =array();
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
                // FlagPubb
                $flagPubb = "";
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
                                $Status =  $rel["statusImportazione"];
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
                                // FlagPubb
                                $flagPubb = $rel["flagPubblicita"];
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
                    "flagRichiestaVisione" =>$flagRichiestaVisione,
                    "dataRichiestaVisione" =>$dataRichiestaVisione,
                    "flagPubblicita" => $flagPubb
                );

                if ($this->view->userLogged["role"]!="admin" && $toInsert) {
                    array_push($arrAsteListAgPreFilter, $arrItem);
                }

                // APPLICA FILTRI DI ESPORTAZIONE
                if (isset($_POST["btnExport"])) {
                    // codiciComuni
                    if (isset($_POST["codiciComuni"]) && $_POST["codiciComuni"]!=null && $_POST["codiciComuni"]!="") {
                        $toInsert = false;
                        foreach($_POST["codiciComuni"] as $comune){
                            if ($item["CodiceComune"]== $functionsModel->ConvertCodiceIstat($comune)) {
                                $toInsert = true;
                            }
                        }
                    }
                    // cap
                    if ($toInsert) {
                        if (isset($_POST["capComuni"]) && $_POST["capComuni"]!=null && $_POST["capComuni"]!="") {
                            $toInsert = false;
                            foreach($_POST["capComuni"] as $cap){
                                if ($item["Cap"]==$cap) {
                                    $toInsert = true;
                                }
                            }
                        }
                    }
                    // codiciComuniTribunali
                    if ($toInsert) {
                        if (isset($_POST["codiciComuniTribunali"]) && $_POST["codiciComuniTribunali"]!=null && $_POST["codiciComuniTribunali"]!="") {
                            $toInsert = false;
                            foreach($_POST["codiciComuniTribunali"] as $comune){
                                if ($item["codiceComuneTribunale"]== $functionsModel->ConvertCodiceIstat($comune)) {
                                    $toInsert = true;
                                }
                            }
                        }
                    }
                    // OffertaMinima DA
                    if ($toInsert) {
                        if (isset($_POST["offertaMinDa"]) && $_POST["offertaMinDa"]!=null && $_POST["offertaMinDa"]!="") {
                            if ($item["importoOffertaMinima"]<$_POST["offertaMinDa"]) {
                                $toInsert = false;
                            }
                        }
                    }
                    // OffertaMinima A
                    if ($toInsert) {
                        if (isset($_POST["offertaMinA"]) && $_POST["offertaMinA"]!=null && $_POST["offertaMinA"]!="") {
                            if ($item["importoOffertaMinima"]>$_POST["offertaMinA"]) {
                                $toInsert = false;
                            }
                        }
                    }
                    // superficie DA
                    if ($toInsert) {
                        if (isset($_POST["superficieDa"]) && $_POST["superficieDa"]!=null && $_POST["superficieDa"]!="") {
                            if ($item["MQSuperficie"]<$_POST["superficieDa"]) {
                                $toInsert = false;
                            }
                        }
                    }
                    // superficie A
                    if ($toInsert) {
                        if (isset($_POST["superficieA"]) && $_POST["superficieA"]!=null && $_POST["superficieA"]!="") {
                            if ($item["MQSuperficie"]>$_POST["superficieA"]) {
                                $toInsert = false;
                            }
                        }
                    }
                    // NrLocali DA
                    if ($toInsert) {
                        if (isset($_POST["NrLocaliDa"]) && $_POST["NrLocaliDa"]!=null && $_POST["NrLocaliDa"]!="") {
                            if ($item["NrLocali"]<$_POST["NrLocaliDa"]) {
                                $toInsert = false;
                            }
                        }
                    }
                    // NrLocali A
                    if ($toInsert) {
                        if (isset($_POST["NrLocaliA"]) && $_POST["NrLocaliA"]!=null && $_POST["NrLocaliA"]!="") {
                            if ($item["NrLocali"]>$_POST["NrLocaliA"]) {
                                $toInsert = false;
                            }
                        }
                    }
                    // Flag Pubblicità
                    if ($toInsert) {
                        if (isset($_POST["flagPubblicita"]) && $_POST["flagPubblicita"]!=null && $_POST["flagPubblicita"]!="") {
                        if ($flagPubb!=$_POST["flagPubblicita"]) {
                            $toInsert = false;
                        }
                        }
                    }
                    // Status Esportazione
                    if ($toInsert) {
                        if (isset($_POST["statusExport"]) && $_POST["statusExport"]!=null && $_POST["statusExport"]!="") {
                            if ($Status!=$_POST["statusExport"]) {
                                $toInsert = false;
                            }
                        }
                    }
                    // Data Asta DA
                    if ($toInsert) {
                        if (isset($_POST["dataAstaDa"]) && $_POST["dataAstaDa"]!=null && $_POST["dataAstaDa"]!="") {
                            if ($item["dataAsta"]<$functionsModel->transformDateFormat(1,$_POST["dataAstaDa"])) {
                                $toInsert = false;
                            }
                        }
                    }
                    // Data Asta A
                    if ($toInsert) {
                        if (isset($_POST["dataAstaA"]) && $_POST["dataAstaA"]!=null && $_POST["dataAstaA"]!="") {
                            if ($item["dataAsta"]>$functionsModel->transformDateFormat(1,$_POST["dataAstaA"])) {
                                $toInsert = false;
                            }
                        }
                    }
                    // Categorie
                    if ($toInsert) {
                        if (isset($_POST["idCategorie"]) && $_POST["idCategorie"]!=null && $_POST["idCategorie"]!="") {
                            $toInsert = false;
                            foreach($_POST["idCategorie"] as $cat){
                                if ($item["Categoria"]==$cat) {
                                    $toInsert = true;
                                }
                            }
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



        if (isset($_POST["btnExport"])) {
//            if (sizeof($arrAsteList)>0) {
//                $arrAsteListFiltrato = array();
//                // APPLICA FILTRI DI ESPORTAZIONE
//                foreach ($arrAsteList as $item){
//                    $isToInsert = true;
//
//                    if ($isToInsert) {
//                        // codiciComuni
//                        if (isset($_POST["codiciComuni"]) && $_POST["codiciComuni"]!=null && $_POST["codiciComuni"]!="") {
//                            $isToInsert = false;
//                            foreach($_POST["codiciComuni"] as $comune){
//                                if ($item["CodiceComune"]== $functionsModel->ConvertCodiceIstat($comune)) {
//                                    $isToInsert = true;
//                                }
//                            }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // cap
//                        if (isset($_POST["capComuni"]) && $_POST["capComuni"]!=null && $_POST["capComuni"]!="") {
//                            $isToInsert = false;
//                            foreach($_POST["capComuni"] as $cap){
//                                if ($item["Cap"]==$cap) {
//                                    $isToInsert = true;
//                                }
//                            }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // codiciComuniTribunali
//                        if (isset($_POST["codiciComuniTribunali"]) && $_POST["codiciComuniTribunali"]!=null && $_POST["codiciComuniTribunali"]!="") {
//                            $isToInsert = false;
//                            foreach($_POST["codiciComuniTribunali"] as $comune){
//                                if ($item["ComuneTribunale"]== $functionsModel->ConvertCodiceIstat($comune)) {
//                                    $isToInsert = true;
//                                }
//                            }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // OffertaMinima DA
//                        if (isset($_POST["offertaMinDa"]) && $_POST["offertaMinDa"]!=null && $_POST["offertaMinDa"]!="") {
//                            if ($item["importoOffertaMinima"]<$_POST["offertaMinDa"]) {
//                                $isToInsert = false;
//                            }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // OffertaMinima A
//                        if (isset($_POST["offertaMinA"]) && $_POST["offertaMinA"]!=null && $_POST["offertaMinA"]!="") {
//                            if ($item["importoOffertaMinima"]>$_POST["offertaMinA"]) {
//                                $isToInsert = false;
//                            }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // superficie DA
//                        if (isset($_POST["superficieDa"]) && $_POST["superficieDa"]!=null && $_POST["superficieDa"]!="") {
//                            if ($item["MQSuperficie"]<$_POST["superficieDa"]) {
//                                $isToInsert = false;
//                            }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // superficie A
//                        if (isset($_POST["superficieA"]) && $_POST["superficieA"]!=null && $_POST["superficieA"]!="") {
//                            if ($item["MQSuperficie"]>$_POST["superficieA"]) {
//                                $isToInsert = false;
//                            }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // NrLocali DA
//                        if (isset($_POST["NrLocaliDa"]) && $_POST["NrLocaliDa"]!=null && $_POST["NrLocaliDa"]!="") {
//                            if ($item["NrLocali"]<$_POST["NrLocaliDa"]) {
//                                $isToInsert = false;
//                            }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // NrLocali A
//                        if (isset($_POST["NrLocaliA"]) && $_POST["NrLocaliA"]!=null && $_POST["NrLocaliA"]!="") {
//                            if ($item["NrLocali"]>$_POST["NrLocaliA"]) {
//                                $isToInsert = false;
//                            }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // Flag Pubblicità
//                        if (isset($_POST["flagPubblicita"]) && $_POST["flagPubblicita"]!=null && $_POST["flagPubblicita"]!="") {
////                        if ($item["NrLocali"]!=$_POST["flagPubblicita"]) {
////                            $isToInsert = false;
////                        }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // Status Esportazione
//                        if (isset($_POST["statusExport"]) && $_POST["statusExport"]!=null && $_POST["statusExport"]!="") {
//                            if ($item["status"]!=$_POST["statusExport"]) {
//                                $isToInsert = false;
//                            }
//                        }
//                    }
//
//
//                    if ($isToInsert) {
//                        // Data Asta DA
//                        if (isset($_POST["dataAstaDa"]) && $_POST["dataAstaDa"]!=null && $_POST["dataAstaDa"]!="") {
//                            if ($item["dataAsta"]<$functionsModel->transformDateFormat(1,$_POST["dataAstaDa"])) {
//                                $isToInsert = false;
//                            }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // Data Asta A
//                        if (isset($_POST["dataAstaA"]) && $_POST["dataAstaA"]!=null && $_POST["dataAstaA"]!="") {
//                            if ($item["dataAsta"]>$functionsModel->transformDateFormat(1,$_POST["dataAstaA"])) {
//                                $isToInsert = false;
//                            }
//                        }
//                    }
//                    if ($isToInsert) {
//                        // Categorie
//                        if (isset($_POST["idCategorie"]) && $_POST["idCategorie"]!=null && $_POST["idCategorie"]!="") {
//                            $isToInsert = false;
//                            foreach($_POST["idCategorie"] as $cat){
//                                if ($item["Categoria"]==$cat) {
//                                    $isToInsert = true;
//                                }
//                            }
//                        }
//                    }
//
//                    // Set values
//                    $arrItem = array(
//                        "id" =>$item["id"],
//                        "ComuneTribunale" =>$item["ComuneTribunale"],
//                        "SiglaProvTribunale" =>$item["SiglaProvTribunale"],
//                        "codiceComuneTribunale" =>$item["codiceComuneTribunale"],
//                        "linkTribunale" =>$item["linkTribunale"],
//                        "rge" =>$item["rge"],
//                        "lotto" =>$item["lotto"],
//                        "tipoProcedura" =>$item["tipoProcedura"],
//                        "rito" =>$item["rito"],
//                        "giudice" =>$item["giudice"],
//                        "delegato" =>$item["delegato"],
//                        "custode" =>$item["custode"],
//                        "curatore" =>$item["curatore"],
//                        "valorePerizia" =>$item["valorePerizia"],
//                        "dataPubblicazione" =>$item["dataPubblicazione"],
//                        "noteGeneriche" =>$item["noteGeneriche"],
//                        "datiCatastali" =>$item["datiCatastali"],
//                        "disponibilita" =>$item["disponibilita"],
//                        "importoBaseAsta" =>$item["importoBaseAsta"],
//                        "importoOffertaMinima" =>$item["importoOffertaMinima"],
//                        "noteAggiuntive" =>$item["noteAggiuntive"],
//                        "dataAsta" =>$item["dataAsta"],
//                        "linkAllegati" =>$item["linkAllegati"],
//                        "CodiceComune" =>$item["CodiceComune"],
//                        "Comune" =>$item["Comune"],
//                        "Provincia" =>$item["Provincia"],
//                        "ComuneProvinciaCompleto" =>$item["ComuneProvinciaCompleto"],
//                        "Strada" =>$item["Strada"],
//                        "Strada_testo" =>$item["Strada_testo"],
//                        "Indirizzo" =>$item["Indirizzo"],
//                        "Civico" =>$item["Civico"],
//                        "Cap" =>$item["Cap"],
//                        "Latitudine" =>$item["Latitudine"],
//                        "Longitudine" =>$item["Longitudine"],
//                        "Categoria" =>$item["Categoria"],
//                        "IDTipologia" =>$item["IDTipologia"],
//                        "NrLocali" =>$item["NrLocali"],
//                        "MQSuperficie" =>$item["MQSuperficie"],
//                        "TipoProprieta" =>$item["TipoProprieta"],
//                        "ClasseCatastale" =>$item["ClasseCatastale"],
//                        "Titolo" =>$item["Titolo"],
//                        "Testo" =>$item["Testo"] ,
//                        "TestoBreve" =>$item["TestoBreve"],
//                        "StatoImmobile" =>$item["StatoImmobile"],
//                        "immagine_URL" =>$item["immagine_URL"] ,
//                        "status" =>$item["status"] ,
//                        "Prezzo" =>$item["Prezzo"] ,
//                        "riferimentoAnnuncio" =>$item["riferimentoAnnuncio"] ,
//                        "nomeAgente" =>$item["nomeAgente"] ,
//                        "flagRichiestaVisione" =>$item["flagRichiestaVisione"] ,
//                        "dataRichiestaVisione" =>$item["dataRichiestaVisione"] ,
//                    );
//                    // Add
//                    if ($isToInsert) {
//                        array_push($arrAsteListFiltrato, $arrItem);
//                    }
//                }
//                $this->view->asteList = $arrAsteListFiltrato;
//            }

        } else {

            // Lista Comuni
            $comuniModel = new Comuni_Model();
            $comuniList = $comuniModel->getComuniList();
            $arrComuni = array();
            $arrComuniTribunale = array();
            // Lista Cap
            $capModel = new Cap_Model();
            $capList = $capModel->getCapList();
            $arrCap = array();
            if (is_array($comuniList) || is_object($comuniList)) {
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
            // Cap in base a COMUNI DISPONIBILI
            if (is_array($capList) || is_object($capList)) {
                if (sizeof($arrComuniList)>0) {
                    foreach($arrComuniList as $comune){
                        // Cap
                        foreach($capList as $cap){
                            $arrItem = array(
                                "codiceIstat"=>$cap["codiceIstat"],
                                "cap"=>$cap["cap"]
                            );
                            if ($functionsModel->ConvertCodiceIstat($comune["codice_istat"] ) == $functionsModel->ConvertCodiceIstat($cap["codiceIstat"] )) {
                                array_push($arrCap, $arrItem);
                            }
                        }
                    }
                }
            }
            $arrCapList = array();
            if (sizeof($arrCap)>0) {
                $tempArr3 = array_unique(array_column($arrCap, 'cap'));
                $arrCapList = array_intersect_key($arrCap, $tempArr3);
            }
            $this->view->capList = $arrCapList;
        }



        // View
        $this->view->render('aste/export', true, HEADER_MAIN);

    }








    // Check IdItem exists
    function CheckIdItemExists($idItem) {
        if (!isset($idItem)) {
            return false;
        }
        if ($idItem==null || $idItem=="") {
            return false;
        } else {
            if ($this->model->getDataFromId($idItem)!=NULL) {
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
    // Check Id immagine aggiuntiva Agenzia exists
    function CheckIdImgAgenziaExists($idItem,$idAsta,$idAgenzia) {
        if (!isset($idItem) || !isset($idAsta) || !isset($idAgenzia)) {
            return false;
        }
        if ($idItem==null || $idItem=="" || $idAgenzia==null || $idAgenzia==""
            || $idAsta==null || $idAsta=="") {
            return false;
        } else {
            $relAsteImgModel = new RelAsteImg_Model();
            if ($relAsteImgModel->getDataFromIdPerAgenzia($idItem, $idAsta, $idAgenzia)!=NULL) {
                return true;
            }
        }
        return false;
    }
    // Check Id immagine aggiuntiva Admin exists
    function CheckIdImgAdminExists($idItem,$idAsta) {
        if (!isset($idItem) || !isset($idAsta)) {
            return false;
        }
        if ($idItem==null || $idItem=="" || $idAsta==null || $idAsta=="") {
            return false;
        } else {
            $relAsteImgModel = new RelAsteImg_Model();
            if ($relAsteImgModel->getDataFromAdmin($idItem,$idAsta)!=NULL) {
                return true;
            }
        }
        return false;
    }
    
    

}


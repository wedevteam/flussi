<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */
// error_reporting(E_ALL | E_PARSE);
// Modelli
require 'Models/aste_model.php';
require 'Models/agency_model.php';
require 'Models/comuni_model.php';
require 'Models/cap_model.php';
require 'Models/dbcucine_model.php';
require 'Models/dbstrade_model.php';
require 'Models/imports_model.php';
require 'Models/importdetails_model.php';
require 'Models/exportdetails_model.php';
require 'Models/relasteagenzie_model.php';
require 'Models/relagenziepref_model.php';
require 'Models/relasteimg_model.php';

class Exports extends Controller {

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
        $this->view->mainMenu = Functions::setActiveMenu("exports");
        // Get Errors
        $this->view->error = Functions::getError($error);
        
        // Get Data
        $exportModel = new Exports_Model();
        $this->view->exportsList = $exportModel->getExportsList();
        $this->view->numItems = 0;
        if (is_array($this->view->exportsList) || is_object($this->view->exportsList)) {
            $this->view->numItems = sizeof($this->view->exportsList);
        }
        
        // View
        $this->view->render('exports/index', true, HEADER_MAIN);
    }

    // GET: Create
    public function create($error=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("exports");
        // Get Errors
        $this->view->error = Functions::getError($error);
        
        // Get data
        $agencyModel = new Agency_Model();
        $this->view->agencyList = $agencyModel->getUsersListByRole("agency");
        $comuniModel = new Comuni_Model();
        $this->view->comuniList = $comuniModel->getComuniList();
        $capModel = new Cap_Model();
        $arrCap = $capModel->getCapList();
        $arrCapList = array();
        if (sizeof($arrCap)>0) {
            $tempArr = array_unique(array_column($arrCap, 'cap'));
            $arrCapList = array_intersect_key($arrCap, $tempArr);
        }
        $this->view->capList = $arrCapList;
        
        // View
        $this->view->render('exports/create', true, HEADER_MAIN);
    }
    
    // GET: Details
    public function details($error=null, $message=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("exports");
        // Get Errors
        $this->view->error = Functions::getError($error);
        // Get Message
        $this->view->message = Functions::getMessages($message);
        
        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"])) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
        
        // Get data
        $agencyModel = new Agency_Model();
        $this->view->agencyList = $agencyModel->getUsersListByRole("agency");
        $asteModel = new Aste_Model();
        $this->view->asteList = $asteModel->getAsteList(Null,Null);
        $exDetModel = new ExportDetails_Model();
        $arrExpDetAll = $exDetModel->getExportDetList($_GET["iditem"]);
        $exportDetailsList = array();
        if (is_array($arrExpDetAll) || is_object($arrExpDetAll)) {
            foreach($arrExpDetAll as $detail) {
                // Nome Agenzia
                $nomeAgenzia = "";
                if (is_array($this->view->agencyList) || is_object($this->view->agencyList)) {
                    foreach($this->view->agencyList as $agency) {
                        if ($agency["id"]== $detail["idAgenzia"]) {
                            $nomeAgenzia = $agency["companyName"];
                        }
                    }
                }
                // Nome Asta
                $nomeAsta = "";
                if (is_array($this->view->asteList) || is_object($this->view->asteList)) {
                    foreach($this->view->asteList as $asta) {
                        if ($asta["id"]== $detail["idAsta"]) {
                            $nomeAsta = $asta["rge"]." ".$asta["lotto"]." - ".$asta["Comune"]." ";
                        }
                    }
                }
                // Set item
                $arrItem = array(
                    "id" => "id",
                    "idAgenzia" => $detail["idAgenzia"],
                    "idAsta" => $detail["idAsta"],
                    "nomeAgenzia" => $nomeAgenzia,
                    "nomeAsta" => $nomeAsta
                );
                // Add
                array_push($exportDetailsList, $arrItem);
                
            }
        }
        $this->view->detailsList = $exportDetailsList;
        
        // View
        $this->view->render('exports/details', true, HEADER_MAIN);
    }

    // GET: Execute Export
    public function executeExport($error=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("exports");
        // Get Errors
        $this->view->error = Functions::getError($error);

        // Checks
        if (!$this->CheckIdItemExists($_GET["iditem"]) || $_GET["iditem"]!=$_POST["idExport"]) {
            $this->index();
            return false;
        }
        // Get Data
        $this->view->data = $this->model->getDataFromId($_GET["iditem"]);
        // Dettaglio Esportazione
        $arrExportDet = array();
        $exportDetailsModel = new ExportDetails_Model();
        $resExDetails = $exportDetailsModel->getExportDetList($_GET["iditem"]);
        if (is_array($resExDetails) || is_object($resExDetails)) {
            foreach($resExDetails as $detail){
                $arrItem = array(
                    "id" => $detail["id"],
                    "idExport" => $detail["idExport"],
                    "idAgenzia" => $detail["idAgenzia"],
                    "idAsta" => $detail["idAsta"]
                );
                array_push($arrExportDet,$arrItem);
            }
        }
        if (sizeof($arrExportDet)==0) {
            // Error
            $this->index();
            return false;
        }

        // =============================== GET DATI IN MEMORIA
        // Dati Platform per Invio (FTP)
        $ftpHost = $this->view->platformData["ftpHost"];
        $ftpUser = $this->view->platformData["ftpUser"];
        $ftpPw = $this->view->platformData["ftpPw"];
        $adminPrefFlagPubb = $this->view->platformData["prefFlagPubblicita"];
        $adminPrefFlagPrezzo = $this->view->platformData["prefFlagPrezzo"];
        // Agenzie
        $agenzieModel = new Agency_Model();
        $arrAgencyListAll = $agenzieModel->getUsersListByRole("agency");
        $arrAgencyList = array();
        if (is_array($arrAgencyListAll) || is_object($arrAgencyListAll)) {
            foreach($arrAgencyListAll as $item){
                // Set values
                $arrItem = array(
                    'id' => $item["id"],
                    'firstName' => $item["firstName"],
                    'lastName' => $item["lastName"],
                    'companyName' => $item["companyName"],
                    'tipoContratto' => $item["tipoContratto"],
                    'email' => $item["email"],
                    'IdGestionale' => $item["IdGestionale"],
                    'IdAgenzia' => $item["IdAgenzia"],
                    'NomePubblicita' => $item["NomePubblicita"],
                    'DescrizioneAgenzia' => $item["DescrizioneAgenzia"],
                    'CodiceNazione' => $item["CodiceNazione"],
                    'CodiceComune' => $item["CodiceComune"],
                    'Indirizzo' => $item["Indirizzo"],
                    'Civico' => $item["Civico"],
                    'Cap' => $item["Cap"],
                    'Latitudine' => $item["Latitudine"],
                    'Longitudine' => $item["Longitudine"],
                    'Comune' => $item["Comune"],
                    'SiglaProvincia' => $item["SiglaProvincia"],
                    'IdComune' => $item["IdComune"],
                    'Telefono' => $item["Telefono"],
                    'Cellulare' => $item["Cellulare"],
                    'Fax' => $item["Fax"],
                    'URLLogo' => $item["URLLogo"],
                    'URLImmagine' => $item["URLImmagine"],
                    'CodicePortale' => $item["CodicePortale"],
                    'CodiceRelazione' => $item["CodiceRelazione"],
                    'DataInizio' => $item["DataInizio"],
                    'DataFine' => $item["DataFine"],
                    'NrAnnunci' => $item["NrAnnunci"],
                    'prefFlagPubblicita' => $item["prefFlagPubblicita"],
                    'prefFlagPrezzo' => $item["prefFlagPrezzo"],
                    'DataModifica' => $item["DataModifica"]
                );
                // Add
                array_push($arrAgencyList, $arrItem);
            }
        }
        if (sizeof($arrAgencyList)==0) {
            $this->details(ER_EXPORT_AGENZIE_NONPRESENTI);
            return false;
        }
        // Rel_AsteAgenzie
        $relAsteAgenzieModel = new RelAsteAgenzie_Model();
        $arrRelAsteAgList = $relAsteAgenzieModel->getRelAsteAgenzieList(Null, Null,Null);
        $functionsModel = new Functions();

        // Leggi tutte le aste
        $asteModel = new Aste_Model();
        $arrAsteListAll = $asteModel->getAsteList(Null, Null);
        $today = date("Y-m-d");
        $arrAsteList = array();
        if (is_array($arrAsteListAll) || is_object($arrAsteListAll)) {
            foreach ($arrAgencyList as $agency) {
                foreach($arrAsteListAll as $asta) {
                    if ($asta["status"]=="on" && $asta["dataAsta"]>$today) {
                        $trovato = false;

                        // TROVA DATI SPECIFICI
                        // -------------------------------------------->>> Set PREZZO e Valori IMMOBILE in base a Preferenze Admin o Agenzia
                        // Prezzo
                        if ($adminPrefFlagPrezzo=="BaseAsta") {
                            $Prezzo = $asta["importoBaseAsta"];
                        } else {
                            $Prezzo = $asta["importoOffertaMinima"];
                        }
                        // Testo
                        $Testo = $asta["Testo"];
                        $TestoBreve = $asta["TestoBreve"];
                        $immagine_URL = $asta["immagine_URL"];
                        $immagineDataModifica = $asta["immagine_DataModifica"];
                        // Trova Record dettaglio Rel_AsteAgenzie
                        if (is_array($arrRelAsteAgList) || is_object($arrRelAsteAgList)) {
                            foreach ($arrRelAsteAgList as $relAsteAg) {
                                if ($relAsteAg["idAgenzia"] == $agency["id"] && $relAsteAg["idAsta"] == $asta["id"]) {
                                    // Prezzo
                                    if ($relAsteAg["preferenzaPrezzo"]=="BaseAsta") {
                                        $Prezzo = $asta["importoBaseAsta"];
                                    } else {
                                        $Prezzo = $asta["importoOffertaMinima"];
                                    }
                                    // Testo
                                    $Testo = $relAsteAg["descrizione"];
                                    $TestoBreve = substr($Testo,0,555)."...";
                                    $immagine_URL = $relAsteAg["immagine_URL"];
                                    $immagineDataModifica = $relAsteAg["DataModifica"];
                                    // FlagPubb
                                    $flagPubb = $relAsteAg["flagPubblicita"];
                                    // Status
                                    $Status =  $relAsteAg["statusImportazione"];
                                }
                            }
                        }

                        // -------------------------------------------->>> VERIFICA SE ASTA E' ALL'INTERNO DEL DETTAGLIO DELL'ESPORTAZIONE
                        foreach($arrExportDet as $dettaglioExport){
                            if ($arrExportDet["idAsta"]==$asta["id"] && $arrExportDet["idAgenzia"]==$agency["id"]) {
                                $trovato = true;
                            }
                        }


                        // Set values
                        $arrItem = array(
                            'IDImmobile' => $asta["id"],
                            'idAgenzia' => $agency["id"],
                            'IDAgenzia' => $agency["IdAgenzia"],                // IdAgenzia
                            'Lingua' => $asta["Descrizioni_Lingua"],
                            'CodiceNazione' => $asta["CodiceNazione"],
                            'CodiceComune' => $asta["CodiceComune"],
                            'CodiceQuartiere' => $asta["CodiceQuartiere"],
                            'CodiceLocalita' => $asta["CodiceLocalita"],
                            'Strada' => $asta["Strada"],
                            'Indirizzo' => $asta["Indirizzo"],
                            'Civico' => $asta["Civico"],
                            'PubblicaCivico' => $asta["PubblicaCivico"],
                            'Cap' => $asta["Cap"],
                            'PubblicaIndirizzo' => $asta["PubblicaIndirizzo"],
                            'Latitudine' => $asta["Latitudine"],
                            'Longitudine' => $asta["Longitudine"],
                            'PubblicaMappa' => $asta["PubblicaMappa"],
                            'Contratto' => $asta["Contratto"],
                            'DurataContratto' => $asta["DurataContratto"],
                            'Categoria' => $asta["Categoria"],
                            'IDTipologia' => $asta["IDTipologia"],
                            'NrLocali' => $asta["NrLocali"],
                            'Prezzo' => $Prezzo,                                    // In base a Pref.Agenzia (se presenti) o Admin
                            'TrattativaRiservata' => $asta["TrattativaRiservata"],
                            'MQSuperficie' => $asta["MQSuperficie"],
                            'Riferimento' => $asta["id"]."-".$agency["id"],         // IdAsta+IdAgenzia
                            'TipoProprieta' => $asta["TipoProprieta"],
                            'Asta' => $asta["Asta"],
                            'Pregio' => $asta["Pregio"],
                            'SpeseMensili' => $asta["SpeseMensili"],
                            'ClasseCatastale' => $asta["ClasseCatastale"],
                            'RenditaCatastale' => $asta["RenditaCatastale"],
                            'URLPlanimetria' => $asta["URLPlanimetria"],
                            'URLVirtualTour' => $asta["URLVirtualTour"],
                            'Collaborazioni' => $asta["Collaborazioni"],
                            'DataInserimento' => $asta["DataInserimento"],
                            'DataModifica' => $asta["DataModifica"],                // QUI
                            'Descrizioni_Lingua' => $asta["Descrizioni_Lingua"],
                            'Titolo' => $asta["Titolo"],
                            'Testo' => $Testo,                                      // In base a Pref.Agenzia
                            'TestoBreve' => $TestoBreve,                            // In base a Pref.Agenzia
                            'StatoImmobile' => $asta["StatoImmobile"],
                            'Piano' => $asta["Piano"],
                            'PianoFuoriTerra' => $asta["PianoFuoriTerra"],
                            'PianiEdificio' => $asta["PianiEdificio"],
                            'NrCamereLetto' => $asta["NrCamereLetto"],
                            'NrAltreCamere' => $asta["NrAltreCamere"],
                            'NrBagni' => $asta["NrBagni"],
                            'Cucina' => $asta["Cucina"],
                            'NrTerrazzi' => $asta["NrTerrazzi"],
                            'NrBalconi' => $asta["NrBalconi"],
                            'Ascensore' => $asta["Ascensore"],
                            'NrAscensori' => $asta["NrAscensori"],
                            'BoxAuto' => $asta["BoxAuto"],
                            'BoxIncluso' => $asta["BoxIncluso"],
                            'NrBox' => $asta["NrBox"],
                            'NrPostiAuto' => $asta["NrPostiAuto"],
                            'Cantina' => $asta["Cantina"],
                            'Portineria' => $asta["Portineria"],
                            'GiardinoCondominiale' => $asta["GiardinoCondominiale"],
                            'GiardinoPrivato' => $asta["GiardinoPrivato"],
                            'AriaCondizionata' => $asta["AriaCondizionata"],
                            'Riscaldamento' => $asta["Riscaldamento"],
                            'TipoImpiantoRiscaldamento' => $asta["TipoImpiantoRiscaldamento"],
                            'TipoRiscaldamento' => $asta["TipoRiscaldamento"],
                            'SpeseRiscaldamento' => $asta["SpeseRiscaldamento"],
                            'Arredamento' => $asta["Arredamento"],
                            'StatoArredamento' => $asta["StatoArredamento"],
                            'AnnoCostruzione' => $asta["AnnoCostruzione"],
                            'TipoCostruzione' => $asta["TipoCostruzione"],
                            'StatoCostruzione' => $asta["StatoCostruzione"],
                            'Piscina' => $asta["Piscina"],
                            'Tennis' => $asta["Tennis"],
                            'VideoCitofono' => $asta["VideoCitofono"],
                            'Allarme' => $asta["Allarme"],
                            'Idromassaggio' => $asta["Idromassaggio"],
                            'Caminetto' => $asta["Caminetto"],
                            'FibraOttica' => $asta["FibraOttica"],
                            'ClasseEnergetica' => $asta["ClasseEnergetica"],
                            'IndicePrestazioneEnergetica' => $asta["IndicePrestazioneEnergetica"],
                            'IDImmagine' => $asta["IDImmagine"],
                            'immagine_URL' => $immagine_URL,                            // In base a Pref. Agenzia
                            'immagine_DataModifica' => $immagineDataModifica,           // In base a Pref. Agenzia
                            'immagine_Posizione' => $asta["immagine_Posizione"],
                            'immagine_TipoFoto' => $asta["immagine_TipoFoto"],
                            'immagine_Titolo' => $asta["immagine_Titolo"]
                        );
                        // Add
                        if ($trovato) {
                            array_push($arrAsteList, $arrItem);
                        }
                    }

                }
            }
        }

        if (sizeof($arrAsteList)==0) {
            $this->details(ER_EXPORT_IMM_NONPRESENTI);
            return false;
        }

        // Leggi tutte le immagini
        $relImgModel = new RelAsteImg_Model();
        $resImg = $relImgModel->getRelAsteImgAllList();
        $arrImg = array();
        if (is_array($resImg) || is_object($resImg)) {
            foreach ($resImg as $img){
                $arrItem = array(
                    'id' => $img["id"],
                    'idAsta' => $img["idAsta"],
                    'idAgenzia' => $img["idAgenzia"],
                    'fonte' => $img["fonte"],
                    'immagine_URL' => $img["immagine_URL"],
                    'IDImmagine' => $img["IDImmagine"],
                    'immagine_Titolo' => $img["immagine_Titolo"],
                    'immagine_TipoFoto' => $img["immagine_TipoFoto"],
                    'immagine_Posizione' => $img["immagine_Posizione"],
                    'DataModifica' => $img["DataModifica"],
                    'DataModifica_d' => $img["DataModifica_d"]
                );
                // Add
                array_push($arrImg,$arrItem);
            }
        }


        // PRENDI DATI DI TUTTE LE ASTE PRESENTI NELL'EXPORT

        // PREDISPONI ESPORTAZINE VS. GETRIX
        // =============================== CREA FILE XML
        // Agenzie
        $this->createXMLfile_Agenzie($arrAgencyList,"20a50f55e1a79d0616fa21a80c262928");
        // Immobili
        $this->createXMLfile_Immobili($arrAsteList,"20a50f55e1a79d0616fa21a80c262928",$arrImg);




        // =============================== INVIO FILE XML A GETRIX VIA FTP
        //$ftp_server = "feed.immobiliarefull.com";
        $ftp_conn = ftp_connect($ftpHost) or die("Errore di connessione con il Server Immobiliare.it");
        $login = ftp_login($ftp_conn, $ftpUser, $ftpPw);

        // DEV ==========================================
        $file_xml_ag = 'xml/agenzieTEST.xml';
        $file_xml_imm = 'xml/immobiliTEST.xml';
        // PROD  ==========================================
        // $file_xml_ag = 'xml/agenzie.xml';
        // $file_xml_imm = 'xml/immobili.xml';


        // INVIO
        if (ftp_put($ftp_conn, '/agenzieTEST.xml', $file_xml_ag, FTP_ASCII) && ftp_put($ftp_conn, '/immobiliTEST.xml', $file_xml_imm, FTP_ASCII)) {
            // INVIO OK: Update Status Esportazione

            // close connection
            ftp_close($ftp_conn);

            // ---------------------->>>>>>>>>>>>>>>>>> UPDATE DATI
            // UPDATE Esportazione come ESPORTATA
            $data = array(
                ':exportDate' => date("Y-m-d H:i:s"),
                ':status' => "on"
            );
            $exportModel = new Exports_Model();
            $exportModel->updateData($data);

            // Aggiorno Rel_AsteAgenzie con Status
            foreach ($arrAsteList as $asta2) {
                // Update Rel_AsteAgenzie
                if (is_array($arrRelAsteAgList) || is_object($arrRelAsteAgList)) {
                    foreach ($arrRelAsteAgList as $rel) {
                        if ($rel["idAgenzia"] == $asta2["idAgenzia"] && $rel["idAsta"] == $asta2["IDImmobile"]) {
                            $dataImport = date("Y-m-d H:i:s");
                            $data = array(
                                ':statusImportazione' => "importato",
                                ':dataUltimaEsportazione_d' => $dataImport,
                                ':dataUltimaEsportazione' => substr($dataImport, 0, 10) . 'T' . substr($dataImport, 11, 19)
                            );
                            $where = " id=:id ";
                            $parameters = array();
                            $parameters[":id"] = $rel["id"];
                            $relModel = new RelAsteAgenzie_Model();
                            $relModel->updateData($data, $parameters, $where);
                        }
                    }
                }
            }
            // ---------------------->>>>>>>>>>>>>>>>>> END UPDATE DATI

            // View
            $this->details(null,EXPORT_SUCCESS);

        } else {
            $this->details(ER_EXPORT_IMM_NONPRESENTI);
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
            if ($this->model->getDataFromId($idItem)!=NULL) {
                return true;
            }
        }
        return false;
    }
    
    // POST: Export vs Getrix
    public function resultExport($error=null) {
        // =============================== GET DATI IN MEMORIA
        // Dati Platform per Invio (FTP)
        $ftpHost = $this->view->platformData["ftpHost"];
        $ftpUser = $this->view->platformData["ftpUser"];
        $ftpPw = $this->view->platformData["ftpPw"];
        $adminPrefFlagPubb = $this->view->platformData["prefFlagPubblicita"];
        $adminPrefFlagPrezzo = $this->view->platformData["prefFlagPrezzo"];
        // Agenzie
        $agenzieModel = new Agency_Model();
        $arrAgencyListAll = $agenzieModel->getUsersListByRole("agency");
        $arrAgencyList = array();
        if (is_array($arrAgencyListAll) || is_object($arrAgencyListAll)) {
            foreach($arrAgencyListAll as $item){
                $trovato = true;

                // STATUS VALIDO
                if ($item["status"]!='on') {
                    $trovato = false;
                }
                // Filtro AGENZIE (da Export) ==>> SOLO AGENZIE Oggetto di Esportazione
//                if (isset($_POST["idAgenzie"]) && $_POST["idAgenzie"]!=NULL) {
//                    $trovato = false;
//                    foreach ($_POST["idAgenzie"] as $filtroAg) {
//                        if ($filtroAg==$item["id"]) {
//                            $trovato = true;
//                        }
//                    }
//                }
                // INSERISCI TUTTE LE AGENZIE, ma identifica quelle che provengono dal FILTRO
                $isAgenziaFiltrata = false;
                if (isset($_POST["idAgenzie"]) && $_POST["idAgenzie"]!=NULL) {
                    foreach ($_POST["idAgenzie"] as $filtroAg) {
                        if ($filtroAg==$item["id"]) {
                            $isAgenziaFiltrata = true;
                        }
                    }
                }


                // Set values
                $arrItem = array(
                    'id' => $item["id"],
                    'firstName' => $item["firstName"],
                    'lastName' => $item["lastName"],
                    'companyName' => $item["companyName"],
                    'tipoContratto' => $item["tipoContratto"],
                    'email' => $item["email"],
                    'IdGestionale' => $item["IdGestionale"],
                    'IdAgenzia' => $item["IdAgenzia"],
                    'NomePubblicita' => $item["NomePubblicita"],
                    'DescrizioneAgenzia' => $item["DescrizioneAgenzia"],
                    'CodiceNazione' => $item["CodiceNazione"],
                    'CodiceComune' => $item["CodiceComune"],
                    'Indirizzo' => $item["Indirizzo"],
                    'Civico' => $item["Civico"],
                    'Cap' => $item["Cap"],
                    'Latitudine' => $item["Latitudine"],
                    'Longitudine' => $item["Longitudine"],
                    'Comune' => $item["Comune"],
                    'SiglaProvincia' => $item["SiglaProvincia"],
                    'IdComune' => $item["IdComune"],
                    'Telefono' => $item["Telefono"],
                    'Cellulare' => $item["Cellulare"],
                    'Fax' => $item["Fax"],
                    'URLLogo' => $item["URLLogo"],
                    'URLImmagine' => $item["URLImmagine"],
                    'CodicePortale' => $item["CodicePortale"],
                    'CodiceRelazione' => $item["CodiceRelazione"],
                    'DataInizio' => $item["DataInizio"],
                    'DataFine' => $item["DataFine"],
                    'NrAnnunci' => $item["NrAnnunci"],
                    'prefFlagPubblicita' => $item["prefFlagPubblicita"],
                    'prefFlagPrezzo' => $item["prefFlagPrezzo"],
                    'DataModifica' => $item["DataModifica"],
                    'isAgenziaFiltrata' => $isAgenziaFiltrata
                );
                // Add
                if ($trovato) {
                    array_push($arrAgencyList, $arrItem);
                }
            }
        }
        if (sizeof($arrAgencyList)==0) {
            $this->create(ER_EXPORT_AGENZIE_NONPRESENTI);
            return false;
        }
        // Rel_AsteAgenzie
        $relAsteAgenzieModel = new RelAsteAgenzie_Model();
        $arrRelAsteAgList = $relAsteAgenzieModel->getRelAsteAgenzieList(Null, Null,Null);
        // Rel_AgenziePref
        $relAgPrefModel = new RelAgenziePref_Model();
        $arrRelAgPrefList = $relAgPrefModel->getRelAgenziePrefList(Null, Null, Null);
        $functionsModel = new Functions();

        // Export Details
        $exportModel = new Exports_Model();
        $resExports = $exportModel->getExportsListByStatus("on");
        $arrExportDet = array();
        if (is_array($resExports) || is_object($resExports)) {
            foreach($resExports as $export){
                $exportDetailsModel = new ExportDetails_Model();
                $resExDetails = $exportDetailsModel->getExportDetList($export["id"]);
                if (is_array($resExDetails) || is_object($resExDetails)) {
                    foreach($resExDetails as $detail){
                        $arrItem = array(
                            "id" => $detail["id"],
                            "idExport" => $detail["idExport"],
                            "idAgenzia" => $detail["idAgenzia"],
                            "idAsta" => $detail["idAsta"]
                        );
                        array_push($arrExportDet,$arrItem);
                    }
                }
            }
        }


        // Aste
        $asteModel = new Aste_Model();
        $arrAsteListAll = $asteModel->getAsteList(Null, Null);
        $today = date("Y-m-d");
        $arrAsteList = array();
        if (is_array($arrAsteListAll) || is_object($arrAsteListAll)) {
            foreach ($arrAgencyList as $agency) { 
                foreach($arrAsteListAll as $asta) {
                    if ($asta["status"]=="on" && $asta["dataAsta"]>$today) {
                        $trovato = false;

                        // TROVA DATI SPECIFICI
                        // -------------------------------------------->>> Set PREZZO e Valori IMMOBILE in base a Preferenze Admin o Agenzia
                        // Prezzo
                        if ($adminPrefFlagPrezzo=="BaseAsta") {
                            $Prezzo = $asta["importoBaseAsta"];
                        } else {
                            $Prezzo = $asta["importoOffertaMinima"];
                        }
                        // Testo
                        $Testo = $asta["Testo"];
                        $TestoBreve = $asta["TestoBreve"];
                        $immagine_URL = $asta["immagine_URL"];
                        $immagineDataModifica = $asta["immagine_DataModifica"];
                        // Trova Record dettaglio Rel_AsteAgenzie
                        if (is_array($arrRelAsteAgList) || is_object($arrRelAsteAgList)) {
                            foreach ($arrRelAsteAgList as $relAsteAg) {
                                if ($relAsteAg["idAgenzia"] == $agency["id"] && $relAsteAg["idAsta"] == $asta["id"]) {
                                    // Prezzo
                                    if ($relAsteAg["preferenzaPrezzo"]=="BaseAsta") {
                                        $Prezzo = $asta["importoBaseAsta"];
                                    } else {
                                        $Prezzo = $asta["importoOffertaMinima"];
                                    }
                                    // Testo
                                    $Testo = $relAsteAg["descrizione"];
                                    $TestoBreve = substr($Testo,0,555)."...";
                                    $immagine_URL = $relAsteAg["immagine_URL"];
                                    $immagineDataModifica = $relAsteAg["DataModifica"];
                                    // FlagPubb
                                    $flagPubb = $relAsteAg["flagPubblicita"];
                                    // Status
                                    $Status =  $relAsteAg["statusImportazione"];
                                }
                            }
                        }



                        // -------------------------------------------->>> VERIFICA SE ASTA VA INSERITA NELL'ESPORTAZIONE
                        // 1) VERIFICA SE L'ASTA E' STATA ESPORTATA PRECEDENTEMENTE
                        if (sizeof($arrExportDet)>0) {
                            foreach ($arrExportDet as $export ) {
                                if ($export["idAgenzia"]==$agency["id"] && $export["idAsta"]==$asta["id"]) {
                                    // E' stata esportata precedentemente
                                    $trovato = true;
                                }
                            }
                        }
//                        echo '<br>idAgenzia: '.$agency["id"];
////                        echo '<br>idAsta: '.$asta["id"];
////                        echo '<br>trovato: '.$trovato;

                        // 2) VERIFICA SE L'ASTA RIENTRA tra le Preferenze di Esportazione dell'AGENZIA
                        //    SOLO PER LE AGENZIE COINVOLTE nell'esportazione (isAgenziaFiltrata = true)
                        if ($agency["isAgenziaFiltrata"] && !$trovato) {
                            $trovatoComune = false;
                            $trovatoCap = false;
                            $trovatoProv = false;
                            $trovatoComuneT = false;
                            // Leggo preferenze Esportazione Agenzia
                            $relAgPrefViewModel = new RelAgenziePref_Model();
                            $relAgPrefViewListComuni = $relAgPrefViewModel->getRelAgenziePrefList($agency["id"], "comune",NULL);
                            $relAgPrefViewListProv = $relAgPrefViewModel->getRelAgenziePrefList($agency["id"], "provincia",NULL);
                            $relAgPrefViewListCap = $relAgPrefViewModel->getRelAgenziePrefList($agency["id"], "cap",NULL);
                            $relAgPrefViewListComuniT = $relAgPrefViewModel->getRelAgenziePrefList($agency["id"], "comuneTribunale",NULL);
                            // Comuni
                            if (is_array($relAgPrefViewListComuni) || is_object($relAgPrefViewListComuni)) {
                                foreach($relAgPrefViewListComuni as $pref){
                                    $prefViewComune = $functionsModel->ConvertCodiceIstat($pref["idOggetto"]);
                                    if ($prefViewComune == $asta["CodiceComune"]) {
                                        $trovatoComune = true;
                                    }
                                }
                            } else {
                                $trovatoComune = true;
                            }
                            // Comune Tribunale
                            if (is_array($relAgPrefViewListComuniT) || is_object($relAgPrefViewListComuniT)) {
                                foreach($relAgPrefViewListComuniT as $pref){
                                    $prefViewComune = $functionsModel->ConvertCodiceIstat($pref["idOggetto"]);
                                    if ($prefViewComune == $asta["codiceComuneTribunale"]) {
                                        $trovatoComuneT = true;
                                    }
                                }
                            } else {
                                $trovatoComuneT = true;
                            }
                            // Prov
                            if (is_array($relAgPrefViewListProv) || is_object($relAgPrefViewListProv)) {
                                foreach($relAgPrefViewListProv as $pref){
                                    if ($pref["idOggetto"] == $asta["Provincia"]) {
                                        $trovatoProv = true;
                                    }
                                }
                            } else {
                                $trovatoProv = true;
                            }
                            // Cap
                            if (is_array($relAgPrefViewListCap) || is_object($relAgPrefViewListCap)) {
                                foreach($relAgPrefViewListCap as $pref){
                                    if ($pref["idOggetto"] == $asta["Cap"]) {
                                        $trovatoCap = true;
                                    }
                                }
                            } else {
                                $trovatoCap = true;
                            }

                            // Filtro finale
                            if ($trovatoComune && $trovatoCap && $trovatoComuneT && $trovatoProv) {
                                $trovato = true;
                            }
                        }




                        // -------------------------------------------->>> VERIFICA SE ASTA RIENTRA NEI PARAMETRI FILTRATI NELL'ESPORTAZIONE
                        // OffertaMinima DA
                        if ($trovato) {
                            if (isset($_POST["offertaMinDa"]) && $_POST["offertaMinDa"]!=null && $_POST["offertaMinDa"]!="") {
                                $trovato = false;
                                if ($asta["importoOffertaMinima"]<$_POST["offertaMinDa"]) {
                                    $trovato = true;
                                }
                            }
                        }
                        // OffertaMinima A
                        if ($trovato) {
                            if (isset($_POST["offertaMinA"]) && $_POST["offertaMinA"]!=null && $_POST["offertaMinA"]!="") {
                                $trovato = false;
                                if ($asta["importoOffertaMinima"]>$_POST["offertaMinA"]) {
                                    $trovato = true;
                                }
                            }
                        }
                        // DATA ASTA DA
                        if ($trovato) {
                            if (isset($_POST["dataAstaDa"]) && $_POST["dataAstaDa"]!=null && $_POST["dataAstaDa"]!="") {
                                $trovato = false;
                                if ($asta["dataAsta"]<$functionsModel->transformDateFormat(1,$_POST["dataAstaDa"])) {
                                    $trovato = false;
                                }
                            }
                        }
                        // DATA ASTA A
                        if ($trovato) {
                            if (isset($_POST["dataAstaA"]) && $_POST["dataAstaA"]!=null && $_POST["dataAstaA"]!="") {
                                $trovato = false;
                                if ($asta["dataAsta"]>$functionsModel->transformDateFormat(1,$_POST["dataAstaA"])) {
                                    $trovato = false;
                                }
                            }
                        }
                        // CATEGORIA
                        if ($trovato) {
                            if (isset($_POST["idCategorie"]) && $_POST["idCategorie"]!=null && $_POST["idCategorie"]!="") {
                                $trovato = false;
                                foreach($_POST["idCategorie"] as $cat){
                                    if ($asta["Categoria"]==$cat) {
                                        $trovato = true;
                                    }
                                }
                            }
                        }
                        // SUPERFICIE DA
                        if ($trovato) {
                            if (isset($_POST["superficieDa"]) && $_POST["superficieDa"]!=null && $_POST["superficieDa"]!="") {
                                $trovato = false;
                                if ($asta["MQSuperficie"]<$_POST["superficieDa"]) {
                                    $trovato = false;
                                }
                            }
                        }
                        // SUPERFICIE A
                        if ($trovato) {
                            if (isset($_POST["superficieA"]) && $_POST["superficieA"]!=null && $_POST["superficieA"]!="") {
                                $trovato = false;
                                if ($asta["MQSuperficie"]>$_POST["superficieA"]) {
                                    $trovato = false;
                                }
                            }
                        }
                        // NUM.LOCALI DA
                        if ($trovato) {
                            if (isset($_POST["NrLocaliDa"]) && $_POST["NrLocaliDa"]!=null && $_POST["NrLocaliDa"]!="") {
                                $trovato = false;
                                if ($asta["NrLocali"]<$_POST["NrLocaliDa"]) {
                                    $trovato = false;
                                }
                            }
                        }
                        // NUM.LOCALI DA
                        if ($trovato) {
                            if (isset($_POST["NrLocaliA"]) && $_POST["NrLocaliA"]!=null && $_POST["NrLocaliA"]!="") {
                                $trovato = false;
                                if ($asta["NrLocali"]>$_POST["NrLocaliA"]) {
                                    $trovato = false;
                                }
                            }
                        }
                        // FLAG PUBBLICITA
                        if ($trovato) {
                            if (isset($_POST["flagPubblicita"]) && $_POST["flagPubblicita"]!=null && $_POST["flagPubblicita"]!="") {
                                $trovato = false;
                                if ($flagPubb!=$_POST["flagPubblicita"]) {
                                    $trovato = false;
                                }
                            }
                        }
                        // STATUS EXPORT
                        if ($trovato) {
                            if (isset($_POST["statusExport"]) && $_POST["statusExport"]!=null && $_POST["statusExport"]!="") {
                                $trovato = false;
                                if ($Status!=$_POST["statusExport"]) {
                                    $trovato = false;
                                }
                            }
                        }

                        // -------------------------------------------->>> Preferenze Agenzia su EXPORT
                        // Agenzia CIOTTA
//                        if ($agency["id"]==4) {
//                            $trovato = false;
//                            if ($asta["Provincia"]=="MI" ) {
//                                $trovato = true;
//                            }
//                        }
//                        // Agenzia LUFFARELLI
//                        if ($agency["id"]==3) {
//                            $trovato = false;
//                            if ($asta["codiceComuneTribunale"]=="058111" || $asta["codiceComuneTribunale"]=="058032" ) {
//                                $trovato = true;
//                            }
//                        }


                        // Set values
                        $arrItem = array(
                            'IDImmobile' => $asta["id"],  
                            'idAgenzia' => $agency["id"],  
                            'IDAgenzia' => $agency["IdAgenzia"],                // IdAgenzia
                            'Lingua' => $asta["Descrizioni_Lingua"],
                            'CodiceNazione' => $asta["CodiceNazione"],
                            'CodiceComune' => $asta["CodiceComune"],
                            'CodiceQuartiere' => $asta["CodiceQuartiere"],
                            'CodiceLocalita' => $asta["CodiceLocalita"],
                            'Strada' => $asta["Strada"],
                            'Indirizzo' => $asta["Indirizzo"],
                            'Civico' => $asta["Civico"],
                            'PubblicaCivico' => $asta["PubblicaCivico"],
                            'Cap' => $asta["Cap"],
                            'PubblicaIndirizzo' => $asta["PubblicaIndirizzo"],
                            'Latitudine' => $asta["Latitudine"],
                            'Longitudine' => $asta["Longitudine"],
                            'PubblicaMappa' => $asta["PubblicaMappa"],
                            'Contratto' => $asta["Contratto"],
                            'DurataContratto' => $asta["DurataContratto"],
                            'Categoria' => $asta["Categoria"],
                            'IDTipologia' => $asta["IDTipologia"],
                            'NrLocali' => $asta["NrLocali"],
                            'Prezzo' => $Prezzo,                                    // In base a Pref.Agenzia (se presenti) o Admin
                            'TrattativaRiservata' => $asta["TrattativaRiservata"],
                            'MQSuperficie' => $asta["MQSuperficie"],
                            'Riferimento' => $asta["id"]."-".$agency["id"],         // IdAsta+IdAgenzia
                            'TipoProprieta' => $asta["TipoProprieta"],
                            'Asta' => $asta["Asta"],
                            'Pregio' => $asta["Pregio"],
                            'SpeseMensili' => $asta["SpeseMensili"],
                            'ClasseCatastale' => $asta["ClasseCatastale"],
                            'RenditaCatastale' => $asta["RenditaCatastale"],
                            'URLPlanimetria' => $asta["URLPlanimetria"],
                            'URLVirtualTour' => $asta["URLVirtualTour"],
                            'Collaborazioni' => $asta["Collaborazioni"],
                            'DataInserimento' => $asta["DataInserimento"],
                            'DataModifica' => $asta["DataModifica"],                // QUI
                            'Descrizioni_Lingua' => $asta["Descrizioni_Lingua"],
                            'Titolo' => $asta["Titolo"],
                            'Testo' => $Testo,                                      // In base a Pref.Agenzia
                            'TestoBreve' => $TestoBreve,                            // In base a Pref.Agenzia
                            'StatoImmobile' => $asta["StatoImmobile"],
                            'Piano' => $asta["Piano"],
                            'PianoFuoriTerra' => $asta["PianoFuoriTerra"],
                            'PianiEdificio' => $asta["PianiEdificio"],
                            'NrCamereLetto' => $asta["NrCamereLetto"],
                            'NrAltreCamere' => $asta["NrAltreCamere"],
                            'NrBagni' => $asta["NrBagni"],
                            'Cucina' => $asta["Cucina"],
                            'NrTerrazzi' => $asta["NrTerrazzi"],
                            'NrBalconi' => $asta["NrBalconi"],
                            'Ascensore' => $asta["Ascensore"],
                            'NrAscensori' => $asta["NrAscensori"],
                            'BoxAuto' => $asta["BoxAuto"],
                            'BoxIncluso' => $asta["BoxIncluso"],
                            'NrBox' => $asta["NrBox"],
                            'NrPostiAuto' => $asta["NrPostiAuto"],
                            'Cantina' => $asta["Cantina"],
                            'Portineria' => $asta["Portineria"],
                            'GiardinoCondominiale' => $asta["GiardinoCondominiale"],
                            'GiardinoPrivato' => $asta["GiardinoPrivato"],
                            'AriaCondizionata' => $asta["AriaCondizionata"],
                            'Riscaldamento' => $asta["Riscaldamento"],
                            'TipoImpiantoRiscaldamento' => $asta["TipoImpiantoRiscaldamento"],
                            'TipoRiscaldamento' => $asta["TipoRiscaldamento"],
                            'SpeseRiscaldamento' => $asta["SpeseRiscaldamento"],
                            'Arredamento' => $asta["Arredamento"],
                            'StatoArredamento' => $asta["StatoArredamento"],
                            'AnnoCostruzione' => $asta["AnnoCostruzione"],
                            'TipoCostruzione' => $asta["TipoCostruzione"],
                            'StatoCostruzione' => $asta["StatoCostruzione"],
                            'Piscina' => $asta["Piscina"],
                            'Tennis' => $asta["Tennis"],
                            'VideoCitofono' => $asta["VideoCitofono"],
                            'Allarme' => $asta["Allarme"],
                            'Idromassaggio' => $asta["Idromassaggio"],
                            'Caminetto' => $asta["Caminetto"],
                            'FibraOttica' => $asta["FibraOttica"],
                            'ClasseEnergetica' => $asta["ClasseEnergetica"],
                            'IndicePrestazioneEnergetica' => $asta["IndicePrestazioneEnergetica"],
                            'IDImmagine' => $asta["IDImmagine"],                    
                            'immagine_URL' => $immagine_URL,                            // In base a Pref. Agenzia
                            'immagine_DataModifica' => $immagineDataModifica,           // In base a Pref. Agenzia
                            'immagine_Posizione' => $asta["immagine_Posizione"],
                            'immagine_TipoFoto' => $asta["immagine_TipoFoto"],
                            'immagine_Titolo' => $asta["immagine_Titolo"]
                        );
                        // Add
                        if ($trovato) {
                            array_push($arrAsteList, $arrItem);
                        }
                    }
                    
                }
            }
        }
//        echo '<br>Q';
//        echo '<br>';
//        print_r($arrAsteList);


        if (sizeof($arrAsteList)==0) {
            $this->create(ER_EXPORT_IMM_NONPRESENTI);
            return false;
        }

        // Leggi tutte le immagini
        $relImgModel = new RelAsteImg_Model();
        $resImg = $relImgModel->getRelAsteImgAllList();
        $arrImg = array();
        if (is_array($resImg) || is_object($resImg)) {
            foreach ($resImg as $img){
                $arrItem = array(
                    'id' => $img["id"],
                    'idAsta' => $img["idAsta"],
                    'idAgenzia' => $img["idAgenzia"],
                    'fonte' => $img["fonte"],
                    'immagine_URL' => $img["immagine_URL"],
                    'IDImmagine' => $img["IDImmagine"],
                    'immagine_Titolo' => $img["immagine_Titolo"],
                    'immagine_TipoFoto' => $img["immagine_TipoFoto"],
                    'immagine_Posizione' => $img["immagine_Posizione"],
                    'DataModifica' => $img["DataModifica"],
                    'DataModifica_d' => $img["DataModifica_d"]
                );
                // Add
                array_push($arrImg,$arrItem);
            }
        }



        // CREA Esportazione
        $data = array(
            ':createdAt' => date("Y-m-d H:i:s"),
            ':status' => "off",
            ':numAgenzie' => sizeof($arrAgencyList),
            ':numAste' => sizeof($arrAsteList),
            ':exportDate' => date('Y-m-d H:i:s')
        );
        $exportModel = new Exports_Model();
        $idExport = $exportModel->create($data);
        // Creo Dettagli Esportazione e Aggiorno Rel_AsteAgenzie
        if ($idExport>0) {
            foreach($arrAsteList as $asta2) {
                // INSERT dettaglio Export
                $data2 = array(
                    ':idExport' => $idExport,
                    ':idAgenzia' => $asta2["idAgenzia"],
                    ':idAsta' => $asta2["IDImmobile"],
                    ':createdAt' => date('Y-m-d H:i:s')
                );
                $exportDetModel = new ExportDetails_Model();
                $idExpDetail = $exportDetModel->create($data2);
            }
        }

        // View
        $this->index();
        return false;


        // ======================================================================

        // =============================== CREA FILE XML
        // Agenzie
        $this->createXMLfile_Agenzie($arrAgencyList,"20a50f55e1a79d0616fa21a80c262928");
        // Immobili
        $this->createXMLfile_Immobili($arrAsteList,"20a50f55e1a79d0616fa21a80c262928",$arrImg);
        
        
        
        
        // =============================== INVIO FILE XML A GETRIX VIA FTP
        //$ftp_server = "feed.immobiliarefull.com";
        $ftp_conn = ftp_connect($ftpHost) or die("Errore di connessione con il Server Immobiliare.it");
        $login = ftp_login($ftp_conn, $ftpUser, $ftpPw);

        // DEV ==========================================
        $file_xml_ag = 'xml/agenzieTEST.xml';
        $file_xml_imm = 'xml/immobiliTEST.xml';
        // PROD  ==========================================
        // $file_xml_ag = 'xml/agenzie.xml';
        // $file_xml_imm = 'xml/immobili.xml';


        // INVIO
        if (ftp_put($ftp_conn, '/agenzieTEST.xml', $file_xml_ag, FTP_ASCII) && ftp_put($ftp_conn, '/immobiliTEST.xml', $file_xml_imm, FTP_ASCII)) {
            // INVIO OK: Update Status Esportazione
            
            // close connection
            ftp_close($ftp_conn);
            
            // ---------------------->>>>>>>>>>>>>>>>>> UPDATE DATI
            // INSERT Esportazione
            $data = array(
                ':createdAt' => date("Y-m-d H:i:s"),
                ':status' => "on",
                ':numAgenzie' => sizeof($arrAgencyList),
                ':numAste' => sizeof($arrAsteList)
            );
            $exportModel = new Exports_Model();
            $idExport = $exportModel->create($data);
            // Creo Dettagli Esportazione e Aggiorno Rel_AsteAgenzie
            if ($idExport>0) {
                foreach($arrAsteList as $asta2) {
                    // INSERT dettaglio Export
                    $data2 = array(
                        ':idExport' => $idExport,
                        ':idAgenzia' => $asta2["idAgenzia"],
                        ':idAsta' => $asta2["IDImmobile"],
                        ':createdAt' => date('Y-m-d H:i:s')
                    );
                    $exportDetModel = new ExportDetails_Model();
                    $idExpDetail = $exportDetModel->create($data2);

                    // Update Rel_AsteAgenzie
                    if (is_array($arrRelAsteAgList) || is_object($arrRelAsteAgList)) {
                        foreach($arrRelAsteAgList as $rel) {
                            if ($rel["idAgenzia"]==$asta2["idAgenzia"] && $rel["idAsta"]==$asta2["IDImmobile"]) {
                                $dataImport = date("Y-m-d H:i:s");
                                $data = array(
                                    ':statusImportazione' => "importato",
                                    ':dataUltimaEsportazione_d' => $dataImport,
                                    ':dataUltimaEsportazione' => substr($dataImport,0,10).'T'.substr($dataImport,11,19)
                                );
                                $where = " id=:id ";
                                $parameters = array();
                                $parameters[":id"] = $rel["id"];
                                $relModel = new RelAsteAgenzie_Model();
                                $relModel->updateData($data, $parameters, $where);
                            }
                        }
                    }
                }
            }
            // ---------------------->>>>>>>>>>>>>>>>>> END UPDATE DATI
           
            
            
            // ERRORE nell'invio del File
            $this->index();
            return false;

        } else {
            // ERRORE nell'invio del File
            $this->create(ER_EXPORT_INVALID);
            return false;
        }
        
    }

    
    
    
    // CREA FILE XML:: AGENZIE
    function createXMLfile_Agenzie($arrAgencyList,$IDGestionale){ 
        // Sets
        // $filePath_Agenzia 	= 'xml/agenzie.xml';
        $filePath_Agenzia 	= 'xml/agenzieTEST.xml';

        $dom_Agenzia     	= new DOMDocument('1.0', 'utf-8'); 
        $root_Agenzia      	= $dom_Agenzia->createElement('agenzie'); 
        
        $root_Agenzia = $dom_Agenzia->createElementNS('http://feed.immobiliarefull.com', 'Feed');
        $dom_Agenzia->appendChild($root_Agenzia);
        $root_Agenzia->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root_Agenzia->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'schemaLocation', 'http://feed.immobiliarefull.com/pms/xsd/agenzie_1_0_0.xsd');
        $root_Agenzia->setAttribute('IDGestionale', $IDGestionale);

        
        foreach($arrAgencyList as $agency){
            //Nodo Agenzia
            $Agenzia = $dom_Agenzia->createElement('Agenzia');
            $Agenzia->setAttribute('IDAgenzia', $agency['IdAgenzia']);

            $NomePubblicita = $dom_Agenzia->createElement('NomePubblicita', $agency['NomePubblicita']); 
            $Agenzia->appendChild($NomePubblicita);
            $CodiceNazione = $dom_Agenzia->createElement('CodiceNazione', $agency['CodiceNazione']); 
            $Agenzia->appendChild($CodiceNazione);
            $CodiceComune = $dom_Agenzia->createElement('CodiceComune', $agency['CodiceComune']); 
            $Agenzia->appendChild($CodiceComune);
            $Telefono = $dom_Agenzia->createElement('Telefono', $agency['Telefono']); 
            $Agenzia->appendChild($Telefono);
            if ($agency['Cellulare']!='') {
                $Cellulare = $dom_Agenzia->createElement('Cellulare', $agency['Cellulare']); 
                $Agenzia->appendChild($Cellulare);
            }
            if ($agency['Fax']!='') {
                $Fax = $dom_Agenzia->createElement('Fax', $agency['Fax']); 
                $Agenzia->appendChild($Fax);
            }
            $Email = $dom_Agenzia->createElement('Email', $agency['email']); 
            $Agenzia->appendChild($Email);
            if ($agency['URLLogo']!='') {
                $URLLogo = $dom_Agenzia->createElement('URLLogo', $agency['URLLogo']); 
                $Agenzia->appendChild($URLLogo);
            }
            $DataModifica = $dom_Agenzia->createElement('DataModifica', $agency['DataModifica']); 
            $Agenzia->appendChild($DataModifica);
            if ($agency['companyName']!='') {
                $RagioneSociale = $dom_Agenzia->createElement('RagioneSociale', $agency['companyName']); 
                $Agenzia->appendChild($RagioneSociale); 
            }
            if ($agency['DescrizioneAgenzia']!='') {
                $DescrizioneAgenzia = $dom_Agenzia->createElement('DescrizioneAgenzia', $agency['DescrizioneAgenzia']); 
                $Agenzia->appendChild($DescrizioneAgenzia); 
            } 
            if ($agency['Indirizzo']!='') {
                $Indirizzo = $dom_Agenzia->createElement('Indirizzo', $agency['Indirizzo']); 
                $Agenzia->appendChild($Indirizzo);
            } 
            if ($agency['Civico']!='') {
                $Civico  = $dom_Agenzia->createElement('Civico', $agency['Civico']); 
            $Agenzia->appendChild($Civico);
            } 
            if ($agency['Cap']!='') {
                $Cap = $dom_Agenzia->createElement('Cap', $agency['Cap']); 
                $Agenzia->appendChild($Cap);
            } 
            if ($agency['Latitudine']!='') {
                $Latitudine = $dom_Agenzia->createElement('Latitudine', $agency['Latitudine']); 
                $Agenzia->appendChild($Latitudine);
            } 
            if ($agency['Longitudine']!='') {
                $Longitudine = $dom_Agenzia->createElement('Longitudine', $agency['Longitudine']); 
                $Agenzia->appendChild($Longitudine);
            } 
            if ($agency['URLImmagine']!='') {
                $URLImmagine = $dom_Agenzia->createElement('URLImmagine', $agency['URLImmagine']); 
                $Agenzia->appendChild($URLImmagine);
            } 

            //Nodo Contratti
            $Contratti = $dom_Agenzia->createElement('Contratti', ''); 
            $Agenzia->appendChild($Contratti);
            //SottoNodo Contratto
            $Contratto = $dom_Agenzia->createElement('Contratto', ''); 
            $Contratti->appendChild($Contratto);
                //Elementi SottoNodo Contratto
                $CodicePortale   	= $dom_Agenzia->createElement('CodicePortale', $agency['CodicePortale']); 
                $Contratto->appendChild($CodicePortale);
                $CodiceRelazione   	= $dom_Agenzia->createElement('CodiceRelazione', $agency['CodiceRelazione']); 
                $Contratto->appendChild($CodiceRelazione);
//                if ($Agenzie_Array[$i]['Username']!='') {
//                    $Username = $dom_Agenzia->createElement('Username', $agency['Username']); 
//                    $Contratto->appendChild($Username);
//                } 
//                if ($Agenzie_Array[$i]['Password']!='') {
//                    $Password = $dom_Agenzia->createElement('Password', $agency['Password']); 
//                    $Contratto->appendChild($Password);
//                } 
                $DataInizio = $dom_Agenzia->createElement('DataInizio', $agency['DataInizio']); 
                $Contratto->appendChild($DataInizio);
                $DataFine = $dom_Agenzia->createElement('DataFine', $agency['DataFine']); 
                $Contratto->appendChild($DataFine);
                $NrAnnunci = $dom_Agenzia->createElement('NrAnnunci', $agency['NrAnnunci']); 
                $Contratto->appendChild($NrAnnunci);

            $root_Agenzia->appendChild($Agenzia);

       }

        $dom_Agenzia->appendChild($root_Agenzia); 
        $dom_Agenzia->save($filePath_Agenzia); 
       
    }  

    
    //FUNZIONE CREA FILE XML (IMMOBILI)
    function createXMLfile_Immobili($arrAsteList, $IDGestionale, $arrImg){
        // Sets
        // $filePath_Immobili  = 'xml/immobili.xml';
        $filePath_Immobili  = 'xml/immobiliTEST.xml';


        $dom_Immobili       = new DOMDocument('1.0', 'utf-8'); 
        $root_Immobili      = $dom_Immobili->createElement('Immobili');

        $root_Immobili = $dom_Immobili->createElementNS('http://feed.immobiliarefull.com', 'Feed');
        $dom_Immobili->appendChild($root_Immobili);
        $root_Immobili->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root_Immobili->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'schemaLocation', 'http://feed.immobiliarefull.com/pms/xsd/immobili_1_5_0.xsd');
        $root_Immobili->setAttribute('IDGestionale', $IDGestionale);

        foreach($arrAsteList as $asta) {
            //Nodo Immobile
            $Immobile = $dom_Immobili->createElement('Immobile');
            $Immobile->setAttribute('IDImmobile', $asta['IDImmobile']);
            $Immobile->setAttribute('IDAgenzia', $asta['IDAgenzia']);
            $Immobile->setAttribute('Lingua', $asta['Descrizioni_Lingua']);
            //Elementi Nodo Immobile
                $CodiceNazione = $dom_Immobili->createElement('CodiceNazione', $asta['CodiceNazione']); 
                $Immobile->appendChild($CodiceNazione); 
                $CodiceComune = $dom_Immobili->createElement('CodiceComune', $asta['CodiceComune']); 
                $Immobile->appendChild($CodiceComune);
                if ($asta['CodiceQuartiere']!='') {
                    $CodiceQuartiere = $dom_Immobili->createElement('CodiceQuartiere', $asta['CodiceQuartiere']); 
                    $Immobile->appendChild($CodiceQuartiere);
                }
                if ($asta['CodiceLocalita']!='') {
                    $CodiceLocalita = $dom_Immobili->createElement('CodiceLocalita', $asta['CodiceLocalita']); 
                    $Immobile->appendChild($CodiceLocalita);
                }
                if ($asta['Strada']!='' && $asta['Strada']!=0) {
                    $Strada = $dom_Immobili->createElement('Strada', $asta['Strada']); 
                    $Immobile->appendChild($Strada);
                }
                if ($asta['Indirizzo']!='') {
                    $Indirizzo = $dom_Immobili->createElement('Indirizzo', '');
                    $cdataNode=$dom_Immobili->createCDATASection($asta['Indirizzo']);
                    $Indirizzo->appendChild($cdataNode); 
                    $Immobile->appendChild($Indirizzo);
                }
                if ($asta['Civico']!='') {
                    $Civico = $dom_Immobili->createElement('Civico', $asta['Civico']); 
                    $Immobile->appendChild($Civico);
                }
                $PubblicaCivico = $dom_Immobili->createElement('PubblicaCivico', $asta['PubblicaCivico']); 
                $Immobile->appendChild($PubblicaCivico);
                if ($asta['Cap']!='') {
                    $Cap = $dom_Immobili->createElement('Cap', $asta['Cap']); 
                    $Immobile->appendChild($Cap);
                }
                $PubblicaIndirizzo = $dom_Immobili->createElement('PubblicaIndirizzo', $asta['PubblicaIndirizzo']); 
                $Immobile->appendChild($PubblicaIndirizzo);
                $Latitudine = $dom_Immobili->createElement('Latitudine', $asta['Latitudine']); 
                $Immobile->appendChild($Latitudine);
                $Longitudine = $dom_Immobili->createElement('Longitudine', $asta['Longitudine']); 
                $Immobile->appendChild($Longitudine);
                $PubblicaMappa = $dom_Immobili->createElement('PubblicaMappa', $asta['PubblicaMappa']); 
                $Immobile->appendChild($PubblicaMappa);
                $Contratto = $dom_Immobili->createElement('Contratto', $asta['Contratto']); 
                $Immobile->appendChild($Contratto);
                if ($asta['DurataContratto']!='' && $asta['DurataContratto']!=0) {
                    $DurataContratto = $dom_Immobili->createElement('DurataContratto', $asta['DurataContratto']); 
                    $Immobile->appendChild($DurataContratto);
                }
                $Categoria = $dom_Immobili->createElement('Categoria', $asta['Categoria']); 
                $Immobile->appendChild($Categoria);
                $IDTipologia = $dom_Immobili->createElement('IDTipologia', $asta['IDTipologia']); 
                $Immobile->appendChild($IDTipologia);
                $NrLocali = $dom_Immobili->createElement('NrLocali', $asta['NrLocali']); 
                $Immobile->appendChild($NrLocali);
                $Prezzo = $dom_Immobili->createElement('Prezzo', $asta['Prezzo']); 
                $Immobile->appendChild($Prezzo);
                $TrattativaRiservata = $dom_Immobili->createElement('TrattativaRiservata', $asta['TrattativaRiservata']); 
                $Immobile->appendChild($TrattativaRiservata);
                $MQSuperficie = $dom_Immobili->createElement('MQSuperficie', $asta['MQSuperficie']); 
                $Immobile->appendChild($MQSuperficie);
                $Riferimento = $dom_Immobili->createElement('Riferimento', $asta['Riferimento']); 
                $Immobile->appendChild($Riferimento);
                $TipoProprieta = $dom_Immobili->createElement('TipoProprieta', $asta['TipoProprieta']); 
                $Immobile->appendChild($TipoProprieta);
                $Asta = $dom_Immobili->createElement('Asta', $asta['Asta']); 
                $Immobile->appendChild($Asta);
                $Pregio = $dom_Immobili->createElement('Pregio', $asta['Pregio']); 
                $Immobile->appendChild($Pregio);
                if ($asta['SpeseMensili']!=0) {
                    $SpeseMensili = $dom_Immobili->createElement('SpeseMensili', $asta['SpeseMensili']); 
                    $Immobile->appendChild($SpeseMensili);
                }
                if ($asta['ClasseCatastale']!='') {
                        $ClasseCatastale = $dom_Immobili->createElement('ClasseCatastale', $asta['ClasseCatastale']); 
                $Immobile->appendChild($ClasseCatastale);
                }
                if ($asta['RenditaCatastale']!=0) {
                    $RenditaCatastale = $dom_Immobili->createElement('RenditaCatastale', $asta['RenditaCatastale']); 
                    $Immobile->appendChild($RenditaCatastale);
                }
                if ($asta['URLPlanimetria']!='') {
                    $URLPlanimetria = $dom_Immobili->createElement('URLPlanimetria', $asta['URLPlanimetria']); 
                    $Immobile->appendChild($URLPlanimetria);
                }
                if ($asta['URLVirtualTour']!='') {
                    $URLVirtualTour = $dom_Immobili->createElement('URLVirtualTour', $asta['URLVirtualTour']); 
                    $Immobile->appendChild($URLVirtualTour);
                }
                $Collaborazioni = $dom_Immobili->createElement('Collaborazioni', $asta['Collaborazioni']); 
                $Immobile->appendChild($Collaborazioni);
                $DataInserimento = $dom_Immobili->createElement('DataInserimento', $asta['DataInserimento']); 
                $Immobile->appendChild($DataInserimento);
                $DataModifica = $dom_Immobili->createElement('DataModifica', $asta['DataModifica']); 
                $Immobile->appendChild($DataModifica);

                //SottoNodo Descrizioni
                $Descrizioni = $dom_Immobili->createElement('Descrizioni', ''); 
                $Immobile->appendChild($Descrizioni);
                //SottoNodo Descrizione
                $Descrizione = $dom_Immobili->createElement('Descrizione', ''); 
                $Descrizioni->appendChild($Descrizione);
                $Descrizione->setAttribute('Lingua', $asta['Descrizioni_Lingua']);
                $Titolo = $dom_Immobili->createElement('Titolo', '');
                $cdataNode2=$dom_Immobili->createCDATASection($asta['Titolo']);
                $Titolo->appendChild($cdataNode2); 
                $Descrizione->appendChild($Titolo);
                $Testo = $dom_Immobili->createElement('Testo', '');
                $cdataNode3=$dom_Immobili->createCDATASection($asta['Testo']);
                $Testo->appendChild($cdataNode3); 
                $Descrizione->appendChild($Testo);
                $TestoBreve = $dom_Immobili->createElement('TestoBreve', '');
                $cdataNode4=$dom_Immobili->createCDATASection($asta['TestoBreve']);
                $TestoBreve->appendChild($cdataNode4); 
                $Descrizione->appendChild($TestoBreve);

                //SottoNodo Residenziale
                $Residenziale = $dom_Immobili->createElement('Residenziale', ''); 
                $Immobile->appendChild($Residenziale);
                //Elementi SottoNodo Residenziale
                $StatoImmobile = $dom_Immobili->createElement('StatoImmobile', $asta['StatoImmobile']); 
                $Residenziale->appendChild($StatoImmobile);
                if ($asta['Piano']!=0) {
                    $Piano = $dom_Immobili->createElement('Piano', $asta['Piano']); 
                    $Residenziale->appendChild($Piano);
                }
                if ($asta['PianoFuoriTerra']!=0) {
                    $PianoFuoriTerra = $dom_Immobili->createElement('PianoFuoriTerra', $asta['PianoFuoriTerra']); 
                    $Residenziale->appendChild($PianoFuoriTerra);
                }
                if ($asta['PianiEdificio']!=0) {
                    $PianiEdificio = $dom_Immobili->createElement('PianiEdificio', $asta['PianiEdificio']); 
                    $Residenziale->appendChild($PianiEdificio);
                }
                if ($asta['NrCamereLetto']!=0) {
                    $NrCamereLetto = $dom_Immobili->createElement('NrCamereLetto', $asta['NrCamereLetto']); 
                    $Residenziale->appendChild($NrCamereLetto);
                }
                if ($asta['NrAltreCamere']!=0) {
                    $NrAltreCamere = $dom_Immobili->createElement('NrAltreCamere', $asta['NrAltreCamere']); 
                    $Residenziale->appendChild($NrAltreCamere);
                }
                if ($asta['NrBagni']!=0) {
                    $NrBagni = $dom_Immobili->createElement('NrBagni', $asta['NrBagni']);
                    $Residenziale->appendChild($NrBagni);
                }
                if ($asta['Cucina']!=0) {
                    $Cucina = $dom_Immobili->createElement('Cucina', $asta['Cucina']); 
                    $Residenziale->appendChild($Cucina);
                }
                $NrTerrazzi = $dom_Immobili->createElement('NrTerrazzi', $asta['NrTerrazzi']);
                $Residenziale->appendChild($NrTerrazzi);
                $NrBalconi = $dom_Immobili->createElement('NrBalconi', $asta['NrBalconi']);
                $Residenziale->appendChild($NrBalconi);
                if ($asta['Ascensore']!='') {
                    $Ascensore = $dom_Immobili->createElement('Ascensore', $asta['Ascensore']); 
                    $Residenziale->appendChild($Ascensore);
                }
                if ($asta['NrAscensori']!=0) {
                    $NrAscensori = $dom_Immobili->createElement('NrAscensori', $asta['NrAscensori']); 
                    $Residenziale->appendChild($NrAscensori);
                }
                if ($asta['BoxAuto']!=0) {
                    $BoxAuto = $dom_Immobili->createElement('BoxAuto', $asta['BoxAuto']); 
                    $Residenziale->appendChild($BoxAuto);
                }
                if ($asta['BoxIncluso']!='') {
                    $BoxIncluso = $dom_Immobili->createElement('BoxIncluso', $asta['BoxIncluso']); 
                    $Residenziale->appendChild($BoxIncluso);
                }
                $NrBox = $dom_Immobili->createElement('NrBox', $asta['NrBox']);
                $Residenziale->appendChild($NrBox);
                if ($asta['NrPostiAuto']!=0) {
                    $NrPostiAuto = $dom_Immobili->createElement('NrPostiAuto', $asta['NrPostiAuto']); 
                    $Residenziale->appendChild($NrPostiAuto);
                }
                if ($asta['Cantina']<3) {
                    $Cantina = $dom_Immobili->createElement('Cantina', $asta['Cantina']); 
                    $Residenziale->appendChild($Cantina);
                }
                if ($asta['Portineria']!='') {
                    $Portineria = $dom_Immobili->createElement('Portineria', $asta['Portineria']); 
                    $Residenziale->appendChild($Portineria);
                }
                if ($asta['GiardinoCondominiale']!='') {
                    $GiardinoCondominiale = $dom_Immobili->createElement('GiardinoCondominiale', $asta['GiardinoCondominiale']); 
                    $Residenziale->appendChild($GiardinoCondominiale);
                }
                $GiardinoPrivato = $dom_Immobili->createElement('GiardinoPrivato', $asta['GiardinoPrivato']);
                $Residenziale->appendChild($GiardinoPrivato);
                if ($asta['AriaCondizionata']!=0) {
                    $AriaCondizionata = $dom_Immobili->createElement('AriaCondizionata', $asta['AriaCondizionata']); 
                    $Residenziale->appendChild($AriaCondizionata);
                }
                if ($asta['Riscaldamento']!=0) {
                    $Riscaldamento = $dom_Immobili->createElement('Riscaldamento',$asta['Riscaldamento']); 
                    $Residenziale->appendChild($Riscaldamento);
                    if ($asta['TipoImpiantoRiscaldamento']!=0) {
                        $TipoImpiantoRiscaldamento = $dom_Immobili->createElement('TipoImpiantoRiscaldamento', $asta['TipoImpiantoRiscaldamento']); 
                        $Residenziale->appendChild($TipoImpiantoRiscaldamento);
                    }
                    if ($asta['TipoRiscaldamento']!=0) {
                        $TipoRiscaldamento = $dom_Immobili->createElement('TipoRiscaldamento', $asta['TipoRiscaldamento']); 
                        $Residenziale->appendChild($TipoRiscaldamento);
                    }
                    if ($asta['SpeseRiscaldamento']!=0) {
                        $SpeseRiscaldamento = $dom_Immobili->createElement('SpeseRiscaldamento', $asta['SpeseRiscaldamento']); 
                        $Residenziale->appendChild($SpeseRiscaldamento);
                    }
                }
                $Arredamento = $dom_Immobili->createElement('Arredamento', $asta['Arredamento']); 
                $Residenziale->appendChild($Arredamento);
                $StatoArredamento = $dom_Immobili->createElement('StatoArredamento', $asta['StatoArredamento']); 
                $Residenziale->appendChild($StatoArredamento);
                if ($asta['AnnoCostruzione']!=0) {
                    $AnnoCostruzione = $dom_Immobili->createElement('AnnoCostruzione', $asta['AnnoCostruzione']); 
                    $Residenziale->appendChild($AnnoCostruzione);
                }
                if ($asta['TipoCostruzione']!=0) {
                    $TipoCostruzione = $dom_Immobili->createElement('TipoCostruzione', $asta['TipoCostruzione']); 
                    $Residenziale->appendChild($TipoCostruzione);
                }
                if ($asta['StatoCostruzione']!=0) {
                    $StatoCostruzione = $dom_Immobili->createElement('StatoCostruzione', $asta['StatoCostruzione']); 
                    $Residenziale->appendChild($StatoCostruzione);
                }
                if ($asta['Piscina']!='') {
                    $Piscina = $dom_Immobili->createElement('Piscina', $asta['Piscina']); 
                    $Residenziale->appendChild($Piscina);
                }
                if ($asta['Tennis']!='') {
                    $Tennis = $dom_Immobili->createElement('Tennis', $asta['Tennis']); 
                    $Residenziale->appendChild($Tennis);
                }
                if ($asta['VideoCitofono']!='') {
                    $VideoCitofono = $dom_Immobili->createElement('VideoCitofono', $asta['VideoCitofono']); 
                    $Residenziale->appendChild($VideoCitofono);
                }
                if ($asta['Allarme']!='') {
                    $Allarme = $dom_Immobili->createElement('Allarme', $asta['Allarme']); 
                    $Residenziale->appendChild($Allarme);
                }
                if ($asta['Idromassaggio']!='') {
                    $Idromassaggio = $dom_Immobili->createElement('Idromassaggio', $asta['Idromassaggio']); 
                    $Residenziale->appendChild($Idromassaggio);
                }
                if ($asta['Caminetto']!='') {
                    $Caminetto = $dom_Immobili->createElement('Caminetto', $asta['Caminetto']); 
                    $Residenziale->appendChild($Caminetto);
                }
                if ($asta['FibraOttica']!='') {
                    $FibraOttica = $dom_Immobili->createElement('FibraOttica', $asta['FibraOttica']); 
                    $Residenziale->appendChild($FibraOttica);
                }
                //SottoNodo Energia
                if ($asta['ClasseEnergetica']!='') {
                    $Energia = $dom_Immobili->createElement('Energia', ''); 
                    $Residenziale->appendChild($Energia);
                    //Elementi SottoNodo Energia
                    $ClasseEnergetica = $dom_Immobili->createElement('ClasseEnergetica', $asta['ClasseEnergetica']); 
                    $Energia->appendChild($ClasseEnergetica);
//                    $ClasseEnergetica = $dom_Immobili->createElement('IndicePrestazioneEnergetica', $asta['IndicePrestazioneEnergetica']);
//                    $Energia->appendChild($ClasseEnergetica);
                }

                //Nodo Immagini
                $Immagini   	= $dom_Immobili->createElement('Immagini', ''); 
                $Immobile->appendChild($Immagini);

                    //SottoNodo Immagine
                    $Immagine = $dom_Immobili->createElement('Immagine', '');
                    $Immagini->appendChild($Immagine);
                    $Immagine->setAttribute('IDImmagine', $asta['IDImmagine']);
                    //Elementi SottoNodo Immagine
                    $URL = $dom_Immobili->createElement('URL', htmlspecialchars($asta['immagine_URL']));
                    $Immagine->appendChild($URL);
                    $DataModifica = $dom_Immobili->createElement('DataModifica', $asta['immagine_DataModifica']);
                    $Immagine->appendChild($DataModifica);
                    $Posizione = $dom_Immobili->createElement('Posizione', $asta['immagine_Posizione']);
                    $Immagine->appendChild($Posizione);
                    $TipoFoto = $dom_Immobili->createElement('TipoFoto', $asta['immagine_TipoFoto']);
                    $Immagine->appendChild($TipoFoto);
                    if ($asta['immagine_Titolo']!='') {
                        $Titolo = $dom_Immobili->createElement('Titolo', $asta['immagine_Titolo']);
                        $Immagine->appendChild($Titolo);
                    }

                // Aggiungi Immagini
                if (is_array($arrImg) || is_object($arrImg)) {
                    foreach($arrImg as $img){
                        $trovato = false;
                        if ($img["idAsta"]==$asta['IDImmobile'] && $img["immagine_Posizione"]!=0) {
                            if ($img["fonte"]=="csv") {
                                $trovato = true;
                            }
                            if ($img["fonte"]=="manuale" && $img["idAgenzia"]==$asta['idAgenzia']) {
                                $trovato = true;
                            }
                            if ($img["fonte"]=="manuale" && $img["idAgenzia"]==0) {
                                $trovato = true;
                            }
                        }
                        if ($trovato) {
                            //SottoNodo Immagine
                            $Immagine = $dom_Immobili->createElement('Immagine', '');
                            $Immagini->appendChild($Immagine);
                            $Immagine->setAttribute('IDImmagine', $img['id']);
                            //Elementi SottoNodo Immagine
                            $URL = $dom_Immobili->createElement('URL', htmlspecialchars($img['immagine_URL']));
                            $Immagine->appendChild($URL);
                            $DataModifica = $dom_Immobili->createElement('DataModifica', $img['DataModifica']);
                            $Immagine->appendChild($DataModifica);
//                            $Posizione = $dom_Immobili->createElement('Posizione', $img['immagine_Posizione']);
//                            $Immagine->appendChild($Posizione);
                            $TipoFoto = $dom_Immobili->createElement('TipoFoto', $img['immagine_TipoFoto']);
                            $Immagine->appendChild($TipoFoto);
                            if ($img['immagine_Titolo']!='') {
                                $Titolo = $dom_Immobili->createElement('Titolo', $img['immagine_Titolo']);
                                $Immagine->appendChild($Titolo);
                            }
                        }
                    }
                }


            $root_Immobili->appendChild($Immobile);

       }

       $dom_Immobili->appendChild($root_Immobili); 
       $dom_Immobili->save($filePath_Immobili); 


   } 


    
    
    
    
}





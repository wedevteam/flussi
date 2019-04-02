<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

// Modelli
require 'Models/aste_model.php';
require 'Models/agency_model.php';
require 'Models/comuni_model.php';
require 'Models/dbcucine_model.php';
require 'Models/dbstrade_model.php';
require 'Models/imports_model.php';
require 'Models/importdetails_model.php';
require 'Models/exports_model.php';
require 'Models/relasteagenzie_model.php';
require 'Models/relagenziepref_model.php';
require 'Models/relagenzieprefview_model.php';

class Dashboard extends Controller {

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
        $this->view->mainMenu = Functions::setActiveMenu("dashboard");
        // Get Errors
        $this->view->error = Functions::getError($error);
        
        // Get Data
        $functionsModel = new Functions();
        $asteModel = new Aste_Model();
        $arrAsteListAll = $asteModel->getAsteList(null," id DESC ");
        if ($this->view->userLogged["role"]!="admin") {
            $relAsAgModel = new RelAsteAgenzie_Model();
            $arrAsteAgenziaList = $relAsAgModel->getRelAsteAgenzieList($this->view->userLogged["id"], Null, Null);
            $relAgPrefViewModel = new RelAgenziePrefView_Model();
            $relAgPrefViewList = $relAgPrefViewModel->getRelAgenziePrefList($this->view->userLogged["id"], NULL,NULL);
            $arrAsteListAgPreFilter =array();
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
                    "rge" =>$item["rge"],
                    "lotto" =>$item["lotto"],
                    "importoBaseAsta" =>$item["importoBaseAsta"],
                    "importoOffertaMinima" =>$item["importoOffertaMinima"],
                    "dataAsta" =>$item["dataAsta"],
                    "CodiceComune" =>$item["CodiceComune"],
                    "Comune" =>$item["Comune"],
                    "Provincia" =>$item["Provincia"],
                    "ComuneProvinciaCompleto" =>$item["ComuneProvinciaCompleto"],
                    "Strada" =>$item["Strada"],
                    "Strada_testo" =>$item["Strada_testo"],
                    "Indirizzo" =>$item["Indirizzo"],
                    "Civico" =>$item["Civico"],
                    "Cap" =>$item["Cap"],
                    "MQSuperficie" =>$item["MQSuperficie"],
                    "Titolo" =>$item["Titolo"],
                    "immagine_URL" =>$immagineURL ,
                    "status" =>$Status,
                    "Prezzo" =>$Prezzo
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
        $this->view->asteNum = sizeof($arrAsteList);


        if ($this->view->userLogged["role"]=="admin") {
            // Tot. Agenzie
            $agencyModel = new Agency_Model();
            $resAgency = $agencyModel->getUsersListByRole("agency");
            $numAgency = 0;
            if (is_array($resAgency) || is_object($resAgency)) {
                $numAgency = sizeof($resAgency);
            }
            $this->view->agencyList = $resAgency;
            $this->view->agencyNum = $numAgency;
            
            // Tot. Imports
            $importsModel = new Imports_Model();
            $resImports = $importsModel->getImportsList();
            $numImports = 0;
            if (is_array($resImports) || is_object($resImports)) {
                $numImports = sizeof($resImports);
            }
            $this->view->importsNum = $numImports;
            
            // Tot. Exports
            $exportsModel = new Exports_Model();
            $resExports = $exportsModel->getExportsList();
            $numExports = 0;
            if (is_array($resExports) || is_object($resExports)) {
                $numExports = sizeof($resExports);
            }
            $this->view->exportsNum = $numExports;



            // Lista Comuni Tribunali
            $comuniModel = new Comuni_Model();
            $comuniList = $comuniModel->getComuniList();
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
                            if ($asta["codiceComuneTribunale"] == $functionsModel->ConvertCodiceIstat($comune["codice_istat"])) {
                                array_push($arrComuniTribunale, $arrItem);
                            }
                        }
                    }
                }
            }
            $arrComuniTribunaleList = array();
            if (sizeof($arrComuniTribunale)>0) {
                $tempArr2 = array_unique(array_column($arrComuniTribunale, 'codice_istat'));
                $arrComuniTribunaleList = array_intersect_key($arrComuniTribunale, $tempArr2);
            }
            $arrTrib = array();
            if (sizeof($arrComuniTribunaleList)>0) {
                // Num.Aste per Tribunale
                foreach ($arrComuniTribunaleList as $tribunale   ) {
                    $codiceTribunale = $functionsModel->ConvertCodiceIstat($tribunale["codice_istat"]);
                    $numAste = $asteModel->getNumAsteFromTribunale($codiceTribunale);
                    $arrItem = array(
                        "nome"=>$tribunale["nome"],
                        "siglaprovincia"=>$tribunale["siglaprovincia"],
                        "numAste"=>$numAste
                    );
                    array_push($arrTrib, $arrItem);
                }
            }
            $this->view->comuniTribunaliList = $arrTrib;

        } else {

            // Num. Aste Esportate
            $this->view->asteExportNum = 0;

            // Num.Esportazioni
            $this->view->exportsNum = 0;
        }
        
        
        // View
        $this->view->render('dashboard/index', true, HEADER_MAIN);
    }


    // ESCI
    function logout() {
        Session::destroy();
        header('location: ' . URL . 'login');
        exit;
    }

}

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
                // data.Visita
                $dataRichiestaVisione = Null;
                // Img
                $immagineURL = $item["immagine_URL"];
                // Set dati agenzia
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
                                $Status =  $rel["status"];
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

        } else {

            // Num. Aste Esportate
            $relAsteAgenzie = $relAsAgModel->getListByAgenziaStatus($this->view->userLogged["id"],"importato");
            $arrAsteEsportate = array();
            if (is_array($relAsteAgenzie) || is_object($relAsteAgenzie)) {
                $tempArr2 = array_unique(array_column($relAsteAgenzie, 'idAsta'));
                $arrAsteEsportate = array_intersect_key($relAsteAgenzie, $tempArr2);
            }
            $this->view->asteExportNum = sizeof($arrAsteEsportate);

            // Num. Esportazioni
            $this->view->exportsNum = 0;
        }

        // Lista Comuni Tribunali
        $comuniModel = new Comuni_Model();
        $comuniList = $comuniModel->getComuniList();
        $arrComuniTribunale = array();
        if (is_array($comuniList) || is_object($comuniList)) {
            // Inserisci solo Comuni presenti nelle aste
            if (is_array($arrAsteList) || is_object($arrAsteList)) {
                foreach ($comuniList as $comune) {
                    foreach ( $arrAsteList as $asta ) {
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

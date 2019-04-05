<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */
// error_reporting(E_ERROR | E_PARSE);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
// Modelli
require 'Models/aste_model.php';
require 'Models/agency_model.php';
require 'Models/comuni_model.php';
require 'Models/dbcucine_model.php';
require 'Models/dbstrade_model.php';
require 'Models/importdetails_model.php';
require 'Models/relasteagenzie_model.php';
require 'Models/relasteimg_model.php';


class Imports extends Controller {

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
        $this->view->mainMenu = Functions::setActiveMenu("imports");
        // Get Errors
        $this->view->error = Functions::getError($error);
        
        // Get Data
        $importModel = new Imports_Model();
        $this->view->importsList = $importModel->getImportsList();
        $this->view->numItems = 0;
        if (is_array($this->view->importsList) || is_object($this->view->importsList)) {
            $this->view->numItems = sizeof($this->view->importsList);
        }
        
        // View
        $this->view->render('imports/index', true, HEADER_MAIN);
    }

    // GET: Create
    public function create($error=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("imports");
        // Get Errors
        $this->view->error = Functions::getError($error);
        
        // View
        $this->view->render('imports/create', true, HEADER_MAIN);
    }
    
    
    
    
    // POST: Import CSV
    public function resultImport($error=null) {
        // Set Active Menu
        $this->view->mainMenu = Functions::setActiveMenu("imports");
        // Get Errors
        $this->view->error = Functions::getError($error);
        
        
         // File
        $fileName = date("Y-m-d")."_". basename($_FILES["filecsv"]["name"]);
        // Target PATH
        $target_dir = "uploads/";
        $target_file = $target_dir .$fileName;
        
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Array Immobili
        $arrImmobili = array();
        // READ FILE
        if (move_uploaded_file($_FILES["filecsv"]["tmp_name"], $target_file)) {
            // Read File
            $arrImmobili = $this->readImporFile($target_file,"test",NULL);
            // Result
            $numImmobiliValidi = 0;
            foreach($arrImmobili as $imm){
                if ($imm->uploadValido) {
                    $numImmobiliValidi ++;
                }
            }
            // Return
            if (sizeof($arrImmobili) ==0) {
                $this->create(ER_IMPORT_INVALID,$target_file);
                return false;
            } else {
                $this->view->target_file = $target_file;
                $this->view->arrAsteList = $arrImmobili;
                $this->view->numAsteTot = sizeof($arrImmobili);
                $this->view->numAsteValide = $numImmobiliValidi;
                
                // View
                $this->view->render('imports/resultImport', true, HEADER_MAIN);
            }
        } else {
            // echo "Sorry, there was an error uploading your file.";
            $this->create(ER_IMPORT_INVALID_MOVEFILE);
            return false;
        }
    }
    
    
    
    
    // POST: Save Import
    public function saveImport($error=null) {
        $target_file = $_POST["target_file"];
        $this->view->target_file = $target_file;
        
        // Elimina tutte le IMPORT che non hanno ASTE correlate
        
        
        // Inserisci Nuova IMPORT
        $data = array(
            ':createdAt' => date("Y-m-d H:i:s"),
            ':status' => "on",
            ':fileName' => $target_file,
            ':status' => "on",
        );
        // INSERT
        $importModel = new Imports_Model();
        $idImport = $importModel->create($data);
        
        if ( $idImport>0 ) {
            // Read File e SALVA ASTE
            $arrImmobili = $this->readImporFile($target_file,"prod",$idImport);
            // Vai a Importazioni
            $this->index(NULL);
        } else {
            $this->create(ER_IMPORT_INVALID);
            return false;
        }
    }
    
    
    
    
    
    // LETTURA FILE E 
    function readImporFile($target_file,$tipoElaborazione,$idImport) {
        if (!isset($target_file) || $target_file=="" || !isset($tipoElaborazione) || $tipoElaborazione=="" ) {
            $this->create(ER_IMPORT_INVALID);
            return false;
        } 
        
        // Array vuoto
        $arrImmobili = array();

        // ===================================================================== GET DATA in memoria
        // Lista Agenzie
        $usersModel = new Agency_Model();
        $agencyList = $usersModel->getUsersListByRole("agency");
        // RelAsteAgenzie
        $relAsteAgenzieModel = new RelAsteAgenzie_Model();
        $relAsteAgenzie = $relAsteAgenzieModel->getRelAsteAgenzieList(NULL, NULL, NULL);
        // Immobili
        $asteModel = new Aste_Model();
        $arrAsteList = $asteModel->getAsteList(NULL,NULL);
        // Comuni
        $comuniModel = new Comuni_Model();
        $arrComuni = $comuniModel->getComuniList();
        // Db Strade
        $dbStradeModel = new DbStrade_Model();
        $arrDbStrade=$dbStradeModel->getDbStradeList();
        // Db Cucine
        $dbCucineModel = new DbCucine_Model();
        $arrDbCucine=$dbCucineModel->getDbCucineList();
        

        //Pulizia Stringhe
        $a = array('224','150','146','232','249','233','147','148','176','128','192','188','236','189','149','242');
        $b = array('a','','','','u','e','','','','','','','','','','');
        $numero=16;

        // Leggi file
        $fileHandle = fopen($target_file, "r");
        $i=0;
        while (($row = fgetcsv($fileHandle, 0, "|")) !== FALSE) {
            $i++;
            if ($i!=1) { // Tutte le righe tranne la PRIMA
                // INIT CLASS
                $nuovoImmobile = new Aste_Model();

                // Check Valido e Err
                $nuovoImmobile->uploadValido = true;
                $nuovoImmobile->uploadErrorsTxt = "";

                // ======================================================DATI BASE
                $nuovoImmobile->idImport = 0;
                if ($idImport!=Null) {
                    $nuovoImmobile->idImport = $idImport;
                }
                $nuovoImmobile->DataInserimento_d = date("Y-m-d H:i:s");
                $nuovoImmobile->DataInserimento = substr($nuovoImmobile->DataInserimento_d,0,10).'T'.substr($nuovoImmobile->DataInserimento_d,11,19);
                $nuovoImmobile->DataModifica_d = date("Y-m-d H:i:s");
                $nuovoImmobile->DataModifica = substr($nuovoImmobile->DataModifica_d,0,10).'T'.substr($nuovoImmobile->DataModifica_d,11,19);
                $nuovoImmobile->status = "on";
                $nuovoImmobile->errorsText = "";
                $nuovoImmobile->isNew = true;
                $nuovoImmobile->uploadType = "Nuovo";

                // ======================================================RGE - LOTTO - TRIBUNALE
                $nuovoImmobile->rge = $row[0];
                if (!isset($row[0]) || trim($row[0]) === '') {
                    $nuovoImmobile->uploadValido = false;
                    $nuovoImmobile->uploadErrorsTxt .= "RGE non presente. ";
                }
                $nuovoImmobile->lotto = $row[1];
                if (!isset($row[1]) || trim($row[1]) === '') {
                    $nuovoImmobile->uploadValido = false;
                    $nuovoImmobile->uploadErrorsTxt .= "LOTTO non presente. ";
                }    
                $nuovoImmobile->codiceComuneTribunale = ""; // ==> TROVA NOME COMUNE DA NOME+SIGLAPROV
                $nuovoImmobile->ComuneTribunale = "";
                $nuovoImmobile->SiglaProvTribunale = "";
                if ($row[2]!="" && isset($row[2]) && $row[3]!="" && isset($row[3]) ) {
                    $nomeComuneTribunale = strtolower($row[2]);
                    $siglaProvTribunale = strtolower($row[3]);
                    foreach ($arrComuni as $comune) {  
                        if (strtolower($comune["nome"])==strtolower($nomeComuneTribunale) && strtolower($comune["siglaprovincia"])==$siglaProvTribunale ) {
                            $codiceIstat = $this->ConvertCodiceIstat($comune["codice_istat"]);
                            $nuovoImmobile->codiceComuneTribunale = $codiceIstat;
                            $nuovoImmobile->ComuneTribunale = $comune["nome"];
                            $nuovoImmobile->SiglaProvTribunale = $comune["siglaprovincia"];
                        }
                    }
                }
                if ($nuovoImmobile->codiceComuneTribunale == "") {
                    $nuovoImmobile->uploadValido = false;
                    $nuovoImmobile->uploadErrorsTxt .= "Tribunale non trovato. ";
                    $nuovoImmobile->status = "error";
                }
                // ====================================================== COMUNE
                $nuovoImmobile->CodiceComune = ""; // ==> TROVA COD.ISTAT da NOME COMUNE + PROVINCIA
                $nuovoImmobile->Comune = "";
                $nuovoImmobile->Provincia = "";
                $nuovoImmobile->ComuneProvinciaCompleto = "";
                if ($row[21]!="" && isset($row[21]) && $row[22]!="" && isset($row[22]) ) {
                    $nomeComune = strtolower($row[21]);
                    $provincia = strtolower($row[22]);
                    foreach ($arrComuni as $comune) { 
                        if (strtolower($comune["nome"])==strtolower($nomeComune) && strtolower($comune["siglaprovincia"])==strtolower($provincia) ) {
                            $codiceIstat = $this->ConvertCodiceIstat($comune["codice_istat"]);
                            $nuovoImmobile->CodiceComune = $codiceIstat;
                            $nuovoImmobile->Comune = $comune["nome"];
                            $nuovoImmobile->Provincia = $comune["siglaprovincia"];
                            $nuovoImmobile->ComuneProvinciaCompleto = $comune["nome"] . " (" . $comune["siglaprovincia"] . ")";
                        }
                    }
                }
                if ($nuovoImmobile->CodiceComune == "") {
                    $nuovoImmobile->uploadValido = false;
                    $nuovoImmobile->uploadErrorsTxt .= "Comune non trovato. ";
                    $nuovoImmobile->status = "error";
                }
                // ====================================================== INDIRIZZO / CIVICO
                $nuovoImmobile->IndirizzoCompleto = "";
                $nuovoImmobile->Indirizzo = "";
                $nuovoImmobile->PubblicaCivico = "false";
                $nuovoImmobile->Civico = "";
                if (isset($row[23]) && trim($row[23]) != '') {
                    $nuovoImmobile->Indirizzo = $row[23];
                    $Indirizzo_completo = $this->sostituisci_carattere($a,$b,$numero,$row[23]);
                    $Indirizzo_completo = $this->sanitizeXML($Indirizzo_completo);
                    $nuovoImmobile->IndirizzoCompleto = $Indirizzo_completo;

                    $stringa_dacercare = ",";
                    $lastPos = 0;
                    $positions = array();
                    $indice_virgola=0;
                    while (($lastPos = strpos($Indirizzo_completo, $stringa_dacercare, $lastPos))!== false) {
                        $positions[] = $lastPos;
                        $lastPos = $lastPos + strlen($stringa_dacercare);
                        ++$indice_virgola; //(N.Ricorrenze virgola)
                    }

                    // echo '<br>stringa da cercare'.$indice_virgola;
                    if ($indice_virgola==0) {
                        $nuovoImmobile->Indirizzo = $Indirizzo_completo;
                        $nuovoImmobile->PubblicaCivico = "false";
                        $nuovoImmobile->Civico = "";
                    } 
                    if ($indice_virgola==1) {
                        $posizione_virgola=strpos($Indirizzo_completo, ',');
                        $nuovoImmobile->Indirizzo = trim( substr( $nuovoImmobile->Indirizzo,0,$posizione_virgola));
                        $nuovoImmobile->PubblicaCivico = "true";
                        $nuovoImmobile->Civico = trim(substr( $nuovoImmobile->Indirizzo,$posizione_virgola+1,strlen( $nuovoImmobile->Indirizzo)));
                        if (!$this->solonumeri($nuovoImmobile->Civico)) {
                            $nuovoImmobile->Civico = "";
                            $nuovoImmobile->PubblicaCivico = "false";
                        }	
                    } 
                    if ($indice_virgola>1) {
                        $posizione_virgola_ultima=$positions[$indice_virgola-1];
                        $nuovoImmobile->Indirizzo = trim( substr( $nuovoImmobile->Indirizzo,0,$posizione_virgola_ultima));
                        $nuovoImmobile->PubblicaCivico = "true";
                        $nuovoImmobile->Civico = trim( $nuovoImmobile->Indirizzo, ($posizione_virgola_ultima+1),(strlen( $nuovoImmobile->Indirizzo)) );
                    }
                }
                if ($nuovoImmobile->Indirizzo == "") {
                    $nuovoImmobile->uploadValido = false;
                    $nuovoImmobile->status = "error";
                    $nuovoImmobile->uploadErrorsTxt .= "Indirizzo non valido. ";
                }



                // ====================================================== STRADA (//Trova Strada (via, piazza etc.) e confrontala con stringhe in Tab.db_strade)
                $nuovoImmobile->Strada = 0;
                $nuovoImmobile->Strada_testo = "";
                if ($nuovoImmobile->Indirizzo != "") {
                    //Cerco prima stringa di Indirizzo
                    $array_words = explode(' ',trim($nuovoImmobile->Indirizzo));
                    $stringa_strada=$array_words[0];
//echo '<br>Stringa Strada: '.$stringa_strada;							
                    //Match con db_strade
                    foreach ($arrDbStrade as $strada) {  
                        if (strtolower($strada["nome"])==strtolower($stringa_strada) ) {
                            $nuovoImmobile->Strada = $strada["id"];
                            $nuovoImmobile->Strada_testo = $strada["nome"];
                            $lunghezza_stringa_strada=strlen($stringa_strada);
                            $nuovoImmobile->Indirizzo = substr($nuovoImmobile->Indirizzo,$lunghezza_stringa_strada+1,strlen($nuovoImmobile->Indirizzo));
                        }
                    }
                }
//                echo '<br>Strada_testo:'.$nuovoImmobile->Strada_testo;
//                echo '<br>indirizzo2:'.$nuovoImmobile->Indirizzo;
//                exit;


                $nuovoImmobile->importoBaseAsta = 0;
                if (isset($row[16]) && trim($row[16]) != '') {
                    $nuovoImmobile->importoBaseAsta = str_replace(",",".",$row[16]);
                } 
                if ( $nuovoImmobile->importoBaseAsta == 0 ) {
                    $nuovoImmobile->uploadValido = false;
                    $nuovoImmobile->status = "error";
                    $nuovoImmobile->uploadErrorsTxt .= "Base Asta invalida. ";
                }
                $nuovoImmobile->importoOffertaMinima = 0;
                if (isset($row[17]) && trim($row[17]) != '') {
                    $nuovoImmobile->importoOffertaMinima = str_replace(",",".",$row[17]);
                } else {
                    if ( $nuovoImmobile->importoBaseAsta > 0 ) {
                        $nuovoImmobile->importoOffertaMinima = round($nuovoImmobile->importoBaseAsta - ($nuovoImmobile->importoBaseAsta * 75 / 100 )) ;
                    }
                }
                
                
                $nuovoImmobile->linkTribunale = "";
                if (isset($row[4]) && trim($row[4]) != '') {
                    $nuovoImmobile->linkTribunale = $row[4];
                } else {
                    $nuovoImmobile->uploadValido = false;
                    $nuovoImmobile->status = "error";
                    $nuovoImmobile->uploadErrorsTxt .= "Link Tribunale assente. ";
                }
                $nuovoImmobile->tipoProcedura = "";
                if (isset($row[5]) && trim($row[5]) != '') {
                    $nuovoImmobile->tipoProcedura = $this->sostituisci_carattere($a,$b,$numero,$row[5]);
                    $nuovoImmobile->tipoProcedura = $this->sanitizeXML($nuovoImmobile->tipoProcedura);
                }
                $nuovoImmobile->rito = "";
                if (isset($row[6]) && trim($row[6]) != '') {
                    $nuovoImmobile->rito = $this->sostituisci_carattere($a,$b,$numero,$row[6]);
                    $nuovoImmobile->rito = $this->sanitizeXML($nuovoImmobile->rito);
                }
                $nuovoImmobile->giudice = "";
                if (isset($row[7]) && trim($row[7]) != '') {
                    $nuovoImmobile->giudice = $this->sostituisci_carattere($a,$b,$numero,$row[7]);
                    $nuovoImmobile->giudice = $this->sanitizeXML($nuovoImmobile->giudice);
                }
                $nuovoImmobile->delegato = "";
                if (isset($row[8]) && trim($row[8]) != '') {
                    $nuovoImmobile->delegato = $this->sostituisci_carattere($a,$b,$numero,$row[8]);
                    $nuovoImmobile->delegato = $this->sanitizeXML($nuovoImmobile->delegato);
                }
                $nuovoImmobile->custode = "";
                if (isset($row[9]) && trim($row[9]) != '') {
                    $nuovoImmobile->custode = $this->sostituisci_carattere($a,$b,$numero,$row[9]);
                    $nuovoImmobile->custode = $this->sanitizeXML($nuovoImmobile->custode);
                }
                $nuovoImmobile->curatore = "";
                if (isset($row[10]) && trim($row[10]) != '') {
                    $nuovoImmobile->curatore = $this->sostituisci_carattere($a,$b,$numero,$row[10]);
                    $nuovoImmobile->curatore = $this->sanitizeXML($nuovoImmobile->curatore);
                }
                $nuovoImmobile->valorePerizia = "";
                if (isset($row[11]) && trim($row[11]) != '') {
                    $nuovoImmobile->valorePerizia = str_replace(",",".",$row[11]);
                }
                $nuovoImmobile->dataPubblicazione = "";
                if (strlen($row[12])==10 || isset($row[12]) || trim($row[12]) != '') {
                    $nuovoImmobile->dataPubblicazione = substr($row[12],6,4).'-'.substr($row[12],3,2).'-'.substr($row[12],0,2);
                } 
                $nuovoImmobile->datiCatastali = "";  
                if (isset($row[14]) && trim($row[14]) != '') {
                    $nuovoImmobile->datiCatastali = $this->sostituisci_carattere($a,$b,$numero,$row[14]);
                    $nuovoImmobile->datiCatastali = $this->sanitizeXML($nuovoImmobile->datiCatastali);
                }  
                $nuovoImmobile->noteAggiuntive = "";
                if (isset($row[18]) && trim($row[18]) != '') {
                    $nuovoImmobile->noteAggiuntive = $this->sostituisci_carattere($a,$b,$numero,$row[18]);
                    $nuovoImmobile->noteAggiuntive = $this->sanitizeXML($nuovoImmobile->noteAggiuntive);
                }
                $nuovoImmobile->dataAsta = NULL;
                if (strlen($row[19])!=10 || !isset($row[19]) || trim($row[19]) === '') {
                    $nuovoImmobile->uploadValido = false;
                    $nuovoImmobile->status = "error";
                    $nuovoImmobile->uploadErrorsTxt .= "Data Asta non valida o assente. ";
                } else {
                    $nuovoImmobile->dataAsta = substr($row[19],6,4).'-'.substr($row[19],3,2).'-'.substr($row[19],0,2);
                }
                $nuovoImmobile->linkAllegati = "";
                if (isset($row[20]) && trim($row[20]) != '') {
                    $nuovoImmobile->linkAllegati = $row[20];
                }
                $nuovoImmobile->Cap = "";  
                if (isset($row[24]) && trim($row[24]) != '') {
                    $nuovoImmobile->Cap = $row[24];
                } 
                $nuovoImmobile->Latitudine = "";  
                $isLat = FALSE;
                if (isset($row[25]) && trim($row[25]) != '') {
                    $nuovoImmobile->Latitudine = $row[25];
                    $isLat = TRUE;
                } 
                $nuovoImmobile->Longitudine = "";   
                $isLon = FALSE; 
                if (isset($row[26]) && trim($row[26]) != '') {
                    $nuovoImmobile->Longitudine = $row[26];
                    $isLon = TRUE; 
                } 
                
                
                // TROVA COORDINATE
                if ($nuovoImmobile->Latitudine=="" || $nuovoImmobile->Longitudine=="" ) {
                    if ($nuovoImmobile->uploadValido 
                            && $nuovoImmobile->CodiceComune!="" 
                            && $nuovoImmobile->Indirizzo!="") {
                        $address = $nuovoImmobile->Comune.' ('.$nuovoImmobile->Provincia.')'.', '.$nuovoImmobile->IndirizzoCompleto.', Italy';
                        // echo '<br>indirizzo: '.$address;
                        $functions = new Functions();
                        $coordinate = $functions->getLatLonFromAddress($address);
                        $arrCoo = explode(",", $coordinate);
                        $nuovoImmobile->Latitudine  = $arrCoo[0];
                        $nuovoImmobile->Longitudine  = $arrCoo[1];
                        if (!isset($nuovoImmobile->Latitudine) || trim($nuovoImmobile->Latitudine) === ''
                            || !isset($nuovoImmobile->Longitudine) || trim($nuovoImmobile->Longitudine) === '' ) {
                            $nuovoImmobile->Latitudine  = "";
                            $nuovoImmobile->Longitudine  = "";
                        }  
                    }
                }
                if ($nuovoImmobile->Latitudine=="" || $nuovoImmobile->Longitudine=="") {
                    $nuovoImmobile->uploadValido = false;
                    $nuovoImmobile->status = "error";
                    $nuovoImmobile->uploadErrorsTxt .= "Coordinate non trovate. ";
                }
                
                
                $nuovoImmobile->NrLocali = 1;  
                if (isset($row[27]) && trim($row[27]) != '') {
                    $nuovoImmobile->NrLocali = $row[27];
                } 
                $nuovoImmobile->MQSuperficie = 1;  
                if (isset($row[28]) && trim($row[28]) != '') {
                    $nuovoImmobile->MQSuperficie = str_replace(",",".",$row[28]);
                } else {
                    $nuovoImmobile->uploadValido = false;
                    $nuovoImmobile->status = "error";
                    $nuovoImmobile->uploadErrorsTxt .= "Metri Quadrati assenti. ";
                }
                $nuovoImmobile->SpeseMensili = 0;  
                if (isset($row[29]) && trim($row[29]) != '') {
                    $nuovoImmobile->SpeseMensili = $row[29];
                } 
                $nuovoImmobile->URLPlanimetria = "";  
                if (isset($row[30]) && trim($row[30]) != '' && substr($row[30],0,4)=="http") {
                    $nuovoImmobile->URLPlanimetria = $row[30];
                } 
                $nuovoImmobile->URLVirtualTour = "";  
                if (isset($row[31]) && trim($row[31]) != '' && substr($row[31],0,4)=="http") {
                    $nuovoImmobile->URLVirtualTour = $row[31];
                } 
                $nuovoImmobile->URLVideo = "";  
                if (isset($row[32]) && trim($row[32]) != '' && substr($row[32],0,4)=="http") {
                    $nuovoImmobile->URLVideo = $row[32];
                } 
                $nuovoImmobile->ClasseCatastale = "";  
                if (isset($row[33]) && trim($row[33]) != '') {
                    $nuovoImmobile->ClasseCatastale = $row[33];
                } 
                $nuovoImmobile->RenditaCatastale = 0;  
                if (isset($row[34]) && trim($row[34]) != '') {
                    $nuovoImmobile->RenditaCatastale = str_replace(",",".",$row[34]);
                } 
                $nuovoImmobile->Titolo = "";  
                if (isset($row[35]) && trim($row[35]) != '') {
                    $nuovoImmobile->Titolo = $this->sostituisci_carattere($a,$b,$numero,$row[35]);
                    $nuovoImmobile->Titolo = $this->sanitizeXML($nuovoImmobile->Titolo);
                } else {
                    $nuovoImmobile->Titolo = "Appartamento ASTA " . $nuovoImmobile->ComuneProvinciaCompleto;
                }
                $nuovoImmobile->Testo = "";  
                if (isset($row[36]) && trim($row[36]) != '') {
                    $nuovoImmobile->Testo = $this->sostituisci_carattere($a,$b,$numero,$row[36]);
                    $nuovoImmobile->Testo = $this->sanitizeXML($nuovoImmobile->Testo);
                    if (strlen($nuovoImmobile->Testo)>2900) {
                        $nuovoImmobile->Testo = substr($nuovoImmobile->Testo,0,2900)."...";
                    }
                }  else {
                    $nuovoImmobile->uploadValido = false;
                    $nuovoImmobile->status = "error";
                    $nuovoImmobile->uploadErrorsTxt .= "Testo assente o non valido. ";
                }
                $nuovoImmobile->TestoBreve = "";  
                if (isset($row[37]) && trim($row[37]) != '') {
                    $nuovoImmobile->TestoBreve = $this->sostituisci_carattere($a,$b,$numero,$row[37]);
                    $nuovoImmobile->TestoBreve = $this->sanitizeXML($nuovoImmobile->TestoBreve);
                    $nuovoImmobile->TestoBreve = substr($nuovoImmobile->TestoBreve,0,500) . "...";
                }  else {
                    if ($nuovoImmobile->Testo != "") {
                        $nuovoImmobile->TestoBreve = substr($nuovoImmobile->Testo,0,500) . "...";
                    }
                }
                $nuovoImmobile->Piano = 0;  
                if (isset($row[38]) && trim($row[38]) != '') {
                    $nuovoImmobile->Piano = trim($row[38]);
                } 
                $nuovoImmobile->PianoFuoriTerra = 0;  
                if (isset($row[39]) && trim($row[39]) != '' && strlen($row[39])==1) {
                    $nuovoImmobile->PianoFuoriTerra = trim($row[39]);
                } 
                $nuovoImmobile->PianiEdificio = 0;  
                if (isset($row[40]) && trim($row[40]) != '') {
                    $nuovoImmobile->PianiEdificio = trim($row[40]);
                } 
                $nuovoImmobile->NrCamereLetto = 0;  
                if (isset($row[41]) && trim($row[41]) != '') {
                    $nuovoImmobile->NrCamereLetto = trim($row[41]);
                } 
                $nuovoImmobile->NrAltreCamere = 0;  
                if (isset($row[42]) && trim($row[42]) != '') {
                    $nuovoImmobile->NrAltreCamere = trim($row[42]);
                } 
                $nuovoImmobile->NrBagni = 0;  
                if (isset($row[43]) && trim($row[43]) != '') {
                    $nuovoImmobile->NrBagni = trim($row[43]);
                } 
                $nuovoImmobile->Cucina = 255;  
                $nuovoImmobile->CucinaDesc = "";  
                if (isset($row[44]) && trim($row[44]) != '') {
                    foreach ($arrDbCucine as $cucina) {  
                        if (strtolower($cucina["descrizione"])==strtolower($row[44]) ) {
                            $nuovoImmobile->Cucina = $cucina["id"];
                            $nuovoImmobile->CucinaDesc = $cucina["descrizione"];  
                        }
                    }
                } 
                $nuovoImmobile->NrTerrazzi = 0;  
                if (isset($row[45]) && trim($row[45]) != '') {
                    $nuovoImmobile->NrTerrazzi = trim($row[45]);
                } 
                $nuovoImmobile->NrBalconi = 0;  
                if (isset($row[46]) && trim($row[46]) != '') {
                    $nuovoImmobile->NrBalconi = trim($row[46]);
                } 
                $nuovoImmobile->Ascensore = "false"; 
                $nuovoImmobile->NrAscensori = 0;  
                if (isset($row[47]) && trim($row[47]) != '') {
                    if ( trim($row[47])=="true" ) {
                        $nuovoImmobile->Ascensore = "true"; 
                        if (isset($row[48]) && trim($row[48]) != '') {
                            $nuovoImmobile->NrAscensori = trim($row[48]);  
                        }
                        if ( $nuovoImmobile->NrAscensori==0) {
                            $nuovoImmobile->NrAscensori = 1;
                        }
                    } 
                }
                $nuovoImmobile->BoxAuto = 255;  
                if (isset($row[49]) && trim($row[49]) != '') {
                    if ( strtolower($row[49])=="singolo" ) {
                        $nuovoImmobile->BoxAuto = 1;  
                    }
                    if ( strtolower($row[49])=="doppio" ) {
                        $nuovoImmobile->BoxAuto = 2;  
                    }
                    if ( strtolower($row[49])=="triplo" ) {
                        $nuovoImmobile->BoxAuto = 3;  
                    }
                }   
                $nuovoImmobile->BoxIncluso = "false";  
                if (isset($row[50]) && trim($row[50]) != '') {
                   if ( trim($row[50])=="true" ) {
                       $nuovoImmobile->BoxIncluso = "true";  
                   }
                } 
                $nuovoImmobile->NrBox = 0;  
                if (isset($row[51]) && trim($row[51]) != '') {
                   $nuovoImmobile->NrBox = trim($row[51]);  
                } 
                $nuovoImmobile->NrPostiAuto = 0;  
                if (isset($row[52]) && trim($row[52]) != '') {
                   $nuovoImmobile->NrPostiAuto = trim($row[52]);  
                } 
                $nuovoImmobile->Cantina = 0;  
                if (isset($row[53]) && trim($row[53]) != '') {
                   if ( strtolower($row[53])=="presente" ) {
                        $nuovoImmobile->Cantina = 1;  
                    }
                    if ( strtolower($row[53])=="assente" ) {
                        $nuovoImmobile->Cantina = 2;  
                    }
                }   
                $nuovoImmobile->Portineria = "false";  
                if (isset($row[54]) && trim($row[54]) != '') {
                   if ( trim($row[54])=="true" ) {
                       $nuovoImmobile->Portineria = "true";  
                   }
                }  
                $nuovoImmobile->GiardinoCondominiale = "false";  
                if (isset($row[55]) && trim($row[55]) != '') {
                   if ( trim($row[55])=="true" ) {
                       $nuovoImmobile->GiardinoCondominiale = "true";  
                   }
                }  
                $nuovoImmobile->GiardinoPrivato = 0;  // Non specificato
                if (isset($row[56]) && trim($row[56]) != '') {
                   if ( strtolower($row[56])=="presente" ) {
                        $nuovoImmobile->GiardinoPrivato = 1;  
                    }
                    if ( strtolower($row[56])=="assente" ) {
                        $nuovoImmobile->GiardinoPrivato = 2;  
                    }
                }   
                $nuovoImmobile->AriaCondizionata = 255;  
                if (isset($row[57]) && trim($row[57]) != '') {
                   if ( strtolower($row[57])=="autonomo" ) {
                        $nuovoImmobile->AriaCondizionata = 1;  
                    }
                    if ( strtolower($row[57])=="centralizzato" ) {
                        $nuovoImmobile->AriaCondizionata = 2;  
                    }
                    if ( strtolower($row[57])=="predisposizione impianto" ) {
                        $nuovoImmobile->AriaCondizionata = 3;  
                    }
                }   
                $nuovoImmobile->Riscaldamento = 255;  
                if (isset($row[58]) && trim($row[58]) != '') {
                   if ( strtolower($row[58])=="autonomo" ) {
                        $nuovoImmobile->Riscaldamento = 1;  
                    }
                    if ( strtolower($row[58])=="centralizzato" ) {
                        $nuovoImmobile->Riscaldamento = 2;  
                    }
                }    
                $nuovoImmobile->TipoImpiantoRiscaldamento = 0;  
                if (isset($row[59]) && trim($row[59]) != '') {
                   if ( strtolower($row[59])=="a radiatori" ) {
                        $nuovoImmobile->TipoImpiantoRiscaldamento = 1;  
                    }
                    if ( strtolower($row[59])=="a pavimento" ) {
                        $nuovoImmobile->TipoImpiantoRiscaldamento = 2;  
                    }
                    if ( strtolower($row[59])=="ad aria" ) {
                        $nuovoImmobile->TipoImpiantoRiscaldamento = 3;  
                    }
                    if ( strtolower($row[59])=="a stufa" ) {
                        $nuovoImmobile->TipoImpiantoRiscaldamento = 4;  
                    }
                }    
                $nuovoImmobile->TipoRiscaldamento = 255;  
                if (isset($row[60]) && trim($row[60]) != '') {
                   if ( strtolower($row[60])=="metano" ) {
                        $nuovoImmobile->TipoRiscaldamento = 1;  
                    }
                    if ( strtolower($row[60])=="gasolio" ) {
                        $nuovoImmobile->TipoRiscaldamento = 2;  
                    }
                    if ( strtolower($row[60])=="gpl" ) {
                        $nuovoImmobile->TipoRiscaldamento = 3;  
                    }
                    if ( strtolower($row[60])=="pannelli" ) {
                        $nuovoImmobile->TipoRiscaldamento = 4;  
                    }
                    if ( strtolower($row[60])=="aria" ) {
                        $nuovoImmobile->TipoRiscaldamento = 5;  
                    }
                    if ( strtolower($row[60])=="gas" ) {
                        $nuovoImmobile->TipoRiscaldamento = 6;  
                    }
                    if ( strtolower($row[60])=="pellet" ) {
                        $nuovoImmobile->TipoRiscaldamento = 7;  
                    }
                    if ( strtolower($row[60])=="legna" ) {
                        $nuovoImmobile->TipoRiscaldamento = 8;  
                    }
                    if ( strtolower($row[60])=="solare" ) {
                        $nuovoImmobile->TipoRiscaldamento = 9;  
                    }
                    if ( strtolower($row[60])=="fotovoltaico" ) {
                        $nuovoImmobile->TipoRiscaldamento = 10;  
                    }
                    if ( strtolower($row[60])=="teleriscaldamento" ) {
                        $nuovoImmobile->TipoRiscaldamento = 11;  
                    }
                    if ( strtolower($row[60])=="pompa di calore" ) {
                        $nuovoImmobile->TipoRiscaldamento = 12;  
                    }
                } 
                $nuovoImmobile->SpeseRiscaldamento = 0;  
                if (isset($row[610]) && trim($row[61]) != '') {
                    $nuovoImmobile->SpeseRiscaldamento = str_replace(",",".",$row[61]);
                }
                $nuovoImmobile->Allarme = "false";  
                if (isset($row[62]) && trim($row[62]) != '') {
                   if ( trim($row[62])=="true" ) {
                       $nuovoImmobile->Allarme = "true";  
                   }
                }  
                $nuovoImmobile->Piscina = "false";  
                if (isset($row[63]) && trim($row[63]) != '') {
                   if ( trim($row[63])=="true" ) {
                       $nuovoImmobile->Piscina = "true";  
                   }
                }   
                $nuovoImmobile->Tennis = "false";  
                if (isset($row[64]) && trim($row[64]) != '') {
                   if ( trim($row[64])=="true" ) {
                       $nuovoImmobile->Tennis = "true";  
                   }
                }  
                $nuovoImmobile->Caminetto = "false";  
                if (isset($row[65]) && trim($row[65]) != '') {
                   if ( trim($row[65])=="true" ) {
                       $nuovoImmobile->Caminetto = "true";  
                   }
                }   
                $nuovoImmobile->Idromassaggio = "false";  
                if (isset($row[66]) && trim($row[66]) != '') {
                   if ( trim($row[66])=="true" ) {
                       $nuovoImmobile->Idromassaggio = "true";  
                   }
                }    
                $nuovoImmobile->VideoCitofono = "false";  
                if (isset($row[67]) && trim($row[67]) != '') {
                   if ( trim($row[67])=="true" ) {
                       $nuovoImmobile->VideoCitofono = "true";  
                   }
                }    
                $nuovoImmobile->FibraOttica = "false";  
                if (isset($row[68]) && trim($row[68]) != '') {
                   if ( trim($row[68])=="true" ) {
                       $nuovoImmobile->FibraOttica = "true";  
                   }
                }    
                $nuovoImmobile->ClasseEnergetica = "G"; 
                
//                $nuovoImmobile->immagine_URL = "http://www.flussiaste.com/flussi/public/uploads/flussiaste-default.png";
//                $nuovoImmobile->IDImmagine = 0;
//                $nuovoImmobile->immagine_DataModifica = substr($nuovoImmobile->DataInserimento_d,0,10).'T'.substr($nuovoImmobile->DataInserimento_d,11,19);
//                if (isset($row[69]) && trim($row[69]) != '' && substr($row[68],0,4)=="http" ) {
//                   $nuovoImmobile->immagine_URL = $row[69];
//                }


                // ======================================================IMMAGINI
                $nuovoImmobile->immagine_URL = "http://www.flussiaste.com/flussi/public/uploads/flussiaste-default.png";
                $nuovoImmobile->IDImmagine = 0;
                $nuovoImmobile->immagine_DataModifica = substr($nuovoImmobile->DataInserimento_d,0,10).'T'.substr($nuovoImmobile->DataInserimento_d,11,19);
                if (isset($row[69]) && trim($row[69]) != '') {
                    $arrImg = array();
                    $arrImg = explode("~", $row[69]);
                    $indiceImg = 0;
                    foreach ($arrImg as $img ){
                        if (isset($img) && trim($img) != '' && substr(trim($img),0,4)=="http" ) {
                            ++$indiceImg;
                            if ($indiceImg==1) {
                                $nuovoImmobile->immagine_URL = $img;
                            }
                        }
                    }
                }

                
                 // ======================================================ALTRI BENI
                $nuovoImmobile->altriBeni1 = "";  
                if (isset($row[70]) && trim($row[70]) != '') {
                    $nuovoImmobile->altriBeni1 = $this->sostituisci_carattere($a,$b,$numero,$row[70]);
                    $nuovoImmobile->altriBeni1 = $this->sanitizeXML($nuovoImmobile->altriBeni1);
                    $nuovoImmobile->altriBeni1 = substr($nuovoImmobile->altriBeni1,0,990) . "...";
                }     
                $nuovoImmobile->altriBeni2 = "";  
                if (isset($row[71]) && trim($row[71]) != '') {
                    $nuovoImmobile->altriBeni2 = $this->sostituisci_carattere($a,$b,$numero,$row[71]);
                    $nuovoImmobile->altriBeni2 = $this->sanitizeXML($nuovoImmobile->altriBeni2);
                    $nuovoImmobile->altriBeni2 = substr($nuovoImmobile->altriBeni2,0,990) . "...";
                }      
                $nuovoImmobile->altriBeni3 = "";  
                if (isset($row[72]) && trim($row[72]) != '') {
                    $nuovoImmobile->altriBeni3 = $this->sostituisci_carattere($a,$b,$numero,$row[72]);
                    $nuovoImmobile->altriBeni3 = $this->sanitizeXML($nuovoImmobile->altriBeni3);
                    $nuovoImmobile->altriBeni3 = substr($nuovoImmobile->altriBeni3,0,990) . "...";
                }       
                $nuovoImmobile->altriBeni4 = "";  
                if (isset($row[73]) && trim($row[73]) != '') {
                    $nuovoImmobile->altriBeni4 = $this->sostituisci_carattere($a,$b,$numero,$row[73]);
                    $nuovoImmobile->altriBeni4 = $this->sanitizeXML($nuovoImmobile->altriBeni4);
                    $nuovoImmobile->altriBeni4 = substr($nuovoImmobile->altriBeni4,0,990) . "...";
                }         
                $nuovoImmobile->altriBeni5 = "";  
                if (isset($row[74]) && trim($row[74]) != '') {
                    $nuovoImmobile->altriBeni5 = $this->sostituisci_carattere($a,$b,$numero,$row[74]);
                    $nuovoImmobile->altriBeni5 = $this->sanitizeXML($nuovoImmobile->altriBeni5);
                    $nuovoImmobile->altriBeni5 = substr($nuovoImmobile->altriBeni5,0,990) . "...";
                }          
                $nuovoImmobile->altriBeni6 = "";  
                if (isset($row[75]) && trim($row[75]) != '') {
                    $nuovoImmobile->altriBeni6 = $this->sostituisci_carattere($a,$b,$numero,$row[75]);
                    $nuovoImmobile->altriBeni6 = $this->sanitizeXML($nuovoImmobile->altriBeni6);
                    $nuovoImmobile->altriBeni6 = substr($nuovoImmobile->altriBeni6,0,990) . "...";
                }             
                $nuovoImmobile->altriBeni7 = "";  
                if (isset($row[76]) && trim($row[76]) != '') {
                    $nuovoImmobile->altriBeni7 = $this->sostituisci_carattere($a,$b,$numero,$row[76]);
                    $nuovoImmobile->altriBeni7 = $this->sanitizeXML($nuovoImmobile->altriBeni7);
                    $nuovoImmobile->altriBeni7 = substr($nuovoImmobile->altriBeni7,0,990) . "...";
                }

                // Aggiungi ALTRI BENI AL TESTO (Descrizione)
                if ($nuovoImmobile->altriBeni1 != '') {
                    $nuovoImmobile->Testo .= ' BENE AGGIUNTIVO: ' .$nuovoImmobile->altriBeni1;
                }
                if ($nuovoImmobile->altriBeni2 != '') {
                    $nuovoImmobile->Testo .= ' BENE AGGIUNTIVO: ' .$nuovoImmobile->altriBeni2;
                }
                if ($nuovoImmobile->altriBeni3 != '') {
                    $nuovoImmobile->Testo .= ' BENE AGGIUNTIVO: ' .$nuovoImmobile->altriBeni3;
                }
                if ($nuovoImmobile->altriBeni4 != '') {
                    $nuovoImmobile->Testo .= ' BENE AGGIUNTIVO: ' .$nuovoImmobile->altriBeni4;
                }
                if ($nuovoImmobile->altriBeni5 != '') {
                    $nuovoImmobile->Testo .= ' BENE AGGIUNTIVO: ' .$nuovoImmobile->altriBeni5;
                }
                if ($nuovoImmobile->altriBeni6 != '') {
                    $nuovoImmobile->Testo .= ' BENE AGGIUNTIVO: ' .$nuovoImmobile->altriBeni6;
                }
                if ($nuovoImmobile->altriBeni7 != '') {
                    $nuovoImmobile->Testo .= ' BENE AGGIUNTIVO: ' .$nuovoImmobile->altriBeni7;
                }
                if (strlen($nuovoImmobile->Testo)>2900) {
                    $nuovoImmobile->Testo = substr($nuovoImmobile->Testo,0,2900)."...";
                }



                // ======================================================VALUTA SE IMMOBILE PRESENTE
                // Set valori per aggiornamenti
                if ($nuovoImmobile->rge!="" && $nuovoImmobile->lotto!="" 
                        && $nuovoImmobile->codiceComuneTribunale!="" && $nuovoImmobile->CodiceComune!="" ) {
                    if (sizeof($arrAsteList)>0) {
                        foreach ($arrAsteList as $imm) {
                            if ($imm["rge"]==$nuovoImmobile->rge 
                                    && $imm["lotto"]==$nuovoImmobile->lotto
                                    && $imm["codiceComuneTribunale"]==$nuovoImmobile->codiceComuneTribunale 
                                    && $imm["CodiceComune"]==$nuovoImmobile->CodiceComune  ) {
                                $nuovoImmobile->isNew = false;
                                $nuovoImmobile->uploadType = "Aggiornamento";
                                $nuovoImmobile->id = $imm["id"];
                                // Set val per update successivo
                                if ($imm["Latitudine"]=="" || $imm["Latitudine"]==NULL) {
                                    $isLat = TRUE;
                                }
                                if ($imm["Longitudine"]=="" || $imm["Longitudine"]==NULL) {
                                    $isLon = TRUE;
                                }
                            }
                        }
                    }
                }

                // ====================================================== COORDINATE
                // TROVA COORDINATE
    //            if ($nuovoImmobile->Latitudine=="" || $nuovoImmobile->Longitudine=="" ) {
    //                if ($nuovoImmobile->uploadValido && $nuovoImmobile->idComune!=0 
    //                        && $nuovoImmobile->siglaProv!="" 
    //                        && $nuovoImmobile->indirizzo!=""
    //                        && $nuovoImmobile->isNew) {
    //                    $address = $nuovoImmobile->nomeComune.' ('.$nuovoImmobile->siglaProv.')'.', '.$nuovoImmobile->indirizzo.', Italy';
    //                    // echo '<br>indirizzo: '.$address;
    //                    $functions = new Functions($db);
    //                    $coordinate = $functions->getLatLonFromAddress($address);
    //                    $arrCoo = explode(",", $coordinate);
    //                    $nuovoImmobile->lat  = $arrCoo[0];
    //                    $nuovoImmobile->lon  = $arrCoo[1];
    //                    if (!isset($nuovoImmobile->lat) || trim($nuovoImmobile->lat) === ''
    //                        || !isset($nuovoImmobile->lon) || trim($nuovoImmobile->lon) === '' ) {
    //                        $nuovoImmobile->lat  = "";
    //                        $nuovoImmobile->lon  = "";
    //                    }  
    //                }
    //            }


                // ======================================================INSERT O UPDATE
                if ($tipoElaborazione=='test') {
                    
                } else {
                    if ($nuovoImmobile->uploadValido) {
                        if ($nuovoImmobile->isNew) {
                            // CONTROLLO NUOVAMENTE SE E' STATO INSERITO DA QUESTA IMPORTAZIONE
                            if ((is_array($arrAsteList) || is_object($arrAsteList)) && sizeof($arrAsteList)>0) {
                                foreach ($arrAsteList as $imm) {
                                    if ($imm["rge"]==$nuovoImmobile->rge 
                                            && $imm["lotto"]==$nuovoImmobile->lotto
                                            && $imm["CodiceComune"]==$nuovoImmobile->CodiceComune 
                                            && $imm["codiceComuneTribunale"]==$nuovoImmobile->codiceComuneTribunale  ) {
                                        $nuovoImmobile->isNew = false;
                                    }
                                }
                            }

                                // Set values
                                $data = array(
                                    ':idImport' => $nuovoImmobile->idImport,
                                    ':ComuneTribunale' => $nuovoImmobile->ComuneTribunale,
                                    ':SiglaProvTribunale' => $nuovoImmobile->SiglaProvTribunale,
                                    ':codiceComuneTribunale' => $nuovoImmobile->codiceComuneTribunale,
                                    ':linkTribunale' => $nuovoImmobile->linkTribunale,
                                    ':rge' => $nuovoImmobile->rge,
                                    ':lotto' => $nuovoImmobile->lotto,
                                    ':tipoProcedura' => $nuovoImmobile->tipoProcedura,
                                    ':rito' => $nuovoImmobile->rito,
                                    ':giudice' => $nuovoImmobile->giudice,
                                    ':delegato' => $nuovoImmobile->delegato,
                                    ':custode' => $nuovoImmobile->custode,
                                    ':curatore' => $nuovoImmobile->curatore,
                                    ':valorePerizia' => $nuovoImmobile->valorePerizia,
                                    ':dataPubblicazione' => $nuovoImmobile->dataPubblicazione,
                                    ':noteGeneriche' => "",
                                    ':datiCatastali' => $nuovoImmobile->datiCatastali,
                                    // ':disponibilita' => "", (default "")
                                    ':importoBaseAsta' => $nuovoImmobile->importoBaseAsta,
                                    ':importoOffertaMinima' => $nuovoImmobile->importoOffertaMinima,
                                    ':noteAggiuntive' => $nuovoImmobile->noteAggiuntive,
                                    ':dataAsta' => $nuovoImmobile->dataAsta,
                                    ':linkAllegati' => $nuovoImmobile->linkAllegati,
                                    // ':CodiceNazione' => $nuovoImmobile->CodiceNazione, (default: "IT")
                                    ':CodiceComune' => $nuovoImmobile->CodiceComune,
                                    ':Comune' => $nuovoImmobile->Comune,
                                    ':Provincia' => $nuovoImmobile->Provincia,
                                    ':ComuneProvinciaCompleto' => $nuovoImmobile->ComuneProvinciaCompleto,
                                    // ':CodiceQuartiere' => $nuovoImmobile->CodiceQuartiere,
                                    // ':CodiceLocalita' => $nuovoImmobile->CodiceLocalita,
                                    ':Strada' => $nuovoImmobile->Strada,
                                    ':Strada_testo' => $nuovoImmobile->Strada_testo,
                                    ':Indirizzo' => $nuovoImmobile->Indirizzo,
                                    ':Civico' => $nuovoImmobile->Civico,
                                    ':PubblicaCivico' => $nuovoImmobile->PubblicaCivico,
                                    ':Cap' => $nuovoImmobile->Cap,
                                    // ':PubblicaIndirizzo' => $nuovoImmobile->PubblicaIndirizzo, (default: true)
                                    ':Latitudine' => $nuovoImmobile->Latitudine,
                                    ':Longitudine' => $nuovoImmobile->Longitudine,
                                    // ':PubblicaMappa' => $nuovoImmobile->PubblicaMappa, (default: true)
                                    // ':Contratto' => $nuovoImmobile->Contratto,  (default: V)
                                    // ':DurataContratto' => $nuovoImmobile->DurataContratto, (default: 255)
                                    // ':Categoria' => $nuovoImmobile->Categoria, (default: 1 - Immobili Residenziali)
                                    // ':IDTipologia' => $nuovoImmobile->IDTipologia, (default: 1 - Appartamento)
                                    ':NrLocali' => $nuovoImmobile->NrLocali,
                                    // ':TrattativaRiservata' => $nuovoImmobile->TrattativaRiservata, (default: false)
                                    ':MQSuperficie' => $nuovoImmobile->MQSuperficie,
                                    // ':TipoProprieta' => $nuovoImmobile->TipoProprieta, (default: 1 - intera proprietà)
                                    // ':Asta' => $nuovoImmobile->Asta, (default: true)
                                    // ':Pregio' => $nuovoImmobile->Pregio, (default: false)
                                    ':SpeseMensili' => $nuovoImmobile->SpeseMensili,
                                    ':URLPlanimetria' => $nuovoImmobile->URLPlanimetria,
                                    ':URLVirtualTour' => $nuovoImmobile->URLVirtualTour,
                                    ':URLVideo' => $nuovoImmobile->URLVideo,
                                    ':DataInserimento' => $nuovoImmobile->DataInserimento,
                                    ':DataInserimento_d' => $nuovoImmobile->DataInserimento_d,
                                    ':DataModifica' => $nuovoImmobile->DataModifica,
                                    ':DataModifica_d' => $nuovoImmobile->DataModifica_d,
                                    ':ClasseCatastale' => $nuovoImmobile->ClasseCatastale,
                                    ':RenditaCatastale' => $nuovoImmobile->RenditaCatastale,
                                    // ':Collaborazioni' => $nuovoImmobile->Collaborazioni, (default: false)
                                    // ':Descrizioni_Lingua' => $nuovoImmobile->Descrizioni_Lingua, (default: IT)
                                    ':Titolo' => $nuovoImmobile->Titolo,
                                    ':Testo' => $nuovoImmobile->Testo,
                                    ':TestoBreve' => $nuovoImmobile->TestoBreve,
                                    // ':StatoImmobile' => $nuovoImmobile->StatoImmobile, (default: 2 - libero al rogito)
                                    ':Piano' => $nuovoImmobile->Piano,
                                    ':PianoFuoriTerra' => $nuovoImmobile->PianoFuoriTerra,
                                    ':PianiEdificio' => $nuovoImmobile->PianiEdificio,
                                    ':NrCamereLetto' => $nuovoImmobile->NrCamereLetto,
                                    ':NrAltreCamere' => $nuovoImmobile->NrAltreCamere,
                                    ':NrBagni' => $nuovoImmobile->NrBagni,
                                    ':Cucina' => $nuovoImmobile->Cucina,
                                    ':NrTerrazzi' => $nuovoImmobile->NrTerrazzi,
                                    ':NrBalconi' => $nuovoImmobile->NrBalconi,
                                    ':Ascensore' => $nuovoImmobile->Ascensore,
                                    ':NrAscensori' => $nuovoImmobile->NrAscensori,
                                    ':BoxAuto' => $nuovoImmobile->BoxAuto,
                                    ':BoxIncluso' => $nuovoImmobile->BoxIncluso,
                                    ':NrBox' => $nuovoImmobile->NrBox,
                                    ':NrPostiAuto' => $nuovoImmobile->NrPostiAuto,
                                    ':Cantina' => $nuovoImmobile->Cantina,
                                    ':Portineria' => $nuovoImmobile->Portineria,
                                    ':GiardinoCondominiale' => $nuovoImmobile->GiardinoCondominiale,
                                    ':GiardinoPrivato' => $nuovoImmobile->GiardinoPrivato,
                                    ':AriaCondizionata' => $nuovoImmobile->AriaCondizionata,
                                    ':Riscaldamento' => $nuovoImmobile->Riscaldamento,
                                    ':TipoImpiantoRiscaldamento' => $nuovoImmobile->TipoImpiantoRiscaldamento,
                                    ':TipoRiscaldamento' => $nuovoImmobile->TipoRiscaldamento,
                                    ':SpeseRiscaldamento' => $nuovoImmobile->SpeseRiscaldamento,
                                    // ':Arredamento' => $nuovoImmobile->Arredamento, (default: 255 - assente)
                                    // ':StatoArredamento' => $nuovoImmobile->StatoArredamento, (default: 4 - buono)
                                    // ':AnnoCostruzione' => $nuovoImmobile->AnnoCostruzione, (default: 0)
                                    ':TipoCostruzione' => 3, // $nuovoImmobile->TipoCostruzione, // (default: 3 - Medio Signorile)
                                    // ':StatoCostruzione' => $nuovoImmobile->StatoCostruzione, (default: 4 - buono)
                                    ':Allarme' => $nuovoImmobile->Allarme,
                                    ':Piscina' => $nuovoImmobile->Piscina,
                                    ':Tennis' => $nuovoImmobile->Tennis,
                                    ':Caminetto' => $nuovoImmobile->Caminetto,
                                    ':Idromassaggio' => $nuovoImmobile->Idromassaggio,
                                    ':VideoCitofono' => $nuovoImmobile->VideoCitofono,
                                    ':FibraOttica' => $nuovoImmobile->FibraOttica,
                                    ':ClasseEnergetica' => $nuovoImmobile->ClasseEnergetica, // (di default G)
                                    ':IndicePrestazioneEnergetica' => 0,// $nuovoImmobile->IndicePrestazioneEnergetica, // (default: 0)
                                    // ':EsenteClasseEnergetica' => $nuovoImmobile->EsenteClasseEnergetica, (default: 0)
                                    // ':Energia' => $nuovoImmobile->Energia,  (default: "")
                                    ':IDImmagine' => $nuovoImmobile->IDImmagine,
                                    ':immagine_URL' => $nuovoImmobile->immagine_URL,
                                    ':immagine_DataModifica' => $nuovoImmobile->immagine_DataModifica,
                                    // ':immagine_Posizione' => $nuovoImmobile->immagine_Posizione, (default: 0)
                                    // ':immagine_TipoFoto' => $nuovoImmobile->immagine_TipoFoto,(default: F)
                                    // ':immagine_Titolo' => $nuovoImmobile->immagine_Titolo, (default: "")
                                    ':status' => $nuovoImmobile->status,
                                    ':adminNote' => "",
                                    ':errorsText' => $nuovoImmobile->errorsText,
                                    ':altriBeni1' => $nuovoImmobile->altriBeni1,
                                    ':altriBeni2' => $nuovoImmobile->altriBeni2,
                                    ':altriBeni3' => $nuovoImmobile->altriBeni3,
                                    ':altriBeni4' => $nuovoImmobile->altriBeni4,
                                    ':altriBeni5' => $nuovoImmobile->altriBeni5,
                                    ':altriBeni6' => $nuovoImmobile->altriBeni6,
                                    ':altriBeni7' => $nuovoImmobile->altriBeni7
                                );
                                
                                // INSERT ASTA
                                $astaModel = new Aste_Model();
                                $idRecord = $astaModel->create($data);
                                // echo '<br>idRecord'.$idRecord;

                                // Inserisci IMPORT DETAIL
                                $data2 = array(
                                    ':idImport' => $nuovoImmobile->idImport,
                                    ':idAsta' => $idRecord,
                                    ':uploadType' => $nuovoImmobile->uploadType,
                                    ':uploadValido' => $nuovoImmobile->uploadValido,
                                    ':uploadErrorsTxt' => $nuovoImmobile->uploadErrorsTxt
                                );
                                // INSERT
                                $importDetModel = new ImportDetails_Model();
                                $idImportDetail = $importDetModel->create($data2);
                                
                                
                                // ==================================== INSERT REL_AGENCY_ASTE
                                if (is_array($agencyList) || is_object($agencyList)) {
                                    foreach($agencyList as $agency) {
                                        
                                        // Set Immagine Agenzia
                                        $immagineAgenzia = $nuovoImmobile->immagine_URL;
                                        if ( $nuovoImmobile->immagine_URL=="http://www.flussiaste.com/flussi/public/uploads/flussiaste-default.png") {
                                            if ( $agency["URLImmagine"]!="" && $agency["URLImmagine"]!=NULL) {
                                                $immagineAgenzia = $agency["URLImmagine"];
                                            }
                                        }
                                        
                                        // Inserisci Nuova REL 
                                        $data3 = array(
                                            ':idAsta' => $idRecord,
                                            ':idAgenzia' => $agency["id"],
                                            ':riferimentoAnnuncio' => "",
                                            ':descrizione' => $nuovoImmobile->Testo,
                                            ':flagPubblicita' => $agency["prefFlagPubblicita"],
                                            ':nomeAgente' => "",
                                            ':noteAgenzia' => "",
                                            ':commentiAgenzia' => "",
                                            ':preferenzaPrezzo' => $agency["prefFlagPrezzo"],
                                            ':prezzoPersonalizzato' => 0,
                                            ':flagRichiestaVisione' => "false",
                                            ':dataRichiestaVisione' =>  date("Y-d-d H:i:s"),
                                            ':immagine_URL' => $immagineAgenzia,
                                            ':statusImportazione' => "non importato",
                                            ':dataUltimaEsportazione_d' => date("Y-d-d H:i:s"),
                                            ':dataUltimaEsportazione' => "",
                                            ':DataModifica_d' => $nuovoImmobile->DataModifica_d,
                                            ':DataModifica' => $nuovoImmobile->DataModifica
                                        );
                                        // INSERT
                                        $relModel = new RelAsteAgenzie_Model();
                                        $idRel = $relModel->create($data3);
                                    }
                                }
                                // ====================================


                                // ==================================== INSERT IMMAGINI
                                if (isset($row[69]) && trim($row[69]) != '') {
                                    $arrImg = array();
                                    $arrImg = explode("~", $row[69]);
                                    $indiceImg = 0;
                                    foreach ($arrImg as $img ){
                                        if (isset($img) && trim($img) != '' && substr(trim($img),0,4)=="http" ) {
                                            ++$indiceImg ;
                                            $dataModifica = date("Y-m-d H:i:s");
                                            $data3 = array(
                                                ':idAsta' => $idRecord,
                                                ':idAgenzia' => 0,
                                                ':fonte' => "csv",
                                                ':IDImmagine' => 0,
                                                ':immagine_URL' => $img,
                                                ':immagine_Posizione' => $indiceImg-1,
                                                ':immagine_TipoFoto' => "F",
                                                ':immagine_Titolo' => "",
                                                ':dataModifica_d' => $dataModifica,
                                                ':dataModifica' => substr($dataModifica,0,10).'T'.substr($dataModifica,11,19)
                                            );

                                            // Add
                                            $relImgModel = new RelAsteImg_Model();
                                            $idRecordImg = $relImgModel->create($data3);
                                        }
                                    }
                                } else {
                                    // Inserisci Immagine unica
                                    $dataModifica = date("Y-m-d H:i:s");
                                    $data3 = array(
                                        ':idAsta' => $idRecord,
                                        ':idAgenzia' => 0,
                                        ':fonte' => "csv",
                                        ':IDImmagine' => 0,
                                        ':immagine_URL' => $immagineAgenzia,
                                        ':immagine_Posizione' => 0,
                                        ':immagine_TipoFoto' => "F",
                                        ':immagine_Titolo' => "",
                                        ':dataModifica_d' => $dataModifica,
                                        ':dataModifica' => substr($dataModifica,0,10).'T'.substr($dataModifica,11,19)
                                    );

                                    // Add
                                    $relImgModel = new RelAsteImg_Model();
                                    $idRecordImg = $relImgModel->create($data3);
                                }
                                // ====================================

                        } else {
                            // UPDATE IMMOBILE GIA' PRESENTE
                            // Set Data
                            $data = array(
                                ':idImport' => $nuovoImmobile->idImport,
                                ':linkTribunale' => $nuovoImmobile->linkTribunale,
                                ':tipoProcedura' => $nuovoImmobile->tipoProcedura,
                                ':rito' => $nuovoImmobile->rito,
                                ':giudice' => $nuovoImmobile->giudice,
                                ':delegato' => $nuovoImmobile->delegato,
                                ':custode' => $nuovoImmobile->custode,
                                ':curatore' => $nuovoImmobile->curatore,
                                ':valorePerizia' => $nuovoImmobile->valorePerizia,
                                ':dataPubblicazione' => $nuovoImmobile->dataPubblicazione,
                                ':datiCatastali' => $nuovoImmobile->datiCatastali,
                                ':importoBaseAsta' => $nuovoImmobile->importoBaseAsta,
                                ':importoOffertaMinima' => $nuovoImmobile->importoOffertaMinima,
                                // ':noteAggiuntive' => $nuovoImmobile->noteAggiuntive,
                                ':dataAsta' => $nuovoImmobile->dataAsta,
                                ':linkAllegati' => $nuovoImmobile->linkAllegati,
                                // ':NrLocali' => $nuovoImmobile->NrLocali,
                                // ':MQSuperficie' => $nuovoImmobile->MQSuperficie,
                                // ':SpeseMensili' => $nuovoImmobile->SpeseMensili,
                                //':URLPlanimetria' => $nuovoImmobile->URLPlanimetria,
                                //':URLVirtualTour' => $nuovoImmobile->URLVirtualTour,
                                //':URLVideo' => $nuovoImmobile->URLVideo,
                                ':DataModifica' => $nuovoImmobile->DataModifica,
                                ':DataModifica_d' => $nuovoImmobile->DataModifica_d,
                                // ':ClasseCatastale' => $nuovoImmobile->ClasseCatastale,
                                // ':RenditaCatastale' => $nuovoImmobile->RenditaCatastale,
                                //':Titolo' => $nuovoImmobile->Titolo,
                                //':Testo' => $nuovoImmobile->Testo,
                                //':TestoBreve' => $nuovoImmobile->TestoBreve,
                                // ':Piano' => $nuovoImmobile->Piano,
                                // ':PianoFuoriTerra' => $nuovoImmobile->PianoFuoriTerra,
                                // ':PianiEdificio' => $nuovoImmobile->PianiEdificio,
                                // ':NrCamereLetto' => $nuovoImmobile->NrCamereLetto,
                                // ':NrAltreCamere' => $nuovoImmobile->NrAltreCamere,
                                // ':NrBagni' => $nuovoImmobile->NrBagni,
                                // ':Cucina' => $nuovoImmobile->Cucina,
                                // ':NrTerrazzi' => $nuovoImmobile->NrTerrazzi,
                                // ':NrBalconi' => $nuovoImmobile->NrBalconi,
                                // ':Ascensore' => $nuovoImmobile->Ascensore,
                                // ':NrAscensori' => $nuovoImmobile->NrAscensori,
                                // ':BoxAuto' => $nuovoImmobile->BoxAuto,
                                // ':BoxIncluso' => $nuovoImmobile->BoxIncluso,
                                // ':NrBox' => $nuovoImmobile->NrBox,
                                // ':NrPostiAuto' => $nuovoImmobile->NrPostiAuto,
                                // ':Cantina' => $nuovoImmobile->Cantina,
                                // ':Portineria' => $nuovoImmobile->Portineria,
                                // ':GiardinoCondominiale' => $nuovoImmobile->GiardinoCondominiale,
                                // ':GiardinoPrivato' => $nuovoImmobile->GiardinoPrivato,
                                // ':AriaCondizionata' => $nuovoImmobile->AriaCondizionata,
                                // ':Riscaldamento' => $nuovoImmobile->Riscaldamento,
                                // ':TipoImpiantoRiscaldamento' => $nuovoImmobile->TipoImpiantoRiscaldamento,
                                // ':TipoRiscaldamento' => $nuovoImmobile->TipoRiscaldamento,
                                // ':SpeseRiscaldamento' => $nuovoImmobile->SpeseRiscaldamento,
                                // ':Allarme' => $nuovoImmobile->Allarme,
                                // ':Piscina' => $nuovoImmobile->Piscina,
                                // ':Tennis' => $nuovoImmobile->Tennis,
                                // ':Caminetto' => $nuovoImmobile->Caminetto,
                                // ':Idromassaggio' => $nuovoImmobile->Idromassaggio,
                                // ':VideoCitofono' => $nuovoImmobile->VideoCitofono,
                                // ':FibraOttica' => $nuovoImmobile->FibraOttica,
                                // ':ClasseEnergetica' => $nuovoImmobile->ClasseEnergetica,
                                //':IDImmagine' => $nuovoImmobile->IDImmagine,
                                //':immagine_URL' => $nuovoImmobile->immagine_URL,
                                //':immagine_DataModifica' => $nuovoImmobile->immagine_DataModifica,
                                ':status' => $nuovoImmobile->status,
                                //':altriBeni1' => $nuovoImmobile->altriBeni1,
                                //':altriBeni2' => $nuovoImmobile->altriBeni2,
                                //':altriBeni3' => $nuovoImmobile->altriBeni3,
                                //':altriBeni4' => $nuovoImmobile->altriBeni4,
                                //':altriBeni5' => $nuovoImmobile->altriBeni5,
                                //':altriBeni6' => $nuovoImmobile->altriBeni6,
                                //':altriBeni7' => $nuovoImmobile->altriBeni7
                            );
                            $where = " id=:id ";
                            $parameters[":id"] = $nuovoImmobile->id;

                            // Aggiorna SOLO SE dataAstaNuova>dataAsta su DB
                            if ($nuovoImmobile->dataAsta>$imm["dataAsta"]) {
                                // UPDATE
                                $astaModel = new Aste_Model();
                                $astaModel->updateDataFromImport($data,$parameters,$where);
                            }

                            
                            // Inserisci Nuova IMPORT DETAIL
                            $data2 = array(
                                ':idImport' => $nuovoImmobile->idImport,
                                ':idAsta' => $nuovoImmobile->id,
                                ':uploadType' => $nuovoImmobile->uploadType,
                                ':uploadValido' => $nuovoImmobile->uploadValido,
                                ':uploadErrorsTxt' => $nuovoImmobile->uploadErrorsTxt
                            );
                            // INSERT
                            $importDetModel = new ImportDetails_Model();
                            $idImportDetail = $importDetModel->create($data2);
                        }
                    }
                }
                // Add Item
                array_push($arrImmobili, $nuovoImmobile);
            }
        }
//        if ($tipoElaborazione!='test') {
//
//            echo '<br>fine';
//            exit;
//        }
        // Return
        return $arrImmobili;
    }


    function sostituisci_carattere($dasostituire,$sostitutivo,$numero,$stringa) {
        $s=$stringa;
        for ($i=0;$i<$numero;$i++) {
            $s=str_replace(chr($dasostituire[$i]),$sostitutivo[$i],$s);
        }
        return $s;
    }

    // ==========================================================================================
    // RIMUOVI CARATTERI INVALIDI PER UTF-8 XML string
    // ==========================================================================================
    function sanitizeXML($string) {
        if (!empty($string)) 
        {
            $regex = '/(
                [\xC0-\xC1] # Invalid UTF-8 Bytes
                | [\xD9-\xD9] # Invalid UTF-8 Bytes
                | [\xF5-\xFF] # Invalid UTF-8 Bytes
                | \xE0[\x80-\x9F] # Overlong encoding of prior code point
                | \xF0[\x80-\x8F] # Overlong encoding of prior code point
                | [\xC2-\xDF](?![\x80-\xBF]) # Invalid UTF-8 Sequence Start
                | [\xE0-\xEF](?![\x80-\xBF]{2}) # Invalid UTF-8 Sequence Start
                | [\xF0-\xF4](?![\x80-\xBF]{3}) # Invalid UTF-8 Sequence Start
                | (?<=[\x0-\x7F\xF5-\xFF])[\x80-\xBF] # Invalid UTF-8 Sequence Middle
                | (?<![\xC2-\xDF]|[\xE0-\xEF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4]|[\xF0-\xF4][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF]{2})[\x80-\xBF] # Overlong Sequence
                | (?<=[\xE0-\xEF])[\x80-\xBF](?![\x80-\xBF]) # Short 3 byte sequence
                | (?<=[\xF0-\xF4])[\x80-\xBF](?![\x80-\xBF]{2}) # Short 4 byte sequence
                | (?<=[\xF0-\xF4][\x80-\xBF])[\x80-\xBF](?![\x80-\xBF]) # Short 4 byte sequence (2)
            )/x';
            $string = preg_replace($regex, '', $string);

            $result99 = "";
            // $current;
            $length = strlen($string);
            for ($i=0; $i < $length; $i++) {
                $current = ord($string{$i});
                if (($current == 0x9) ||
                    ($current == 0xA) ||
                    ($current == 0xD) ||
                    (($current >= 0x20) && ($current <= 0xD7FF)) ||
                    (($current >= 0xE000) && ($current <= 0xFFFD)) ||
                    (($current >= 0x10000) && ($current <= 0x10FFFF)))  {
                    $result99 .= chr($current);
                } else {
                    // $ret;    // use this to strip invalid character(s)
                    // $ret .= " ";    // use this to replace them with spaces
                }
            }
            $string = $result99;
        }
        return $string;
    }
    
    
    
    function ConvertCodiceIstat($CodiceComune) {
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
    
    
    // ======== SOLO NUMERI
    function solonumeri($valore){
            if (is_numeric($valore)){
                    return true;
            } else {
                    return false; 
            }
     }
 

}




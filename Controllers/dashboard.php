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
        $this->view->mainMenu = Functions::setActiveMenu("dashboard");
        // Get Errors
        $this->view->error = Functions::getError($error);
        
        // Get Data
        if ($this->view->userLogged["role"]=="admin") {
            // Tot. Aste
            $asteModel = new Aste_Model();
            $resAste = $asteModel->getAsteList(NULL,NULL);
            $numAste = 0;
            if (is_array($resAste) || is_object($resAste)) {
                $numAste = sizeof($resAste);
            }
            $this->view->asteList = $resAste;
            $this->view->asteNum = $numAste;
            // Tot. Agenzie
            $agencyModel = new Agency_Model();
            $resAgency = $agencyModel->getUsersListByRole("agency");
            $numAgency = 0;
            if (is_array($resAgency) || is_object($resAgency)) {
                $numAgency = sizeof($resAgency);
            }
            $this->view->agencyList = $resAgency;
            $this->view->agencyNum = $numAgency;
            
            // Tot. Importazioni
            $importsModel = new Imports_Model();
            $resImports = $importsModel->getImportsList();
            $numImports = 0;
            if (is_array($resImports) || is_object($resImports)) {
                $numImports = sizeof($resImports);
            }
            $this->view->importsNum = $numImports;
            
            // Tot. Importazioni
            $exportsModel = new Exports_Model();
            $resExports = $exportsModel->getExportsList();
            $numExports = 0;
            if (is_array($resExports) || is_object($resExports)) {
                $numExports = sizeof($resExports);
            }
            $this->view->exportsNum = $numExports;
            
        } 
        
        
        // View
        $this->view->render('dashboard/index', true, HEADER_MAIN);
    }

    function logout() {
        Session::destroy();
        header('location: ' . URL . 'login');
        exit;
    }

}

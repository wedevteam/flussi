<?php

/*
 * Concesso in licenza d'uso a STAKING SCHOOL
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

/*
 * Controller di start generale
 */
class Index extends Controller {

    function __construct() {
        parent::__construct();
    }

    // Reindirizzamento a login, con background nullo
    public function index() {
        Functions::redirectToAction('login/index');
    }

}

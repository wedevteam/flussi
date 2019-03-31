<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */


/*
 * Gestisce la Session per tutta la piattaforma
 */

class Session {

    function __construct() {
        
    }

    public static function init() {
        @session_start();
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return null;
        }
    }

    public static function destroy() {
        session_destroy();
    }

}

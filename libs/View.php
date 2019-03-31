<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class View {

    function __construct() {
        
    }

    public function render($name, $isToInclude = true, $isCustom = null) {
        if (!$isToInclude) {
            require 'Views/' . $name . '.php';
        } else {
            if ($isCustom == null) {
                require 'Views/headerMain.php';
                require 'Views/' . $name . '.php';
                require 'Views/footerMain.php';
            } else {
                switch ($isCustom) {
                    case HEADER_LOGIN:
                        require 'Views/headerAuth.php';
                        require 'Views/' . $name . '.php';
                        require 'Views/footerAuth.php';
                        break;
                    case HEADER_MAIN:
                        require 'Views/headerMain.php';
                        require 'Views/' . $name . '.php';
                        require 'Views/footerMain.php';
                        break;
                }
            }
        }
    }

}

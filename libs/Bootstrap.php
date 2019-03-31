<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Bootstrap {

    function __construct() {

        // Controller/Action/{args}
        $url = isset($_GET['url']) ? $_GET['url'] : NULL;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        // L'url definisce il controllero come il primo elemento dopo la radice (indice 0)
        // la action il secondo e gli argomenti i successivi
        // Se si scrive solo la radice il controller è nullo e viene richiamata la index generale
        if (empty($url[ROUTE_CONTROLLER])) {
            require 'Controllers/index.php';
            $controller = new Index();
            $controller->index();
            return FALSE;
        }

      
        $file = 'Controllers/' . $url[ROUTE_CONTROLLER] . '.php';
        if (file_exists($file)) {
            require $file;
        } else {
            $this->error();
        }

        $controller = new $url[ROUTE_CONTROLLER];
        $controller->loadModel($url[ROUTE_CONTROLLER]);

        if (isset($url[ROUTE_ARGS])) {
            if (method_exists($controller, $url[ROUTE_ACTION])) {
                $controller->{$url[ROUTE_ACTION]}($url[ROUTE_ARGS]);
            } else {
                $this->error();
            }
        } else {
            if (isset($url[ROUTE_ACTION])) {
                if (method_exists($controller, $url[ROUTE_ACTION])) {
                    $controller->{$url[ROUTE_ACTION]}();
                } else {
                    $this->error();
                }
            } else {
                $controller->index();
            }
        }
    }

    function error() {
        require 'Controllers/error.php';
        $controller = new Error();
        $controller->index();
        return false;
    }

}

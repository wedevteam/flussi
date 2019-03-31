<?php

/* 
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Controller {

    function __construct() {
        include 'constants/const.php';
        $this->view = new View();
        $this->func =new Functions();
    }

    public function loadModel($name){
        $path = 'Models/'.$name.'_model.php';
        if (file_exists($path)){
            require $path;
            $modelName = $name.'_Model';
            $this->model = new $modelName();
        }
    }
}
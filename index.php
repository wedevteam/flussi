<?php

// error_reporting(E_ERROR | E_PARSE);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


error_reporting(E_ERROR | E_PARSE);
error_reporting(0); // Disable all errors.


require 'config/paths.php';
require 'config/database.php';
require 'config/constants.php';


function __autoload($class) {
    require LIBS.$class.".php";
}

$app = new Bootstrap();
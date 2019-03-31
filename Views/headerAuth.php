<!doctype html>
<html class="fixed dark">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Flussi Aste</title>
        <meta name="keywords" content="Flussi Aste" />
        <meta name="description" content="<?php echo $this->platformData['metaDesritpion'] ?>">
        <meta name="author" content="http://www.wedevteam.com">
        <link rel="shortcut icon" type="image/png" href="<?php echo URL;?>public/images/favicon-flussiaste.ico"/>
        
        <!--Favicon-->
        <link rel="icon" href="<?php echo URL ?>public/images/<?php echo $this->model['logo'] ?>" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo URL ?>public/images/<?php echo $this->model['logo'] ?>" type="image/x-icon">
        
        <link href="<?php echo URL_THEME; ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo URL_THEME; ?>font-awesome/css/font-awesome.css" rel="stylesheet">

        <link href="<?php echo URL_THEME; ?>css/animate.css" rel="stylesheet">
        <link href="<?php echo URL_THEME; ?>css/style.css" rel="stylesheet">
        
        <!-- LOAD CSS e JS SPECIFICI DELLA VIEW -->
        <!----------------------------------------->
        <?php
        if (isset($this->js)) {
            foreach ($this->js as $js) {
                echo '<script type="text/javascript" src="' . URL . 'Views/' . $js . '"></script>';
            }
        }
        ?>

    </head>

    
    <?php
    // Inizializzazione della Sessione
    Session::init();
    ?>

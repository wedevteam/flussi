<!doctype html>
<html>
    <head>
        <title>Test</title>

        <!-- LOAD CSS e JS GENERALI -->
        <!---------------------------->

        <!-- CSS di default -->
        <link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css"/>

        <!-- JQUERY (Interno ultima versione al 5/9/2018 -->
        <script type="text/javascript" src="<?php echo URL; ?>public/js/jquery.js"></script>

        <!-- TOOLTIP integrazione bootstrap -->
        <link href="https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js" >

        <!-- POPPER integrazione bootstrap -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" 
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

        <!-- CSS Bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
              integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- Bootstrap -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" 
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        
        <!-- Funzioni Custom JQuery -->
        <script type="text/javascript" src="<?php echo URL; ?>public/js/custom.js"></script>


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

    <body>
        <div id="header">

            <?php if (Session::get('loggedIn') == false): ?>
                <a href="<?php echo URL; ?>index">Index</a>
                <a href="<?php echo URL; ?>help">Help</a>
            <?php endif ?>

            <?php if (Session::get('loggedIn') == true): ?>
                <a href="<?php echo URL; ?>dashboard">Dashboard</a>

                <?php if (Session::get('role') == 'owner'): ?>
                    <a href="<?php echo URL; ?>user/index">Users</a>
                <?php endif ?>

                <a href="<?php echo URL; ?>dashboard/logout">Logout</a>
            <?php else: ?>
                <a href="<?php echo URL; ?>login">Login</a>
            <?php endif ?>


        </div>
        <div id="content">

            <!-- i tag sono chiusi nel footer -->
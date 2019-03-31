<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $this->platformData['siteName'] ?></title>
        <meta name="keywords" content="<?php echo $this->platformData['metaKeywords'] ?>" />
        <meta name="description" content="<?php echo $this->platformData['metaDesritpion'] ?>">
        <meta name="author" content="http://www.wedevteam.com">
        <link rel="shortcut icon" type="image/png" href="<?php echo URL;?>public/images/favicon-flussiaste.ico"/>

        <link href="<?php echo URL_THEME;?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo URL_THEME;?>font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="<?php echo URL_THEME;?>css/animate.css" rel="stylesheet">
        <link href="<?php echo URL_THEME;?>css/style.css" rel="stylesheet">
        
        <!-- FooTable -->
        <link href="<?php echo URL_THEME;?>css/plugins/footable/footable.core.css" rel="stylesheet">
        
        <!--Sweet alert-->
        <link href="<?php echo URL_THEME;?>css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
        
        <!--select2-->
        <link href="<?php echo URL_THEME;?>css/plugins/select2/select2.min.css" rel="stylesheet">
        <!--chosen-->
        <link href="<?php echo URL_THEME;?>css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
        
        
         <?php
        // LOAD LINKS specifici della VIEW
        if (isset($this->includeHeadLinks) && $this->includeHeadLinks!=null ) {
            include(URL_DOCUMENT_ROOT.$this->includeHeadLinks);
        }
        ?>
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

    <body class="top-navigation">
        <div id="wrapper">
            <div id="page-wrapper" class="gray-bg">
                <div class="row border-bottom white-bg">
                    <nav class="navbar navbar-expand-lg navbar-static-top" role="navigation">
                        <?php  if ($this->userLogged["role"]=="admin") { ?>
                            <a href="<?php echo URL ?>dashboard/index" class="navbar-brand" 
                               style="background-color:#FFFFFF;" title="FlussiAste">
                                <img src="<?php echo URL;?>public/images/favicon-flussiaste.png" style="max-width:40px;" >
                            </a>
                        <?php  } else { ?>
                            <a href="<?php echo URL ?>aste/index" class="navbar-brand" 
                               style="background-color:#FFFFFF;" title="FlussiAste">
                                <img src="<?php echo URL;?>public/images/favicon-flussiaste.png" style="max-width:40px;" >
                            </a>
                        <?php  } ?>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa fa-reorder"></i>
                        </button>
                        <div class="navbar-collapse collapse" id="navbar">
                            <ul class="nav navbar-nav mr-auto">
                                <?php  if ($this->userLogged["role"]=="admin") { ?>
                                    <li class=" <?php echo $this->mainMenu["menuDashboard"] ?>">
                                        <a aria-expanded="false" role="button" href="<?php echo URL ?>dashboard/index"> 
                                            Dashboard
                                        </a>
                                    </li>
                                <?php } ?>
                                <li class="<?php echo $this->mainMenu["menuAste"] ?>">
                                    <a aria-expanded="false" role="button" href="<?php echo URL ?>aste/index"> 
                                        Aste
                                    </a>
                                </li>
                                <?php  if ($this->userLogged["role"]=="admin") { ?>
                                    <li class="dropdown <?php echo $this->mainMenu["menuImports"] ?>" >
                                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                                            Importazioni
                                        </a>
                                        <ul role="menu" class="dropdown-menu">
                                            <li><a href="<?php echo URL ?>imports/index">Archivio</a></li>
                                            <li><a href="<?php echo URL ?>imports/create">Nuova</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown <?php echo $this->mainMenu["menuExports"] ?>" >
                                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                                            Esportazioni
                                        </a>
                                        <ul role="menu" class="dropdown-menu">
                                            <li><a href="<?php echo URL ?>exports/index">Archivio</a></li>
                                            <li><a href="<?php echo URL ?>exports/create">Nuova</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown <?php echo $this->mainMenu["menuAgency"] ?>" >
                                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                                            Agenzie
                                        </a>
                                        <ul role="menu" class="dropdown-menu">
                                            <li><a href="<?php echo URL ?>agency/index">Archivio</a></li>
                                            <li><a href="<?php echo URL ?>agency/create">Nuova</a></li>
                                        </ul>
                                    </li>
                                    <li class="<?php echo $this->mainMenu["menuSettings"] ?>">
                                        <a aria-expanded="false" role="button" href="<?php echo URL ?>settings/index"> 
                                            Impostazioni
                                        </a>
                                    </li>
                                <?php } else { ?>
                                    <li class="<?php echo $this->mainMenu["menuSettings"] ?>">
                                        <a aria-expanded="false" role="button" href="<?php echo URL ?>preferenze/index"> 
                                            Preferenze
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                            <ul class="nav navbar-top-links navbar-right">
<!--                                <li>
                                    <a href="<?php //echo URL ?>account/index">
                                        Account
                                    </a>
                                </li>-->
                                <li class="dropdown" <?php echo $this->mainMenu["menuProfile"] ?>>
                                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                                        <img src="<?php echo URL_THEME ?>img/user-avatar.png" class=" circle" style="width:30px;border-radius:50px;"> &nbsp;Profilo 
                                    </a>
                                    <ul role="menu" class="dropdown-menu">
                                        <li><a href="<?php echo URL ?>profile/index">Account</a></li>
<!--                                        <li><a href="<?php //echo URL ?>account/pref">Preferenze</a></li>-->
                                        <li><a href="<?php echo URL ?>login/index">Esci</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                
             <!--------------------
            START PAGE
            --------------------> 





                    
            
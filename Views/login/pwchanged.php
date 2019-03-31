<!-- start: page -->
<body>
    <section class="body-sign">
        <div class="center-sign">
            <div class="panel card-sign">
                <div class="card-title-sign mt-3 text-right">
                    <h2 class="title text-uppercase font-weight-bold m-0">
                        <i class="fas fa-user mr-1"></i> Recupera Password
                    </h2>
                </div>
                <div class="card-body">
                    <form id="loginForm" method="POST" action="<?php echo URL?>login/sendEmailForgot"  class="m-t">
                         <div>
                            <img src="<?php echo URL; ?>public/images/<?php echo $this->platformData["logo"]; ?>" style="width: 100%;">
                        </div>
                        <h3><br>Benvenuto in <?php echo $this->platformData["siteName"]; ?></h3>
            <!--            <p>Tutti i dati delle case all'asta direttamente sul tuo gestionale.</p>-->
                        
                        <div class="text-center alert alert-success">
                            La tua password è stata modificata con successo!
                        </div>
                        
                        <hr>
                        <p>
                            <a href="<?php echo URL; ?>login/index" class="btn btn-flussi-light btn-block">Accedi</a>
                        </p>
                    </form>
                </div>
            </div>

            <p class="text-center mt-3 mb-3 text-white">&copy; Copyright <?php echo date('Y'); ?> <a href="<?php echo $this->model['officialPathHttp']; ?>"><?php echo $this->model['officialPathName']; ?></a> | Tutti i diritti riservati
                <br><span class="text-muted">Powered by <a href="http://www.wedevteam.com" target="_blank" class="text-muted">wedevteam.com</a></span>
            </p>
        </div>
    </section>
    
    

    <!-- Mainly scripts -->
    <!-- Vendor -->
    <script src="<?php echo URL_THEME; ?>vendor/jquery/jquery.js"></script>
    <script src="<?php echo URL_THEME; ?>vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
    <script src="<?php echo URL_THEME; ?>vendor/popper/umd/popper.min.js"></script>
    <script src="<?php echo URL_THEME; ?>vendor/bootstrap/js/bootstrap.js"></script>
    <script src="<?php echo URL_THEME; ?>vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?php echo URL_THEME; ?>vendor/common/common.js"></script>
    <script src="<?php echo URL_THEME; ?>vendor/nanoscroller/nanoscroller.js"></script>
    <script src="<?php echo URL_THEME; ?>vendor/magnific-popup/jquery.magnific-popup.js"></script>
    <script src="<?php echo URL_THEME; ?>vendor/jquery-placeholder/jquery-placeholder.js"></script>
    <!-- Theme Base, Components and Settings -->
    <script src="<?php echo URL_THEME; ?>js/theme.js"></script>
    <!-- Theme Custom -->
    <script src="<?php echo URL_THEME; ?>js/custom.js"></script>
    <!-- Theme Initialization Files -->
    <script src="<?php echo URL_THEME; ?>js/theme.init.js"></script>
</body>
<!-- end: page -->
</html>

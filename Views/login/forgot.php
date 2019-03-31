
<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
             <div>
                <img src="<?php echo URL; ?>public/images/<?php echo $this->platformData["logo"]; ?>" style="width: 100%;">
            </div>
            <h3><br>Benvenuto in <?php echo $this->platformData["siteName"]; ?></h3>
<!--            <p>Tutti i dati delle case all'asta direttamente sul tuo gestionale.</p>-->
            <p class="text-primary">RECUPERA PASSWORD</p>
            <small>Ti verranno inviate via email le istruzioni per recuperare la tua password</small>
            <form class="m-t" role="form" method="POST"  action="<?php echo URL ?>login/sendEmailForgot" >
                <?php if ($this->error) { ?>
                    <div class="col-md-12 text-center">
                        <p class="alert alert-danger"><?php echo $this->error; ?></p>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" required="">
                </div>
                <button type="submit" class="btn btn-flussi-light block full-width m-b">Invia Email</button>

                <a href="<?php echo URL; ?>login/index">
                    <small>Ricordi la Password? Accedi</small>
                </a>
            </form>
            <p class="m-t"> 
                <small>&copy; Copyright <?php echo date('Y'); ?> <a target="_blank" href="<?php echo $this->platformData['officialPathHttp']; ?>"><?php echo $this->platformData['siteName']; ?></a> | Tutti i diritti riservati</small> 
            </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?php echo URL_THEME; ?>js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo URL_THEME; ?>js/popper.min.js"></script>
    <script src="<?php echo URL_THEME; ?>js/bootstrap.js"></script>

</body>





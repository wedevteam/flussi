<!--class="gray-bg"-->
<body  style="background-color:#253645;">

    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-6 text-white">
                <h2 class="font-bold text-center">Benvenuto in  <?php echo $this->platformData["siteName"]; ?></h2>
                <p>
                    Il primo gestionale specializzato per aiutare le Agenzie che trattano le Aste Immobiliari.
                </p>
                <p>
                    Risparmia subito fino al 80% del tuo tempo prezioso.
                </p>
                <p>
                    Fai eseguire in automatico a Flussi Aste il lavoro ripetitivo dell'inserimento degli annunci su Getrix e usa il tuo tempo per le attività che portano più guadagno alla tua Agenzia!
                </p>
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <div>
                        <img src="<?php echo URL; ?>public/images/<?php echo $this->platformData["logo"]; ?>" style="width: 100%;">
                    </div>
                    <form class="m-t" role="form" method="POST"  action="<?php echo URL ?>login/executeLogin" >
                        <?php if ($this->error) { ?>
                            <div class="col-md-12 text-center">
                                <p class="alert alert-danger"><?php echo $this->error; ?></p>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email" required="">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password" required="">
                        </div>
                        <button type="submit" class="btn btn-flussi-light block full-width m-b">Accedi</button>

                        <a href="<?php echo URL; ?>login/forgot">
                            <small>Password dimenticata?</small>
                        </a>
                    </form>

                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                <small>© Copyright <?php echo date('Y'); ?></small>
            </div>
            <div class="col-md-6 text-right">
                <small><a target="_blank" href="<?php echo $this->platformData['officialPathHttp']; ?>"><?php echo $this->platformData['siteName']; ?></a> | Tutti i diritti riservati</small>
            </div>
        </div>
    </div>


    <!-- Mainly scripts -->
    <script src="<?php echo URL_THEME; ?>js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo URL_THEME; ?>js/popper.min.js"></script>
    <script src="<?php echo URL_THEME; ?>js/bootstrap.js"></script>

</body>



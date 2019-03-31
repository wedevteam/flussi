
<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <img src="<?php echo URL; ?>public/images/<?php echo $this->platformData["logo"]; ?>" style="width: 100%;">
            </div>
            <h3><br>Benvenuto in <?php echo $this->platformData["siteName"]; ?></h3>
<!--            <p>Tutti i dati delle case all'asta direttamente sul tuo gestionale.</p>-->
            <p class="text-primary">ACCESSO</p>
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



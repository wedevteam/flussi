
<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-6">

                <h2 class="font-bold text-center">Benvenuto in  <?php echo $this->platformData["siteName"]; ?></h2>
                <p>
                    Tutti i dati delle case all'asta direttamente sul tuo gestionale.
                </p>

                <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                </p>

                <p>
                    When an unknown printer took a galley of type and scrambled it to make a type specimen book.
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



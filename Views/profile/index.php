<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-6">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>Modifica Email </h5>
                    </div>
                    <div class="ibox-content">
                        <form method="POST" action="">
                            <div class="row">
                                <?php if ($this->error) { ?>
                                    <div class="col-md-12 text-center">
                                        <p class="alert alert-danger"><?php echo $this->error;?></p>
                                    </div>
                                <?php }  ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email*</label>
                                        <input type="email" placeholder="Email" class="form-control" disabled
                                               value="<?php echo $this->userLogged["email"] ?>"/>
                                    </div>
                                </div>
<!--                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">
                                            Salva
                                        </button>
                                    </div>
                                </div>-->
                            </div>

                        </form>
                    </div>
                </div>
            </div>

             <div class="col-lg-6">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>Modifica Password </h5>
                    </div>
                    <div class="ibox-content">
                        
                        <form method="POST" action="">
                            <div class="row">
                                <?php if ($this->error) { ?>
                                    <div class="col-md-12 text-center">
                                        <p class="alert alert-danger"><?php echo $this->error;?></p>
                                    </div>
                                <?php }  ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Password attuale*</label>
                                        <input type="password" placeholder="Password attuale" class="form-control" maxlength="20" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nuova Password*</label>
                                        <input type="password" placeholder="Nuova Password" class="form-control" maxlength="20" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Conferma Password*</label>
                                        <input type="password" placeholder="Conferma Password" class="form-control" maxlength="20" />
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <button class="btn btn-flussi-light" type="submit" disabled>
                                            Salva
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            
            
        </div>

    </div>

</div>

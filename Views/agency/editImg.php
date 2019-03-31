<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>
                            Modifica Agenzia  
                            <small>
                                <br>
                                Id# <?php echo $this->data["id"]  ?> | Creazione: <?php echo date("d/m/y", strtotime($this->data["CreatedAt"])) ;?> | Ultima modifica: <?php echo date("d/m/y", strtotime($this->data["lastEdit"])) ;?>
                            </small>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="col-lg-12">
                            <div class="tabs-container">
                                <div class="tabs-left">
                                    <ul class="nav nav-tabs">
                                        <li>
                                            <a class="nav-link " href="<?php echo URL ?>agency/edit?iditem=<?php echo $this->data["id"]; ?>"> 
                                                <i class="fa fa-user"></i> Scheda
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link " href="<?php echo URL ?>agency/editPrefView?iditem=<?php echo $this->data["id"]; ?>"> 
                                                <i class="fa fa-eye"></i> Preferenze Vista
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="<?php echo URL ?>agency/editPref?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-list"></i> Preferenze Export
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link active" style="color:#6DC4E9;" href="<?php echo URL ?>agency/editImg?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-picture-o"></i> Immagine
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content ">
                                        <div id="tab-6" class="tab-pane active">
                                            <div class="panel-body">
                                                <form method="POST"  action="<?php echo URL ?>agency/executeEditImg?iditem=<?php echo  $this->data["id"] ?>" 
                                                    enctype="multipart/form-data">
                                                  <div class="row">
                                                        <?php if ($this->error) { ?>
                                                            <div class="col-md-12 text-center">
                                                                <p class="alert alert-danger"><strong>Oops...</strong><?php echo $this->error;?></p>
                                                            </div>
                                                        <?php }  ?>
                                                        <?php if ($this->message) { ?>
                                                            <div class="col-md-12 text-center">
                                                                <p class="alert alert-success"><?php echo $this->message;?></p>
                                                            </div>
                                                        <?php }  ?>
                                                        <div class="col-md-12">
                                                            <h4>Immagine Agenzia <br><small>(L'immagine viene inserita nell'Annuncio solo nel caso in cui l'Asta non abbia una sua immagine)</small></h4>
                                                        </div>
                                                      <?php
                                                      if ( $this->data["URLImmagine"]!=NULL && $this->data["URLImmagine"]!="" ) {
                                                          ?>
                                                            <div class="col-md-6">
                                                                <img src="<?php echo $this->data["URLImmagine"] ?>" style="max-width: 300px;" />
                                                            </div>
                                                          <?php
                                                      }
                                                      ?>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Nuova Immagine*</label>
                                                                <input type="file" name="avatar" class="form-control" required="">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12 text-right">
                                                            <div class="form-group">
                                                                <a href="<?php echo URL; ?>agency/index" class="btn btn-default">
                                                                    Annulla
                                                                </a>
                                                                <button class="btn btn-flussi-light" type="submit">
                                                                    Upload Immagine
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
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>




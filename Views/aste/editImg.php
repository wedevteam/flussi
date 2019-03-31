

<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>
                            Dettaglio Asta  
                            <small>
                                <br>
                                Id# <?php echo $this->data["id"]  ?> | Creazione: <?php echo date("d/m/y", strtotime($this->data["DataInserimento_d"])) ;?>
                                <br>
                                Titolo: <?php echo $this->data["Titolo"] ?>
                            </small>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="col-lg-12">
                            <div class="tabs-container">
                                <div class="tabs-left">
                                    <ul class="nav nav-tabs">
                                        <li>
                                            <a class="nav-link" href="<?php echo URL ?>aste/overview?iditem=<?php echo $this->data["id"]; ?>"> 
                                                <i class="fa fa-home"></i> Informazioni
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="<?php echo URL ?>aste/edit?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-pencil"></i> Modifica
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link active" style="color:#6DC4E9;" href="<?php echo URL ?>aste/editImg?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-picture-o"></i> Immagine
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content ">
                                        <div id="tab-6" class="tab-pane active">
                                            <div class="panel-body">
                                                <?php if ($this->userLogged["role"]=="admin") { ?> 
                                                    <form method="POST" action="<?php echo URL ?>aste/executeEditImg?iditem=<?php echo  $this->data["id"] ?>" 
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
                                                                  <div class="form-group">
                                                                      <label>Immagine:</label>
                                                                      <div class="">
                                                                          <img src="<?php echo $this->data["immagine_URL"]; ?>" style="max-width: 200px !important;" />
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <div class="form-group">
                                                                      <label>Nuova immagine*</label>
                                                                      <input type="file" class="form-control" name="avatar" required="">
                                                                  </div>
                                                              </div>

                                                              <div class="col-md-12 text-right">
                                                                  <div class="form-group">
                                                                      <a href="<?php echo URL; ?>aste/index" class="btn btn-default">
                                                                          Annulla
                                                                      </a>
                                                                      <button class="btn btn-flussi-light" type="submit">
                                                                          Upload Immagine
                                                                      </button>
                                                                  </div>
                                                              </div>
                                                        </div>
                                                    </form>
                                                <?php } else { ?> 
                                                    <form method="POST" action="<?php echo URL ?>aste/executeEditImg?iditem=<?php echo  $this->data["id"] ?>&idrel=<?php echo  $this->relAsteAgenzia["id"] ?>" 
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
                                                                  <div class="form-group">
                                                                      <label>Immagine:</label>
                                                                      <div class="">
                                                                          <img src="<?php echo $this->relAsteAgenzia["immagine_URL"]; ?>" style="max-width: 200px !important;" />
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <div class="form-group">
                                                                      <label>Nuova immagine*</label>
                                                                      <input type="file" class="form-control" name="avatar" required="">
                                                                  </div>
                                                              </div>

                                                              <div class="col-md-12 text-right">
                                                                  <div class="form-group">
                                                                      <a href="<?php echo URL; ?>aste/index" class="btn btn-default">
                                                                          Annulla
                                                                      </a>
                                                                      <button class="btn btn-primary" type="submit">
                                                                          Upload Immagine
                                                                      </button>
                                                                  </div>
                                                              </div>
                                                        </div>
                                                    </form>
                                                <?php } ?> 
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



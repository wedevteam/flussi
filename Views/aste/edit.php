

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
                                            <a class="nav-link"  href="<?php echo URL ?>aste/gallery?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-photo"></i> Gallery
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link active" style="color:#6DC4E9;" href="<?php echo URL ?>aste/edit?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-pencil"></i> Modifica
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="<?php echo URL ?>aste/editImg?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-picture-o"></i> Immagini
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content ">
                                        <div id="tab-6" class="tab-pane active">
                                            <div class="panel-body">
                                                <?php if ($this->userLogged["role"]=="admin") { ?>
                                                    <form method="POST" action="<?php echo URL ?>aste/executeEditA?iditem=<?php echo  $this->data["id"] ?>" 
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
                                                                      <label>Descrizione personalizzata*</label>
                                                                      <textarea class="form-control" name="Testo" 
                                                                                required="" maxlength="3000" style="min-height:100px;" 
                                                                                placeholder="Descrizione personalizzata"><?php echo isset($this->errors) ? $_POST['Testo'] : $this->data['Testo'] ?></textarea>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <div class="form-group">
                                                                      <label>Descrizione Breve personalizzata*</label>
                                                                      <textarea class="form-control" name="TestoBreve" 
                                                                                required="" maxlength="560" style="min-height:100px;" 
                                                                                placeholder="Descrizione Breve personalizzata"><?php echo isset($this->errors) ? $_POST['TestoBreve'] : $this->data['TestoBreve'] ?></textarea>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <div class="form-group">
                                                                      <label>Note Admin (non visibili dalle Agenzie)</label>
                                                                      <textarea class="form-control" name="adminNote" 
                                                                                maxlength="3000" style="min-height:100px;" 
                                                                                placeholder="Note Admin"><?php echo isset($this->errors) ? $_POST['adminNote'] : $this->data['adminNote'] ?></textarea>
                                                                  </div>
                                                              </div>



                                                              <div class="col-md-12 text-right">
                                                                  <div class="form-group">
                                                                      <a href="<?php echo URL; ?>aste/index" class="btn btn-default">
                                                                          Annulla
                                                                      </a>
                                                                      <button class="btn btn-flussi-light" type="submit">
                                                                          Salva
                                                                      </button>
                                                                  </div>
                                                              </div>
                                                        </div>
                                                    </form>
                                                <?php } else { ?>
                                                    <div class="row" style="margin-bottom:10px;">
                                                        <div class="col-md-12 text-right">
                                                            <?php
                                                            if ( $this->relAsteAgenzia["isNoty"]=="off" ) {
                                                                ?>
                                                                <form method="POST" action="<?php echo URL ?>aste/setNotyOn?iditem=<?php echo  $this->data["id"] ?>&idrel=<?php echo  $this->relAsteAgenzia["id"] ?>">
                                                                    <button class="btn btn-flussi-light btn-xs confirmNotyOn" 
                                                                            type="button">
                                                                        <i class="fa fa-calendar"></i> Abilita ricezione App.to via email
                                                                    </button>
                                                                    <input hidden type="submit" id="btnNoty" name="btnNoty" value="<?php echo  $this->data["id"] ?>">
                                                                </form>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <form method="POST" action="<?php echo URL ?>aste/setNotyOff?iditem=<?php echo  $this->data["id"] ?>&idrel=<?php echo  $this->relAsteAgenzia["id"] ?>">
                                                                    <button class="btn btn-warning btn-xs confirmNotyOff" 
                                                                            type="button">
                                                                        <i class="fa fa-calendar"></i> Disabilita ricezione App.to via email
                                                                    </button>
                                                                    <input hidden type="submit" id="btnNoty" name="btnNoty" value="<?php echo  $this->data["id"] ?>">
                                                                </form>
                                                                <?php
                                                            }
                                                            ?>
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                    <form method="POST" action="<?php echo URL ?>aste/executeEdit?iditem=<?php echo  $this->data["id"] ?>&idrel=<?php echo  $this->relAsteAgenzia["id"] ?>" 
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
                                                                    <label>Riferimento Annuncio</label>
                                                                    <input type="text" placeholder="Riferimento Annuncio" class="form-control"
                                                                           maxlength="250" name="riferimentoAnnuncio"
                                                                           value="<?php echo isset($this->errors) ? $_POST['riferimentoAnnuncio'] : $this->relAsteAgenzia['riferimentoAnnuncio'] ?>"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Nome Agente</label>
                                                                    <input type="text" placeholder="Nome Agente" class="form-control"
                                                                           maxlength="50" name="nomeAgente" 
                                                                           value="<?php echo isset($this->errors) ? $_POST['nomeAgente'] : $this->relAsteAgenzia['nomeAgente'] ?>"/>
                                                                </div>
                                                            </div>
                                                              <?php
                                                              $prefFlagPubblicita_all="";
                                                              $prefFlagPubblicita_anyone="";
                                                              if (isset($_POST["btnSave"])) {
                                                                  if ($_POST["flagPubblicita"]=="all") {
                                                                      $prefFlagPubblicita_all="selected";
                                                                  }
                                                                  if ($_POST["flagPubblicita"]=="anyone") {
                                                                      $prefFlagPubblicita_anyone="selected";
                                                                  }
                                                              } else {
                                                                  if ($this->relAsteAgenzia["flagPubblicita"]=="all") {
                                                                      $prefFlagPubblicita_all="selected";
                                                                  }
                                                                  if ($this->relAsteAgenzia["flagPubblicita"]=="anyone") {
                                                                      $prefFlagPubblicita_anyone="selected";
                                                                  }
                                                              }
                                                              $prefFlagPrezzo_BaseAsta="";
                                                              $prefFlagPrezzo_OffertaMinima="";
                                                              if (isset($_POST["btnSave"])) {
                                                                  if ($_POST["preferenzaPrezzo"]=="BaseAsta") {
                                                                      $prefFlagPrezzo_BaseAsta="selected";
                                                                  }
                                                                  if ($_POST["preferenzaPrezzo"]=="OffertaMinima") {
                                                                      $prefFlagPrezzo_OffertaMinima="selected";
                                                                  }
                                                              } else {
                                                                  if ($this->relAsteAgenzia["preferenzaPrezzo"]=="BaseAsta") {
                                                                      $prefFlagPrezzo_BaseAsta="selected";
                                                                  }
                                                                  if ($this->relAsteAgenzia["preferenzaPrezzo"]=="OffertaMinima") {
                                                                      $prefFlagPrezzo_OffertaMinima="selected";
                                                                  }
                                                              }
                                                              ?>
                                                              <div class="col-md-6">
                                                                  <div class="form-group">
                                                                      <label>Flag Pubblicità*</label>
                                                                      <select class="form-control" name="flagPubblicita" required="">
                                                                          <option value="all" <?php echo $prefFlagPubblicita_all;?> >Pubblica</option>
                                                                          <option value="anyone" <?php echo $prefFlagPubblicita_anyone;?> >Non Pubblica</option>
                                                                      </select>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-6">
                                                                  <div class="form-group">
                                                                      <label>Prezzo default*</label>
                                                                      <select class="form-control" name="preferenzaPrezzo" required="">
                                                                          <option value="BaseAsta" <?php echo $prefFlagPrezzo_BaseAsta;?> >Importo Base Asta</option>
                                                                          <option value="OffertaMinima" <?php echo $prefFlagPrezzo_OffertaMinima;?> >Importo Offerta Minima</option>
                                                                      </select>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <div class="form-group">
                                                                      <label>Descrizione personalizzata*</label>
                                                                      <textarea class="form-control" name="descrizione" 
                                                                                required="" maxlength="3000" style="min-height:100px;" 
                                                                                placeholder="Descrizione personalizzata"><?php echo isset($this->errors) ? $_POST['descrizione'] : $this->relAsteAgenzia['descrizione'] ?></textarea>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <div class="form-group">
                                                                      <label>Note</label>
                                                                      <textarea class="form-control" name="noteAgenzia" 
                                                                                maxlength="3000" style="min-height:100px;" 
                                                                                placeholder="Note"><?php echo isset($this->errors) ? $_POST['noteAgenzia'] : $this->relAsteAgenzia['noteAgenzia'] ?></textarea>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <div class="form-group">
                                                                      <label>Commenti</label>
                                                                      <textarea class="form-control" name="commentiAgenzia" 
                                                                                maxlength="500" style="min-height:100px;" 
                                                                                placeholder="Commenti"><?php echo isset($this->errors) ? $_POST['commentiAgenzia'] : $this->relAsteAgenzia['commentiAgenzia'] ?></textarea>
                                                                  </div>
                                                              </div>
                                                              <?php
                                                              $richiesta_f="";
                                                              $richiesta_t="";
                                                              if (isset($_POST["btnSave"])) {
                                                                  if ($_POST["flagRichiestaVisione"]=="false") {
                                                                      $richiesta_f="selected";
                                                                  }
                                                                  if ($_POST["flagRichiestaVisione"]=="true") {
                                                                      $richiesta_t="selected";
                                                                  }
                                                              } else {
                                                                  if ($this->relAsteAgenzia["flagRichiestaVisione"]=="false") {
                                                                      $richiesta_f="selected";
                                                                  }
                                                                  if ($this->relAsteAgenzia["flagRichiestaVisione"]=="true") {
                                                                      $richiesta_t="selected";
                                                                  }
                                                              }
                                                              ?>
                                                              <div class="col-md-4">
                                                                  <div class="form-group">
                                                                      <label>Richiesta visione*</label>
                                                                      <select class="form-control" name="flagRichiestaVisione" required="">
                                                                          <option value="false" <?php echo $richiesta_f;?> >No</option>
                                                                          <option value="true" <?php echo $richiesta_t;?> >Si</option>
                                                                      </select>
                                                                  </div>
                                                              </div>
                                                              <?php
                                                              $data="";
                                                              $ora="";
                                                              if (isset($_POST["btnSave"])) {
                                                                  if ($_POST["flagRichiestaVisione"]=="true") {
                                                                      $data = $_POST["dataRichiestaVisione"];
                                                                      $ora = $_POST["oraRichiestaVisione"];
                                                                  }
                                                              } else {
                                                                  if ($this->relAsteAgenzia["flagRichiestaVisione"]=="true" && $this->relAsteAgenzia["dataRichiestaVisione"]!="0000-00-00") {
                                                                      $data= substr($this->relAsteAgenzia["dataRichiestaVisione"],8,2)."/".substr($this->relAsteAgenzia["dataRichiestaVisione"],5,2)."/".substr($this->relAsteAgenzia["dataRichiestaVisione"],0,4) ;
                                                                  }
                                                                  if ($this->relAsteAgenzia["flagRichiestaVisione"]=="true" && $this->relAsteAgenzia["oraRichiestaVisione"]!="00:00:00") {
                                                                      $ora= substr($this->relAsteAgenzia["oraRichiestaVisione"],0,5);
                                                                  }
                                                              }
                                                              ?>
                                                              <div class="col-md-4">
                                                                  <div class="form-group datapickerbox">
                                                                      <label>Data richiesta visione</label>
                                                                      <div class="input-group date">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </span>
                                                                            <input name="dataRichiestaVisione" type="text"
                                                                                 class="form-control" placeholder="gg/mm/aaaa"
                                                                                value="<?php  echo $data; ?>">
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-4">
                                                                  <div class="form-group">
                                                                      <label>Ora</label>
                                                                      <div class="input-group clockpicker" data-autoclose="true">
                                                                          <input type="text" class="form-control"
                                                                                 style="color:black;" name="oraRichiestaVisione"
                                                                                 placeholder="hh:mm" value="<?php  echo $ora; ?>" >
                                                                          <span class="input-group-addon">
                                                                                <span class="fa fa-clock-o"></span>
                                                                          </span>
                                                                      </div>
                                                                  </div>
                                                              </div>



                                                              <div class="col-md-12 text-right">
                                                                  <div class="form-group">
                                                                      <a href="<?php echo URL; ?>aste/index" class="btn btn-default">
                                                                          Annulla
                                                                      </a>
                                                                      <button class="btn btn-flussi-light" type="submit">
                                                                          Salva
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



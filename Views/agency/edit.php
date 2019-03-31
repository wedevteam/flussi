

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
                                            <a class="nav-link active" style="color:#6DC4E9;" href="<?php echo URL ?>agency/edit?iditem=<?php echo $this->data["id"]; ?>"> 
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
                                            <a class="nav-link" href="<?php echo URL ?>agency/editImg?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-picture-o"></i> Immagine
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content ">
                                        <div id="tab-6" class="tab-pane active">
                                            <div class="panel-body">
                                                <div class="row" style="margin-bottom: 10px;">
                                                    <div class="col-md-12 text-right">
                                                        <form method="POST" action="<?php echo URL ?>agency/resetCred?iditem=<?php echo  $this->data["id"] ?>">
                                                            <button class="btn btn-flussi-light btn-xs confirmCredenziali" 
                                                                    type="button">
                                                                <i class="fa fa-lock"></i> Reset e invia credenziali
                                                            </button>
                                                            <input hidden type="submit" id="btnResetCred" name="btnResetCred" value="<?php echo  $this->data["id"] ?>">
                                                        </form>
                                                    </div>
                                                </div>
                                                <form method="POST"  action="<?php echo URL ?>agency/executeEdit?iditem=<?php echo  $this->data["id"] ?>" 
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
                                                      <div class="col-md-6">
                                                          <div class="form-group">
                                                              <label>Ragione Sociale*</label>
                                                              <input type="text" placeholder="Ragione Sociale" class="form-control"
                                                                     maxlength="100" required="" name="companyName"
                                                                     value="<?php echo isset($this->errors) ? $_POST['companyName'] : $this->data['companyName'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                          <div class="form-group">
                                                              <label>Email <small>(utilizzata anche per l'accesso)</small>*</label>
                                                              <input type="email" placeholder="Email" class="form-control"
                                                                     maxlength="100" required="" name="email"
                                                                     value="<?php echo isset($this->errors) ? $_POST['email'] : $this->data['email'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>Tipo Contratto*</label>
                                                              <?php
                                                              $_standerd = "";
                                                              $_business = "";
                                                              if (isset($this->errors)) {
                                                                  if ($_POST['tipoContratto']=="standard" ) {
                                                                      $_standerd = "selected";
                                                                  }
                                                                  if ($_POST['tipoContratto']=="business" ) {
                                                                      $_business = "selected";
                                                                  }
                                                              } else {
                                                                  if ($this->data['tipoContratto']=="standard" ) {
                                                                      $_standerd = "selected";
                                                                  }
                                                                  if ($this->data['tipoContratto']=="business" ) {
                                                                      $_business = "selected";
                                                                  }
                                                              }
                                                              ?>
                                                              <select class="form-control" name="tipoContratto" required>
                                                                  <option value="standard" <?php echo $_standerd;?>>
                                                                      Standard
                                                                  </option>
                                                                  <option value="business" <?php echo $_business;?>>Business</option>
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>Nome referente*</label>
                                                              <input type="text" placeholder="Nome referente" class="form-control"
                                                                     maxlength="50" required="" name="firstName" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['firstName'] : $this->data['firstName'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>Cognome referente*</label>
                                                              <input type="text" placeholder="Cognome referente" class="form-control"
                                                                     maxlength="50" required="" name="lastName" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['lastName'] : $this->data['lastName'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                          <div class="form-group">
                                                              <label>Partita Iva</label>
                                                              <input type="text" placeholder="Partita Iva" class="form-control"
                                                                     maxlength="11" name="PartitaIva"
                                                                     value="<?php echo isset($this->errors) ? $_POST['PartitaIva'] : $this->data['PartitaIva'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                          <div class="form-group">
                                                              <label>Codice Fiscale</label>
                                                              <input type="text" placeholder="Codice Fiscale" class="form-control"
                                                                     maxlength="16" name="CodiceFiscale"
                                                                     value="<?php echo isset($this->errors) ? $_POST['CodiceFiscale'] : $this->data['CodiceFiscale'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>Telefono</label>
                                                              <input type="text" placeholder="Telefono" class="form-control"
                                                                     maxlength="20" name="Telefono" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['Telefono'] : $this->data['Telefono'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>Cellulare</label>
                                                              <input type="text" placeholder="Cellulare" class="form-control"
                                                                     maxlength="20" name="Cellulare" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['Cellulare'] : $this->data['Cellulare'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>Fax</label>
                                                              <input type="text" placeholder="Fax" class="form-control"
                                                                     maxlength="20" name="Fax"
                                                                     value="<?php echo isset($this->errors) ? $_POST['Fax'] : $this->data['Fax'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>Codice Nazione*</label>
                                                              <input type="text" placeholder="IT" class="form-control"
                                                                     maxlength="2" name="CodiceNazione" required="" disabled="" value='IT' />
                                                          </div>
                                                      </div>
                                                      <div class="col-md-8">
                                                          <div class="form-group">
                                                              <label>Comune <small></small>*</label>
                                                              <input type="text" placeholder="es. Milano (MI)" class="cities form-control"
                                                                     maxlength="50" name="Comune" required="" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['Comune'] : $this->data['Comune']. " (".$this->data['SiglaProvincia'].")" ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                          <div class="form-group">
                                                              <label>Indirizzo*</label>
                                                              <input type="text" placeholder="Indirizzo" class="form-control"
                                                                     maxlength="50" name="Indirizzo" required="" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['Indirizzo'] : $this->data['Indirizzo'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-3">
                                                          <div class="form-group">
                                                              <label>Civico*</label>
                                                              <input type="text" placeholder="Civico" class="form-control"
                                                                     maxlength="20" name="Civico" required="" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['Civico'] : $this->data['Civico'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-3">
                                                          <div class="form-group">
                                                              <label>Cap*</label>
                                                              <input type="text" placeholder="Cap" class="form-control"
                                                                     maxlength="5" name="Cap" required="" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['Cap'] : $this->data['Cap'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-12">
                                                          <hr>
                                                          <h4>Dati Contratto Agenzia</h4>
                                                      </div>
                                                      <div class="col-md-8">
                                                          <div class="form-group">
                                                              <label>Nome Pubblicità*</label>
                                                              <input type="text" placeholder="Nome Pubblicità" class="form-control"
                                                                     maxlength="50" name="NomePubblicita" required="" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['NomePubblicita'] : $this->data['NomePubblicita'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>ID Agenzia*</label>
                                                              <input type="text" placeholder="ID Agenzia" class="form-control"
                                                                     maxlength="6" name="IdAgenzia" required="" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['IdAgenzia'] : $this->data['IdAgenzia'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-8">
                                                          <div class="form-group">
                                                              <label>ID Gestionale*</label>
                                                              <input type="text" placeholder="ID Gestionale" class="form-control"
                                                                     maxlength="250" name="IdGestionale" required="" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['IdGestionale'] : $this->data['IdGestionale'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>Codice Portale*</label>
                                                              <input type="text" placeholder="Codice Portale" class="form-control"
                                                                     name="CodicePortale" disabled="" value="1 | Immobiliare.it" />
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>Data Inizio*</label>
                                                              <input type="date" placeholder="gg/mm/aaaa" class="form-control"
                                                                     name="DataInzio_d" required="" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['DataInzio_d'] : $this->data['DataInzio_d'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>Data Fine*</label>
                                                              <input type="date" placeholder="gg/mm/aaaa" class="form-control"
                                                                     name="DataFine_d" required=""  
                                                                     value="<?php echo isset($this->errors) ? $_POST['DataFine_d'] : $this->data['DataFine_d'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group">
                                                              <label>Numero Annunci*</label>
                                                              <input type="number" step="1" min="1" placeholder="Numero Annunci" class="form-control text-right"
                                                                     name="NrAnnunci" required="" 
                                                                     value="<?php echo isset($this->errors) ? $_POST['NrAnnunci'] : $this->data['NrAnnunci'] ?>"/>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-12 text-right">
                                                          <div class="form-group">
                                                              <a href="<?php echo URL; ?>agency/index" class="btn btn-default">
                                                                  Annulla
                                                              </a>
                                                              <button class="btn btn-flussi-light" type="submit">
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
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

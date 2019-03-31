<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>Inserisci Agenzia </h5>
                    </div>
                    <div class="ibox-content">
                        <form method="POST"  action="<?php echo URL ?>agency/executeCreate" enctype="multipart/form-data">
                            <div class="row">
                                <?php if ($this->error) { ?>
                                    <div class="col-md-12 text-center">
                                        <p class="alert alert-danger"><strong>Oops...</strong><?php echo $this->error;?></p>
                                    </div>
                                <?php }  ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ragione Sociale*</label>
                                        <input type="text" placeholder="Ragione Sociale" class="form-control"
                                               maxlength="100" required="" name="companyName"
                                               value="<?php if (isset($_POST['companyName'])) { echo $_POST['companyName']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email <small>(utilizzata anche per l'accesso)</small>*</label>
                                        <input type="email" placeholder="Email" class="form-control"
                                               maxlength="100" required="" name="email"
                                               value="<?php if (isset($_POST['email'])) { echo $_POST['email']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo Contratto*</label>
                                        <select class="form-control" name="tipoContratto" required>
                                            <option value="standard">Standard</option>
                                            <option value="business">Business</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nome referente*</label>
                                        <input type="text" placeholder="Nome referente" class="form-control"
                                               maxlength="50" required="" name="firstName" 
                                               value="<?php if (isset($_POST['firstName'])) { echo $_POST['firstName']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Cognome referente*</label>
                                        <input type="text" placeholder="Cognome referente" class="form-control"
                                               maxlength="50" required="" name="lastName" 
                                               value="<?php if (isset($_POST['lastName'])) { echo $_POST['lastName']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Partita Iva</label>
                                        <input type="text" placeholder="Partita Iva" class="form-control"
                                               maxlength="11" name="PartitaIva"
                                               value="<?php if (isset($_POST['PartitaIva'])) { echo $_POST['PartitaIva']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Codice Fiscale</label>
                                        <input type="text" placeholder="Codice Fiscale" class="form-control"
                                               maxlength="16" name="CodiceFiscale"
                                               value="<?php if (isset($_POST['CodiceFiscale'])) { echo $_POST['CodiceFiscale']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Telefono</label>
                                        <input type="text" placeholder="Telefono" class="form-control"
                                               maxlength="20" name="Telefono" 
                                               value="<?php if (isset($_POST['Telefono'])) { echo $_POST['Telefono']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Cellulare</label>
                                        <input type="text" placeholder="Cellulare" class="form-control"
                                               maxlength="20" name="Cellulare" 
                                               value="<?php if (isset($_POST['Cellulare'])) { echo $_POST['Cellulare']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fax</label>
                                        <input type="text" placeholder="Fax" class="form-control"
                                               maxlength="20" name="Fax"
                                               value="<?php if (isset($_POST['Fax'])) { echo $_POST['Fax']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Codice Nazione*</label>
                                        <input type="text" placeholder="IT" class="form-control"
                                               maxlength="2" name="CodiceNazione" required="" disabled="" value='IT' />
                                    </div>
                                </div>
<!--                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Codice Comune* <small></small>*</label>
                                        <input type="text" placeholder="Codice Comune" class="form-control"
                                               maxlength="6" name="CodiceComune" required=""
                                               value="<?php //if (isset($_POST['CodiceComune'])) { echo $_POST['CodiceComune']; }?>"/>
                                    </div>
                                </div>-->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Comune <small></small>*</label>
                                        <input type="text" placeholder="es. Milano (MI)" class="form-control"
                                               maxlength="50" name="Comune" required="" 
                                               value="<?php if (isset($_POST['Comune'])) { echo $_POST['Comune']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Indirizzo*</label>
                                        <input type="text" placeholder="Indirizzo" class="form-control"
                                               maxlength="50" name="Indirizzo" required="" 
                                               value="<?php if (isset($_POST['Indirizzo'])) { echo $_POST['Indirizzo']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Civico*</label>
                                        <input type="text" placeholder="Civico" class="form-control"
                                               maxlength="20" name="Civico" required="" 
                                               value="<?php if (isset($_POST['Civico'])) { echo $_POST['Civico']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Cap*</label>
                                        <input type="text" placeholder="Cap" class="form-control"
                                               maxlength="5" name="Cap" required="" 
                                               value="<?php if (isset($_POST['Cap'])) { echo $_POST['Cap']; }?>"/>
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
                                               value="<?php if (isset($_POST['NomePubblicita'])) { echo $_POST['NomePubblicita']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>ID Agenzia*</label>
                                        <input type="text" placeholder="ID Agenzia" class="form-control"
                                               maxlength="6" name="IdAgenzia" required="" 
                                               value="<?php if (isset($_POST['IdAgenzia'])) { echo $_POST['IdAgenzia']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>ID Gestionale*</label>
                                        <input type="text" placeholder="ID Gestionale" class="form-control"
                                               maxlength="250" name="IdGestionale" required="" 
                                               value="<?php if (isset($_POST['IdGestionale'])) { echo $_POST['IdGestionale']; }?>"/>
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
                                               value="<?php if (isset($_POST['DataInzio_d'])) { echo $_POST['DataInzio_d']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data Fine*</label>
                                        <input type="date" placeholder="gg/mm/aaaa" class="form-control"
                                               name="DataFine_d" required=""  
                                               value="<?php if (isset($_POST['DataFine_d'])) { echo $_POST['DataFine_d']; }?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Numero Annunci*</label>
                                        <input type="number" step="1" min="1" placeholder="Numero Annunci" class="form-control text-right"
                                               name="NrAnnunci" required="" 
                                               value="<?php if (isset($_POST['NrAnnunci'])) { echo $_POST['NrAnnunci']; }?>"/>
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

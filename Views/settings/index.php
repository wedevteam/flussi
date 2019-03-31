<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>Impostazioni </h5>
                    </div>
                    <div class="ibox-content">
                        <form method="POST"  action="<?php echo URL ?>settings/executeEdit" enctype="multipart/form-data">
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
                                    <h4>Configurazione FTP Getrix</h4>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Host*</label>
                                        <input type="text" placeholder="Host" class="form-control"
                                               maxlength="250" required="" name="ftpHost" disabled="" 
                                                value="<?php echo isset($this->errors) ? $_POST['ftpHost'] : $this->platformData['ftpHost'] ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>User*</label>
                                        <input type="text" placeholder="User" class="form-control"
                                               maxlength="100" required="" name="ftpUser" disabled="" 
                                                value="<?php echo isset($this->errors) ? $_POST['ftpUser'] : $this->platformData['ftpUser'] ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Password*</label>
                                        <input type="text" placeholder="Password" class="form-control"
                                               maxlength="100" required="" name="ftpPw" disabled="" 
                                                value="<?php echo isset($this->errors) ? $_POST['ftpPw'] : $this->platformData['ftpPw'] ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                    <h4>Configurazione Google API Key</h4>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Google API Key*</label>
                                        <input type="text" placeholder="Google API Key" class="form-control"
                                               maxlength="50" name="GoogleApiKey" required="" 
                                                value="<?php echo isset($this->errors) ? $_POST['GoogleApiKey'] : $this->platformData['GoogleApiKey'] ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                    <h4>Configurazione preferenze Esportazione vs. Getrix</h4>
                                </div>
                                <?php
                                $prefFlagPubblicita_all="";
                                $prefFlagPubblicita_anyone="";
                                if (isset($_POST["btnSave"])) {
                                    if ($_POST["prefFlagPubblicita"]=="all") {
                                        $prefFlagPubblicita_all="selected";
                                    }
                                    if ($_POST["prefFlagPubblicita"]=="anyone") {
                                        $prefFlagPubblicita_anyone="selected";
                                    }
                                } else {
                                    if ($this->platformData["prefFlagPubblicita"]=="all") {
                                        $prefFlagPubblicita_all="selected";
                                    }
                                    if ($this->platformData["prefFlagPubblicita"]=="anyone") {
                                        $prefFlagPubblicita_anyone="selected";
                                    }
                                }
                                $prefFlagPrezzo_BaseAsta="";
                                $prefFlagPrezzo_OffertaMinima="";
                                if (isset($_POST["btnSave"])) {
                                    if ($_POST["prefFlagPrezzo"]=="BaseAsta") {
                                        $prefFlagPrezzo_BaseAsta="selected";
                                    }
                                    if ($_POST["prefFlagPrezzo"]=="OffertaMinima") {
                                        $prefFlagPrezzo_OffertaMinima="selected";
                                    }
                                } else {
                                    if ($this->platformData["prefFlagPrezzo"]=="BaseAsta") {
                                        $prefFlagPrezzo_BaseAsta="selected";
                                    }
                                    if ($this->platformData["prefFlagPrezzo"]=="OffertaMinima") {
                                        $prefFlagPrezzo_OffertaMinima="selected";
                                    }
                                }
                                ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Flag Pubblicità*</label>
                                        <select class="form-control" name="prefFlagPubblicita" required="">
                                            <option value="all" <?php echo $prefFlagPubblicita_all;?> >Tutti in Pubblicità</option>
                                            <option value="anyone" <?php echo $prefFlagPubblicita_anyone;?> >Nessuno in Pubblicità</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Prezzo default*</label>
                                        <select class="form-control" name="prefFlagPrezzo" required="">
                                            <option value="BaseAsta" <?php echo $prefFlagPrezzo_BaseAsta;?> >Importo Base Asta</option>
                                            <option value="OffertaMinima" <?php echo $prefFlagPrezzo_OffertaMinima;?> >Importo Offerta Minima</option>
                                        </select>
                                    </div>
                                </div>
                                
                                
                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <button class="btn btn-flussi-light" type="submit" name="btnSave" disabled>
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


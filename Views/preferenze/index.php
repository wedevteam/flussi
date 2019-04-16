<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>Preferenze </h5>
                    </div>
                    <div class="ibox-content">
                        <form method="POST"  action="<?php echo URL ?>preferenze/executeEdit" enctype="multipart/form-data">
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
                                    if ($this->userLogged["prefFlagPubblicita"]=="all") {
                                        $prefFlagPubblicita_all="selected";
                                    }
                                    if ($this->userLogged["prefFlagPubblicita"]=="anyone") {
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
                                    if ($this->userLogged["prefFlagPrezzo"]=="BaseAsta") {
                                        $prefFlagPrezzo_BaseAsta="selected";
                                    }
                                    if ($this->userLogged["prefFlagPrezzo"]=="OffertaMinima") {
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
                                <div class="col-md-12">
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            Comune Immobile
                                            <br>
                                            <small>(Se non si seleziona nulla il sistema li prende tutti)</small>
                                        </label>
                                        <select data-placeholder="Seleziona Comuni" name="idComuni[]"
                                            class="chosen-select" multiple style="width:350px;" tabindex="4">
                                            <?php
                                            if (is_array($this->comuniList) || is_object($this->comuniList)) {
                                                foreach ($this->comuniList as $item) {
                                                    $_selected = "";
                                                    if (is_array($this->relAgPrefList) || is_object($this->relAgPrefList)) {
                                                        foreach ($this->relAgPrefList as $pref) {
                                                            if ($pref["tipoPreferenza"]=="comune" && $pref["idOggetto"]==$item["codice_istat"]) {
                                                                $_selected = " selected ";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $item["codice_istat"]; ?>" <?php echo $_selected;?>>
                                                        <?php echo $item["nome"]." (".$item["siglaprovincia"].")";?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            Provincia Immobile
                                            <br>
                                            <small>(Se non si seleziona nulla il sistema le prende tutte)</small>
                                        </label>
                                        <select data-placeholder="Seleziona Provincia" name="idProvince[]"
                                            class="chosen-select" multiple style="width:350px;" tabindex="4">
                                            <?php
                                            if (is_array($this->provinceList) || is_object($this->provinceList)) {
                                                foreach ($this->provinceList as $item) {
                                                    $_selected = " ";
                                                    if (is_array($this->relAgPrefList) || is_object($this->relAgPrefList)) {
                                                        foreach ($this->relAgPrefList as $pref) {
                                                            if ($pref["tipoPreferenza"]=="provincia" && $pref["idOggetto"]==$item["sigla"]) {
                                                                $_selected = " selected ";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $item["sigla"]; ?>" <?php echo $_selected?> >
                                                        <?php echo $item["nome"]." (".$item["sigla"].")";?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            Cap Immobile
                                            <br>
                                            <small>(Se non si seleziona nulla il sistema li prende tutti)</small>
                                        </label>
                                        <select data-placeholder="Seleziona Cap" name="capComuni[]"
                                                class="chosen-select" multiple style="100%;" tabindex="4">
                                            <?php
                                            if (sizeof($this->capList)>0) {
                                                foreach ($this->capList as $cap) {
                                                    $_selected = " ";
                                                    if (is_array($this->relAgPrefList) || is_object($this->relAgPrefList)) {
                                                        foreach ($this->relAgPrefList as $pref) {
                                                            if ($pref["tipoPreferenza"]=="cap" && $pref["idOggetto"]==$cap["cap"]) {
                                                                $_selected = " selected ";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $cap["cap"] ?>" <?php echo $_selected?> >
                                                        <?php echo $cap["cap"]; ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            Comune Tribunale
                                            <br>
                                            <small>(Se non si seleziona nulla il sistema li prende tutti)</small>
                                        </label>
                                        <select data-placeholder="Seleziona Comuni" name="idComuniTribunale[]"
                                            class="chosen-select" multiple style="width:350px;" tabindex="4">
                                            <?php
                                            if (is_array($this->comuniTribunaleList) || is_object($this->comuniTribunaleList)) {
                                                foreach ($this->comuniTribunaleList as $item) {
                                                    $_selected = " ";
                                                    if (is_array($this->relAgPrefList) || is_object($this->relAgPrefList)) {
                                                        foreach ($this->relAgPrefList as $pref) {
                                                            if ($pref["tipoPreferenza"]=="comuneTribunale" && $pref["idOggetto"]==$item["codice_istat"]) {
                                                                $_selected = " selected ";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $item["codice_istat"]; ?>" <?php echo $_selected?> >
                                                        <?php echo $item["nome"]." (".$item["siglaprovincia"].")";?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                                
                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <button class="btn btn-flussi-light" type="submit" name="btnSave">
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



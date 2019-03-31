<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>Esporta vs Getrix </h5>
                    </div>
                    <div class="ibox-content">
                        <form method="POST"  action="<?php echo URL ?>exports/resultExport" enctype="multipart/form-data">
                            <div class="row">
                                <?php if ($this->error) { ?>
                                    <div class="col-md-12 text-center">
                                        <p class="alert alert-danger"><?php echo $this->error;?></p>
                                    </div>
                                <?php }  ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            Agenzie
                                            <br>
                                            <small>(Se non si seleziona nulla il sistema le prende tutte)</small>
                                        </label>
                                        <select data-placeholder="Seleziona Agenzie" name="idAgenzie[]"
                                                class="chosen-select" multiple style="width:350px;" tabindex="4">
                                            <?php
                                            if (is_array($this->agencyList) || is_object($this->agencyList)) {
                                                foreach ($this->agencyList as $item) {
                                                    if ($item["status"]=="on") {
                                                        ?>
                                                        <option value="<?php echo $item["id"]; ?>">
                                                            <?php echo $item["companyName"];?>
                                                        </option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
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
                                                ?>
                                                <option value="<?php echo $item["codice_istat"]; ?>">
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
                                            Comune Tribunale
                                            <br>
                                            <small>(Se non si seleziona nulla il sistema li prende tutti)</small>
                                        </label>
                                        <select data-placeholder="Seleziona Comuni" name="idComuniTribunale[]"
                                            class="chosen-select" multiple style="width:350px;" tabindex="4">
                                            <?php
                                            if (is_array($this->comuniList) || is_object($this->comuniList)) {
                                                foreach ($this->comuniList as $item) {
                                                ?>
                                                <option value="<?php echo $item["codice_istat"]; ?>">
                                                    <?php echo $item["nome"]." (".$item["siglaprovincia"].")";?>
                                                </option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
<!--                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            Cap
                                            <br>
                                            <small>(Se non si seleziona nulla il sistema li prende tutti)</small>
                                        </label>
                                        <input type="text" class="form-control" name="cap" placeholder="Elenco Cap separati da virgola">
                                    </div>
                                </div>-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            Tipologie
                                            <br>
                                            <small>(Se non si seleziona nulla il sistema le prende tutte)</small>
                                        </label>
                                        <select data-placeholder="Seleziona Tipologie" name="idTipologie[]"
                                                class="chosen-select" multiple style="width:350px;" tabindex="4">
                                            <option value="1">
                                                Immobili Residenziali
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <a href="<?php echo URL; ?>exports/index" class="btn btn-default">
                                            Annulla
                                        </a>
                                        <button class="btn btn-flussi-light" type="submit" name="btnExport">
                                            Esporta vs. Getrix
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



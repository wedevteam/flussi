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
                                <?php
                                $dataDa="";
                                if (isset($_POST["dataAstaDa"])) {
                                    $dataDa = $_POST["dataAstaDa"];
                                }
                                ?>
                                <div class="col-md-4">
                                    <div class="form-group datapickerbox">
                                        <label>Data Asta Da</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input name="dataAstaDa" type="text"
                                                   class="form-control" placeholder="gg/mm/aaaa"
                                                   value="<?php  echo $dataDa; ?>">
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $dataA="";
                                if (isset($_POST["dataAstaA"])) {
                                    $dataA = $_POST["dataAstaA"];
                                }
                                ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group datapickerbox">
                                            <label>Data Asta A</label>
                                            <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                                <input name="dataAstaA" type="text"
                                                       class="form-control" placeholder="gg/mm/aaaa"
                                                       value="<?php  echo $dataA; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Categorie</label>
                                        <select data-placeholder="Seleziona Categorie" name="idCategorie[]"
                                                class="chosen-select" multiple style="width:100%" tabindex="4">
                                            <?php
                                            $tipologieArr = unserialize (GX_CATEGORIA);
                                            for ($i=1; $i<= sizeof($tipologieArr)-1;$i++){
                                                $_selected = "";
                                                if (isset($_POST["idCategorie"])) {
                                                    foreach ($_POST["idCategorie"] as $cat) {
                                                        if ($cat==$i) {
                                                            $_selected = " selected ";
                                                        }
                                                    }
                                                }
                                                ?>
                                                <option value="<?php echo $i; ?>" <?php echo $_selected; ?> >
                                                    <?php echo $tipologieArr[$i] ?>
                                                </option>
                                                <?php
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
                                            Cap Immobile
                                            <br>
                                            <small>(Se non si seleziona nulla il sistema li prende tutti)</small>
                                        </label>
                                        <select data-placeholder="Seleziona Cap" name="capComuni[]"
                                                class="chosen-select" multiple style="100%;" tabindex="4">
                                            <?php
                                            if (sizeof($this->capList)>0) {
                                                foreach ($this->capList as $cap) {
                                                    $_selected = "";
                                                    if ( isset($_POST["capComuni"])) {
                                                        foreach ($_POST["capComuni"] as $codCap ) {
                                                            if ($cap["cap"]==$codCap) {
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Offerta Minima Da</label>
                                        <input type="number" class="form-control" placeholder="€" name="offertaMinDa" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Offerta Minima A</label>
                                        <input type="number" class="form-control" placeholder="€" name="offertaMinA" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Superficie Da</label>
                                        <input type="number" class="form-control" placeholder="Mq" name="superficieDa" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Superficie A</label>
                                        <input type="number" class="form-control" placeholder="Mq" name="superficieA" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Num. Locali Da</label>
                                        <input type="number" class="form-control" placeholder="Numero" name="NrLocaliDa" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Num. Locali A</label>
                                        <input type="number" class="form-control" placeholder="Numero" name="NrLocaliA" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Flag Pubblicità</label>
                                        <select class="form-control" name="flagPubblicita">
                                            <option value="">Tutti</option>
                                            <option value="all">In Pubblicità</option>
                                            <option value="anyone">Non In Pubblicità</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status Esportazione</label>
                                        <select class="form-control" name="statusExport">
                                            <option value="">Tutti</option>
                                            <option value="importato">Esportato</option>
                                            <option value="non importato">Non Esportato</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <a href="<?php echo URL; ?>exports/index" class="btn btn-default">
                                            Annulla
                                        </a>
                                        <button class="btn btn-flussi-light" type="submit" name="btnExport">
                                            Crea Esportazione
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



<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>
                            Esporta Aste
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <?php if ($_POST["btnExport"]) { ?>

                        <?php }else {?>
                            <form method="POST"  action="<?php echo URL ?>aste/export">
                                <div class="row">
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
                                            <label>Comuni Immobile</label>
                                            <select data-placeholder="Seleziona Comuni" name="codiciComuni[]"
                                                    class="chosen-select" multiple style="100%;" tabindex="4">
                                                <?php
                                                if (sizeof($this->comuniList)>0) {
                                                    foreach ($this->comuniList as $comune) {
                                                        $_selected = "";
                                                        if ( isset($_POST["codiciComuni"])) {
                                                            foreach ($_POST["codiciComuni"] as $codComune ) {
                                                                if ($comune["codice_istat"]==$codComune) {
                                                                    $_selected = " selected ";
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <option value="<?php echo $comune["codice_istat"] ?>" <?php echo $_selected?> >
                                                            <?php echo $comune["nome"]." (".$comune["siglaprovincia"].")" ?>
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
                                            <label>Cap Immobile</label>
                                            <select class="form-control" name="capComuni">
                                                <option value="0"  <?php if (isset($_POST["codiceComuneFilter"]) && $_POST["codiceComuneFilter"]==0) {echo "selected";} ?> >
                                                    Tutti
                                                </option>
                                                <?php
                                                if (sizeof($this->comuniList)>0) {
                                                    foreach ($this->comuniList as $comune) {
                                                        $_selected = "";
                                                        if ( isset($_POST["codiceComuneFilter"]) && $_POST["codiceComuneFilter"]!=0 ) {
                                                            if ($comune["codice_istat"]==$_POST["codiceComuneFilter"]) {
                                                                $_selected = " selected ";
                                                            }
                                                        }
                                                        ?>
                                                        <option value="<?php echo $comune["codice_istat"] ?>" <?php echo $_selected?> >
                                                            <?php echo $comune["nome"]." (".$comune["siglaprovincia"].")" ?>
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
                                            <label>Comune Tribunale</label>
                                            <select data-placeholder="Seleziona Comuni" name="codiciComuniTribunali[]"
                                                    class="chosen-select" multiple style="100%;" tabindex="4">
                                                <?php
                                                if (sizeof($this->comuniTribunaleList)>0) {
                                                    foreach ($this->comuniTribunaleList as $comune) {
                                                        $_selected = "";
                                                        if ( isset($_POST["codiciComuniTribunali"])) {
                                                            foreach ($_POST["codiciComuniTribunali"] as $codComune ) {
                                                                if ($comune["codice_istat"]==$codComune) {
                                                                    $_selected = " selected ";
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <option value="<?php echo $comune["codice_istat"] ?>" <?php echo $_selected?> >
                                                            <?php echo $comune["nome"]." (".$comune["siglaprovincia"].")" ?>
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
                                            <input type="number" class="form-control"
                                                   placeholder="€" name="offertaMinDa"
                                                   value="<?php if (isset($_POST["offertaMinDa"])) {echo $_POST["offertaMinDa"];} ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Offerta Minima A</label>
                                            <input type="number" class="form-control" placeholder="€" name="offertaMinA"
                                                   value="<?php if (isset($_POST["offertaMinA"])) {echo $_POST["offertaMinA"];} ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Superficie Da</label>
                                            <input type="number" class="form-control" placeholder="Mq" name="superficieDa"
                                                   value="<?php if (isset($_POST["superficieDa"])) {echo $_POST["superficieDa"];} ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Superficie A</label>
                                            <input type="number" class="form-control" placeholder="Mq" name="superficieA"
                                                   value="<?php if (isset($_POST["superficieA"])) {echo $_POST["superficieA"];} ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Num. Locali Da</label>
                                            <input type="number" class="form-control" placeholder="Numero" name="NrLocaliDa"
                                                   value="<?php if (isset($_POST["NrLocaliDa"])) {echo $_POST["NrLocaliDa"];} ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Num. Locali A</label>
                                            <input type="number" class="form-control" placeholder="Numero" name="NrLocaliA"
                                                   value="<?php if (isset($_POST["NrLocaliA"])) {echo $_POST["NrLocaliA"];} ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Flag Pubblicità</label>
                                            <select class="form-control" name="flagPubblicita">
                                                <option value="" <?php if (isset($_POST["flagPubblicita"]) && $_POST["flagPubblicita"]=='') {echo "selected";}?>>Tutti</option>
                                                <option value="all" <?php if (isset($_POST["flagPubblicita"]) && $_POST["flagPubblicita"]=='all') {echo "selected";}?>>In Pubblicità</option>
                                                <option value="anyone" <?php if (isset($_POST["flagPubblicita"]) && $_POST["flagPubblicita"]=='anyone') {echo "selected";}?>>Non In Pubblicità</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status Esportazione</label>
                                            <select class="form-control" name="statusExport">
                                                <option value="" <?php if (isset($_POST["statusExport"]) && $_POST["statusExport"]=='') {echo "selected";}?>>Tutti</option>
                                                <option value="importato" <?php if (isset($_POST["statusExport"]) && $_POST["statusExport"]=='importato') {echo "selected";}?>>Esportato</option>
                                                <option value="non importato" <?php if (isset($_POST["statusExport"]) && $_POST["statusExport"]=='non importato') {echo "selected";}?>>Non Esportato</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-right" style="margin-top:30px;">
                                            <a class="btn btn-default" href="<?php echo URL ?>aste/index" >
                                                Annulla
                                            </a>
                                            <button class="btn btn-flussi-light" name="btnExport" type="submit">
                                                Esporta CSV
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



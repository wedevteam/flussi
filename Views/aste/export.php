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
                        <?php if (isset($_POST["btnExport"])) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group text-right" >
<!--                                        <a class="btn btn-flussi-light" href="--><?php //echo URL ?><!--aste/export" >-->
<!--                                            <i class="fa fa-download"></i> Nuova Esportazione-->
<!--                                        </a>-->
                                        <a class="btn btn-default" href="<?php echo URL ?>aste/export" >
                                            Indietro
                                        </a>
                                    </div>
                                </div>
                                <?php
                                if (sizeof($this->asteList)==0) {
                                    ?>
                                    <div class="col-md-12">
                                        <p class="alert alert-info text-center">Non ci sono Aste corrispondenti ai Filtri selezionati</p>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="col-md-12">
                                        <p class="alert alert-info text-center">
                                            Ci sono <b><?php echo sizeof($this->asteList); ?></b> Aste da Esportare
                                        </p>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dataTables-example">
                                            <thead>
                                            <tr>
                                                <th class="text-center" data-type="numeric">ID#</th>
                                                <th>
                                                    Rge / Lotto
                                                </th>
                                                <th>
                                                    Tribunale
                                                </th>
                                                <th>
                                                    Indirizzo
                                                </th>
                                                <th class="text-center" data-type="numeric">
                                                    Data Asta
                                                </th>
                                                <th class="text-center" data-type="numeric">
                                                    Base Asta
                                                </th>
                                                <th hidden class="text-center" data-type="numeric">
                                                    Offerta Min.
                                                </th>
                                                <th class="text-center" data-type="numeric">
                                                    Mq
                                                </th>
                                                <th hidden>
                                                    Link
                                                </th>
                                                <th hidden>
                                                    Tipo Procedura
                                                </th>
                                                <th hidden>
                                                    Rito
                                                </th>
                                                <th hidden>
                                                    Giudice
                                                </th>
                                                <th hidden>
                                                    Delegato
                                                </th>
                                                <th hidden>
                                                    Custode
                                                </th>
                                                <th hidden>
                                                    Curatore
                                                </th>
                                                <th hidden>
                                                    Latitudine
                                                </th>
                                                <th hidden>
                                                    Longitudine
                                                </th>
                                                <th hidden>
                                                    Testo
                                                </th>
                                                <th hidden>
                                                    Rif. Annuncio
                                                </th>
                                                <th hidden>
                                                    Nome Agente
                                                </th>
                                                <th hidden>
                                                    Richiesta visione
                                                </th>
                                                <th hidden class="text-center">Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($this->asteList as $item) { ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $item["id"]."-".$this->userLogged["id"]; ?>
                                                    </td>
                                                    <td><?php echo $item["rge"]." / ".$item["lotto"]; ?></td>
                                                    <td><?php echo $item["ComuneTribunale"]." (".$item["SiglaProvTribunale"].")"; ?></td>
                                                    <td>
                                                        <?php echo $item["ComuneProvinciaCompleto"]; ?>
                                                        <br>
                                                        <?php echo $item["Strada_testo"] . " ". $item["Indirizzo"]. " ". $item["Civico"]; ?>
                                                    </td>
                                                    <?php
                                                    $dataAstaSort = "";
                                                    if ($item["dataAsta"]!=Null && $item["dataAsta"]!="0000-00-00") {
                                                        $dataAstaSort = substr($item["dataAsta"],0,4) .substr($item["dataAsta"],5,2) .substr($item["dataAsta"],8,2)  ;
                                                    }
                                                    ?>
                                                    <td class="text-center" data-type="number"  data-value="<?php echo $dataAstaSort; ?>">
                                                        <?php
                                                        if ($item["dataAsta"]!=Null && $item["dataAsta"]!="0000-00-00") {
                                                            echo date("d/m/y", strtotime($item["dataAsta"])) ;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center" data-value="<?php echo $item["importoBaseAsta"]; ?>">
                                                        <?php echo number_format($item["importoBaseAsta"],2,',','.'); ?>
                                                    </td>
                                                    <td hidden class="text-center" data-value="<?php echo $item["importoOffertaMinima"]; ?>">
                                                        <?php echo number_format($item["importoOffertaMinima"],2,',','.');  ?>
                                                    </td>
                                                    <td class="text-center"  data-type="number" data-value="<?php echo $item["MQSuperficie"]; ?>">
                                                        <?php echo $item["MQSuperficie"]; ?>
                                                    </td>
                                                    <td hidden>
                                                        <?php
                                                        if ($item["linkTribunale"]!="") {
                                                            ?>
                                                            <a href="<?php echo $item["linkTribunale"]; ?>" target="_blank">
                                                                <?php echo $item["linkTribunale"]; ?>
                                                            </a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td hidden><?php echo $item["tipoProcedura"]; ?></td>
                                                    <td hidden><?php echo $item["rito"]; ?></td>
                                                    <td hidden><?php echo $item["giudice"]; ?></td>
                                                    <td hidden><?php echo $item["delegato"]; ?></td>
                                                    <td hidden><?php echo $item["custode"]; ?></td>
                                                    <td hidden><?php echo $item["curatore"]; ?></td>
                                                    <td hidden><?php echo $item["Latitudine"]; ?></td>
                                                    <td hidden><?php echo $item["Longitudine"]; ?></td>
                                                    <td hidden><?php echo $item["Testo"]; ?></td>
                                                    <td hidden>
                                                        <?php echo $item["riferimentoAnnuncio"]; ?>
                                                    </td>
                                                    <td hidden>
                                                        <?php echo $item["nomeAgente"]; ?>
                                                    </td>
                                                    <td hidden>
                                                        <?php
                                                        if ($item["flagRichiestaVisione"]=="false") {
                                                            echo 'No';
                                                        } else {
                                                            echo 'Si ('.date("d/m/y", strtotime($item["dataRichiestaVisione"])).')';
                                                        }
                                                        ?>
                                                    </th>
                                                    <td hidden class="text-center">
                                                        <?php
                                                        if ($item["status"]=='on') {
                                                            ?>
                                                            <i class="fa fa-check text-success" title="Valida"></i>
                                                            <?php
                                                        }
                                                        if ($item["status"]=='off') {
                                                            ?>
                                                            <i class="fa fa-remove text-danger" title="Non Valida"></i>
                                                            <?php
                                                        }
                                                        if ($item["status"]=='importato') {
                                                            ?>
                                                            <i class="fa fa-check text-success" title="Esportata"></i>
                                                            <?php
                                                        }
                                                        if ($item["status"]=='non importato') {
                                                            ?>
                                                            <i class="fa fa-remove text-danger" title="Non Esportata"></i>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-center" data-type="numeric">ID#</th>
                                                    <th>
                                                        Rge / Lotto
                                                    </th>
                                                    <th>
                                                        Tribunale
                                                    </th>
                                                    <th>
                                                        Indirizzo
                                                    </th>
                                                    <th class="text-center" data-type="numeric">
                                                        Data Asta
                                                    </th>
                                                    <th class="text-center" data-type="numeric">
                                                        Base Asta
                                                    </th>
                                                    <th hidden class="text-center" data-type="numeric">
                                                        Offerta Min.
                                                    </th>
                                                    <th class="text-center" data-type="numeric">
                                                        Mq
                                                    </th>
                                                    <th hidden>
                                                        Link
                                                    </th>
                                                    <th hidden>
                                                        Tipo Procedura
                                                    </th>
                                                    <th hidden>
                                                        Rito
                                                    </th>
                                                    <th hidden>
                                                        Giudice
                                                    </th>
                                                    <th hidden>
                                                        Delegato
                                                    </th>
                                                    <th hidden>
                                                        Custode
                                                    </th>
                                                    <th hidden>
                                                        Curatore
                                                    </th>
                                                    <th hidden>
                                                        Latitudine
                                                    </th>
                                                    <th hidden>
                                                        Longitudine
                                                    </th>
                                                    <th hidden>
                                                        Testo
                                                    </th>
                                                    <th hidden>
                                                        Rif. Annuncio
                                                    </th>
                                                    <th hidden>
                                                        Nome Agente
                                                    </th>
                                                    <th hidden>
                                                        Richiesta visione
                                                    </th>
                                                    <th hidden class="text-center">Status</th>
                                                </tr>
                                            </tfoot>

                                        </table>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        <?php }else {?>
                            <form method="POST" action="<?php echo URL ?>aste/export">
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
                                                        <?php echo $tipologieArr[$i];?>
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
                                            <select data-placeholder="Seleziona Cap" name="capComuni[]"
                                                    class="chosen-select" multiple style="100%;" tabindex="4">

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



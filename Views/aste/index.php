<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>
                            Aste 
                            <small>(<?php echo sizeof($this->asteList);  ?>)</small>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <form method="POST"  action="<?php echo URL ?>aste/index">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Comune Immobile</label>
                                        <select class="form-control" name="codiceComuneFilter">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Comune Tribunale</label>
                                        <select class="form-control" name="codiceComuneTribunaleFilter">
                                            <option value="0"  <?php if (isset($_POST["codiceComuneTribunaleFilter"]) && $_POST["codiceComuneTribunaleFilter"]==0) {echo "selected";} ?> >
                                                Tutti
                                            </option>
                                            <?php 
                                            if (sizeof($this->comuniTribunaleList)>0) {
                                                foreach ($this->comuniTribunaleList as $comune) {
                                                    $_selected = "";
                                                    if ( isset($_POST["codiceComuneTribunaleFilter"]) && $_POST["codiceComuneTribunaleFilter"]!=0 ) {
                                                        if ($comune["codice_istat"]==$_POST["codiceComuneTribunaleFilter"]) {
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
                                <div class="col-md-4">
                                    <div class="form-group text-right" style="margin-top:30px;">
                                        <button class="btn btn-flussi-light" type="submit" name="btnSearch">
                                            Filtra
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php if (sizeof($this->asteList)==0 ) { ?>
                            <div class="col-md-12 text-center">
                                <p class="alert alert-info">Non ci sono aste</p>
                            </div>
                        <?php } else { ?>
                            
                            <input type="text" class="form-control form-control-sm m-b-xs" id="filter"
                                   placeholder="Cerca nella tabella">
                            <hr/>
                            <div class="table-responsive">
                               <table class="footable table table-stripped toggle-arrow-tiny"  
                                      data-filter="#filter" data-limit-navigation="3">
                                    <thead>
                                        <tr class="bg-flussi-light">
                                            <th data-toggle="true" class="text-center"></th>
                                            <th class="text-center">
                                                <i class="fa fa-picture-o"></i>
                                            </th>
                                            <th class="text-center">ID#</th>
                                            <th data-toggle="true">
                                                Rge / Lotto
                                            </th>
                                            <th>
                                                Tribunale
                                            </th>
                                            <th>
                                                Indirizzo
                                            </th>
                                            <th class="text-center">
                                                Data Asta
                                            </th>
                                            <th class="text-center">
                                                Base Asta
                                            </th>
                                            <th class="text-center">
                                                Offerta Min.
                                            </th>
                                            <th class="text-center">
                                                Mq
                                            </th>
                                            <th data-hide="all">
                                                Link
                                            </th>
                                            <th data-hide="all">
                                                Tipo Procedura
                                            </th>
                                            <th data-hide="all">
                                                Rito
                                            </th>
                                            <th data-hide="all">
                                                Giudice
                                            </th>
                                            <th data-hide="all">
                                                Delegato
                                            </th>
                                            <th data-hide="all">
                                                Custode
                                            </th>
                                            <th data-hide="all">
                                                Curatore
                                            </th>
                                            <th data-hide="all">
                                                Latitudine
                                            </th>
                                            <th data-hide="all">
                                                Longitudine
                                            </th>
                                            <th data-hide="all">
                                                Testo
                                            </th>
                                            <?php if ($this->userLogged["role"]!="admin") { ?>
                                                <th data-hide="all">
                                                    Rif. Annuncio
                                                </th>
                                                <th data-hide="all">
                                                    Nome Agente
                                                </th>
                                                <th data-hide="all">
                                                    Richiesta visione
                                                </th>
                                            <?php } ?>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Modifica</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($this->asteList as $item) { ?>
                                            <tr>
                                                <td class="text-center"></td>
                                                <td class="text-center">
                                                    <img src="<?php echo $item["immagine_URL"] ?>" style="max-height: 25px;" />
                                                </td>
                                                <td><?php echo $item["id"]; ?></td>
                                                <td><?php echo $item["rge"]." / ".$item["lotto"]; ?></td>
                                                <td><?php echo $item["ComuneTribunale"]." (".$item["SiglaProvTribunale"].")"; ?></td>
                                                <td>
                                                    <?php echo $item["ComuneProvinciaCompleto"]; ?>
                                                    <br>
                                                    <?php echo $item["Strada_testo"] . " ". $item["Indirizzo"]. " ". $item["Civico"]; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php 
                                                    if ($item["dataAsta"]!=Null && $item["dataAsta"]!="0000-00-00") {
                                                        echo date("d/m/y", strtotime($item["dataAsta"])) ; 
                                                    } 
                                                    ?>
                                                </td>
                                                <td class="text-center"><?php echo number_format($item["importoBaseAsta"],2,',','.'); ?></td>
                                                <td class="text-center"><?php echo number_format($item["importoOffertaMinima"],2,',','.');  ?></td>
                                                <td class="text-center"><?php echo $item["MQSuperficie"]; ?></td>
                                                <td>
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
                                                <td><?php echo $item["tipoProcedura"]; ?></td>
                                                <td><?php echo $item["rito"]; ?></td>
                                                <td><?php echo $item["giudice"]; ?></td>
                                                <td><?php echo $item["delegato"]; ?></td>
                                                <td><?php echo $item["custode"]; ?></td>
                                                <td><?php echo $item["curatore"]; ?></td>
                                                <td><?php echo $item["Latitudine"]; ?></td>
                                                <td><?php echo $item["Longitudine"]; ?></td>
                                                <td><?php echo $item["Testo"]; ?></td>
                                                <?php if ($this->userLogged["role"]!="admin") { ?>
                                                    <td>
                                                        <?php echo $item["riferimentoAnnuncio"]; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $item["nomeAgente"]; ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        if ($item["flagRichiestaVisione"]=="false") {
                                                            echo 'No';
                                                        } else {
                                                            echo 'Si ('.date("d/m/y", strtotime($item["dataRichiestaVisione"])).')';
                                                        }
                                                        ?>
                                                    </th>
                                                <?php } ?>
                                                <td class="text-center">
                                                    <?php 
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
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?php echo URL ?>aste/overview?iditem=<?php echo $item["id"] ?>">
                                                        <i class="fa fa-pencil text-flussi-light"></i> <span class=" text-flussi-light">Modifica</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <?php if ($this->userLogged["role"]=="admin") { $num=21;}else{$num=24;} ?>
                                        <td colspan="<?php echo $num;?>">
                                            <ul class="pagination float-right"></ul>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>

        </div>

    </div>

</div>


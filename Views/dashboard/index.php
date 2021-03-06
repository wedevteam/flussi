<div class="wrapper wrapper-content">
    <div class="container">
        <?php
        if ($this->userLogged["role"]=="admin") {
            ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="ibox ">
                                <div class="ibox-title text-center bg-flussi-dark">
                                    <h2 class=" text-center">
                                        AGENZIE
                                    </h2>
                                </div>
                                <div class="ibox-content text-center">
                                    <h1 class="no-margins">
                                        <?php echo $this->agencyNum; ?>
                                    </h1>
                                    <div class="text-center">
                                        <h1 class="text-flussi-light"><i class="fa fa-users" style="color:#6DC4E9;"></i></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ibox ">
                                <div class="ibox-title text-center bg-flussi-dark">
                                    <h2 class=" text-center">
                                        ASTE
                                    </h2>
                                </div>
                                <div class="ibox-content text-center">
                                    <h1 class="no-margins">
                                        <?php echo $this->asteNum; ?>
                                    </h1>
                                    <div class="text-center">
                                        <h1 class="text-flussi-light"><i class="fa fa-home" style="color:#6DC4E9;"></i></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="ibox ">
                                <div class="ibox-title text-center bg-flussi-dark">
                                    <h2 class=" text-center">
                                        IMPORTAZIONI
                                    </h2>
                                </div>
                                <div class="ibox-content text-center">
                                    <h1 class="no-margins">
                                        <?php echo $this->importsNum; ?>
                                    </h1>
                                    <div class="text-center">
                                        <h1 class="text-flussi-light"><i class="fa fa-upload" style="color:#6DC4E9;"></i></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ibox ">
                                <div class="ibox-title text-center bg-flussi-dark">
                                    <h2 class=" text-center">
                                        ESPORTAZIONI
                                    </h2>
                                </div>
                                <div class="ibox-content text-center">
                                    <h1 class="no-margins">
                                        <?php echo $this->exportsNum; ?>
                                    </h1>
                                    <div class="text-center">
                                        <h1 class="text-flussi-light"><i class="fa fa-download" style="color:#6DC4E9;"></i></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ibox " style="height:100%;">
                        <div class="ibox-title text-center bg-flussi-dark">
                            <h2 class=" text-center">
                                Sintesi per Tribunale
                            </h2>
                        </div>
                        <div class="ibox-content text-center">
                            <?php
                            if (sizeof($this->comuniTribunaliList)==0){
                                ?>
                                <p class="text-center alert alert-info">Non ci sono dati</p>
                                <?php
                            } else {
                                ?>
                                <div class="table-responsive">
                                    <table class="footable table table-stripped toggle-arrow-tiny"
                                           data-filter="#filter" data-limit-navigation="2">
                                        <thead>
                                        <tr class="bg-flussi-light">
                                            <th>Tribunale</th>
                                            <th class="text-center" data-type="numeric">Num. Aste</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($this->comuniTribunaliList as $item   ) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $item["nome"]." (".$item["siglaprovincia"].")" ?></td>
                                                    <td class="text-center" data-value="<?php echo $item["numAste"] ?>"><?php echo $item["numAste"] ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2">
                                                    <ul class="pagination float-right"></ul>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <?Php
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title bg-flussi-dark">
                            <h5>Ultime Aste Importate</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li>
                                        <a href="<?php echo URL ?>aste/index" class="dropdown-item">
                                            Vai alle Aste
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <?php if (is_array($this->asteList) || is_object($this->asteList)) { ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr class="bg-flussi-light">
                                            <th class="text-center">Img</th>
                                            <th data-toggle="true" class="text-center">Id#</th>
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
                                            <th class="text-center">Dettaglio</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i=0;
                                        foreach ($this->asteList as $item) {
                                            $i++;
                                            if ($i<=5) {
                                                ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <img src="<?php echo $item["immagine_URL"] ?>" style="max-height: 25px;" />
                                                    </td>
                                                    <?php
                                                    if ($this->userLogged["role"]=="admin") {
                                                        ?>
                                                        <td data-value="<?php echo $item["id"]; ?>">
                                                            <?php echo $item["id"]; ?>
                                                        </td>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td data-value="<?php echo $item["id"]; ?>">
                                                            <?php echo $item["id"]."-".$this->userLogged["id"]; ?>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
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
                                                    <td class="text-center">
                                                        <a href="<?php echo URL ?>aste/overview?iditem=<?php echo $item["id"] ?>">
                                                            <i class="fa fa-pencil text-flussi-light"></i> <span class=" text-flussi-light">Dettaglio</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <p class="text-center">
                                        <a class="btn btn-flussi-light" href="<?php echo URL ?>aste/index">
                                            Vedi tutte
                                        </a>
                                    </p>
                                </div>
                            <?php } else { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="alert alert-info text-center">Non ci sono Aste</p>
                                    </div>
                                </div>
                            <?php } ?>


                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="ibox ">
                        <div class="ibox-title text-center bg-flussi-dark">
                            <h2 class=" text-center">
                                ARCHIVIO ASTE
                            </h2>
                        </div>
                        <div class="ibox-content text-center">
                            <h1 class="no-margins">
                                <?php echo $this->asteNum; ?>
                            </h1>
                            <div class="text-center">
                                <h1 class="text-flussi-light"><i class="fa fa-home" style="color:#6DC4E9;"></i></h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ibox ">
                        <div class="ibox-title text-center bg-flussi-dark">
                            <h2 class=" text-center">
                                ASTE ESPORTATE
                            </h2>
                        </div>
                        <div class="ibox-content text-center">
                            <h1 class="no-margins">
                                <?php echo $this->asteExportNum; ?>
                            </h1>
                            <div class="text-center">
                                <h1 class="text-flussi-light"><i class="fa fa-home" style="color:#6DC4E9;"></i></h1>
                            </div>
                        </div>
                    </div>
                </div>
<!--                <div class="col-md-4">-->
<!--                    <div class="ibox ">-->
<!--                        <div class="ibox-title text-center bg-flussi-dark">-->
<!--                            <h2 class=" text-center">-->
<!--                                ESPORTAZIONI-->
<!--                            </h2>-->
<!--                        </div>-->
<!--                        <div class="ibox-content text-center">-->
<!--                            <h1 class="no-margins">-->
<!--                                --><?php //echo $this->exportsNum; ?>
<!--                            </h1>-->
<!--                            <div class="text-center">-->
<!--                                <h1 class="text-flussi-light"><i class="fa fa-download" style="color:#6DC4E9;"></i></h1>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="col-md-4">
                    <div class="ibox " style="height:100%;">
                        <div class="ibox-title text-center bg-flussi-dark">
                            <h2 class=" text-center">
                                Sintesi per Tribunale
                            </h2>
                        </div>
                        <div class="ibox-content text-center">
                            <?php
                            if (sizeof($this->comuniTribunaliList)==0){
                                ?>
                                <p class="text-center alert alert-info">Non ci sono dati</p>
                                <?php
                            } else {
                                ?>
                                <div class="table-responsive">
                                    <table class="footable table table-stripped toggle-arrow-tiny"
                                           data-filter="#filter" data-limit-navigation="2">
                                        <thead>
                                        <tr class="bg-flussi-light">
                                            <th>Tribunale</th>
                                            <th class="text-center" data-type="numeric">Num. Aste</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($this->comuniTribunaliList as $item   ) {
                                            ?>
                                            <tr>
                                                <td><?php echo $item["nome"]." (".$item["siglaprovincia"].")" ?></td>
                                                <td class="text-center" data-value="<?php echo $item["numAste"] ?>"><?php echo $item["numAste"] ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="2">
                                                <ul class="pagination float-right"></ul>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <?Php
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title bg-flussi-dark">
                            <h5>Ultime Aste inserite</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li>
                                        <a href="<?php echo URL ?>aste/index" class="dropdown-item">
                                            Vai alle Aste
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <?php if (is_array($this->asteList) || is_object($this->asteList)) { ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr class="bg-flussi-light">
                                            <th class="text-center">Img</th>
                                            <th data-toggle="true" class="text-center">Id#</th>
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
                                            <th class="text-center">Dettaglio</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i=0;
                                        foreach ($this->asteList as $item) {
                                            $i++;
                                            if ($i<=5) {
                                                ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <a href="<?php echo URL ?>aste/overview?iditem=<?php echo $item["id"] ?>">
                                                            <img src="<?php echo $item["immagine_URL"] ?>" style="max-height: 25px;" />
                                                        </a>
                                                    </td>
                                                    <?php
                                                    if ($this->userLogged["role"]=="admin") {
                                                        ?>
                                                        <td data-value="<?php echo $item["id"]; ?>">
                                                            <?php echo $item["id"]; ?>
                                                        </td>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td data-value="<?php echo $item["id"]; ?>">
                                                            <?php echo $item["id"]."-".$this->userLogged["id"]; ?>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
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
                                                    <td class="text-center">
                                                        <a href="<?php echo URL ?>aste/overview?iditem=<?php echo $item["id"] ?>">
                                                            <i class="fa fa-pencil text-flussi-light"></i> <span class=" text-flussi-light">Dettaglio</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <p class="text-center">
                                        <a class="btn btn-flussi-light" href="<?php echo URL ?>aste/index">
                                            Vedi tutte
                                        </a>
                                    </p>
                                </div>
                            <?php } else { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="alert alert-info text-center">Non ci sono Aste</p>
                                    </div>
                                </div>
                            <?php } ?>


                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>


    </div>

</div>


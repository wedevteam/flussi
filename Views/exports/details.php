<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>
                            Dettaglio Esportazione 
                            <br>
                            <small>
                                Id# <?php echo $this->data["id"]; ?>
                                | Status:
                                <?php if ( $this->data["status"]=="on"): ?>
                                    <span class="text-white">ESPORTATA</span>
                                <?php endif ?>
                                <?php if ( $this->data["status"]=="off"): ?>
                                    <span class="text-white">NON ESPORTATA</span>
                                <?php endif ?>
                            </small>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <?php if (!is_array($this->detailsList) && !is_object($this->detailsList) ) { ?>
                            <div class="col-md-12 text-center">
                                <p class="alert alert-info">Non ci dati</p>
                            </div>
                        <?php } else { ?>
                            <div class="row">
                                <?php if ($this->data["status"]=="off"): ?>
                                    <div class="col-md-12 text-right">
                                        <form method="POST" action="<?php echo URL ?>exports/details?iditem=<?php echo $this->data["id"] ?>">
                                            <button class="btn btn-flussi-light" name="btnExecuteExport"
                                                    type="submit" value="<?php echo $this->data["id"] ?>">
                                                Esporta
                                            </button>
                                        </form>
                                    </div>
                                <?php endif ?>
                                <?php if ($this->error!=""): ?>
                                    <div class="col-md-12 text-center">
                                        <p class="alert alert-danger"><?php echo $this->error; ?></p>
                                    </div>
                                <?php endif ?>
                                <?php if ($this->message!=""): ?>
                                    <div class="col-md-12 text-center">
                                        <p class="alert alert-success"><?php echo $this->message; ?></p>
                                    </div>
                                <?php endif ?>


                                <div class="col-md-12" style="margin-top:10px;">
                                    <div class="table-responsive">
                                        <table class="footable table table-stripped toggle-arrow-tiny"
                                               data-limit-navigation="3">
                                            <thead>
                                            <tr class="bg-flussi-light">
                                                <th data-toggle="true">Agenzia</th>
                                                <th class="text-center" data-type="numeric"> ID# Asta</th>
                                                <th> Rge/Lotto</th>
                                                <th> Tribunale</th>
                                                <th> Comune</th>
                                                <th> Cap</th>
                                                <th class="text-center" data-type="numeric"> Data Asta</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($this->detailsList as $item) { ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $item["nomeAgenzia"]; ?>
                                                        <a target="_blank" href="<?php echo URL ?>agency/edit?iditem=<?php echo $item["idAgenzia"] ?>">
                                                            <i class="fa fa-external-link"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a target="_blank" href="<?php echo URL ?>aste/edit?iditem=<?php echo $item["IDImmobile"] ?>">
                                                            <?php echo $item["IDImmobile"]; ?><i class="fa fa-external-link"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?php echo $item["rge"]."/".$item["lotto"]; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $item["ComuneTribunale"]." (".$item["SiglaProvTribunale"].")"; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $item["Comune"]." (".$item["Provincia"].")"; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $item["Cap"]; ?>
                                                    </td>
                                                    <td class="text-center" data-value="<?php echo $item["dataAsta"]; ?>">
                                                        <?php echo date("d/m/y", strtotime($item["dataAsta"])); ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="7">
                                                    <ul class="pagination float-right"></ul>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>




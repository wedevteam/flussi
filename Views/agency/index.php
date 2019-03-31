<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>Agenzie (<?php echo $this->numItems?>)</h5>
                    </div>
                    <div class="ibox-content">
                        <?php if (!is_array($this->agencyList) && !is_object($this->agencyList) ) { ?>
                        <div class="col-md-12 text-center">
                            <p class="alert alert-info">Non ci sono Agenzie</p>
                        </div>
                        <?php } else { ?>
                            <div class="table-responsive">
                               <table class="footable table table-stripped toggle-arrow-tiny">
                                    <thead>
                                    <tr>
                                        <th data-toggle="true">Id#</th>
                                        <th>Nome</th>
                                        <th>Referente</th>
                                        <th>Comune</th>
                                        <th data-hide="all">Email</th>
                                        <th data-hide="all">Telefono</th>
                                        <th data-hide="all">Cellulare</th>
                                        <th data-hide="all">Partita Iva</th>
                                        <th class="text-center">Id Gestionale</th>
                                        <th class="text-center">Id Agenzia</th>
                                        <th data-hide="all">N. Annunci</th>
                                        <th data-hide="all">Inizio Contratto</th>
                                        <th data-hide="all">Fine Contratto</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Modifica</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($this->agencyList as $item) { ?>
                                            <tr>
                                                <td><?php echo $item["id"]; ?></td>
                                                <td><?php echo $item["companyName"]; ?></td>
                                                <td><?php echo $item["firstName"]." ".$item["lastName"]; ?></td>
                                                <td><?php echo $item["Comune"] . " (" . $item["SiglaProvincia"] . ")"; ?></td>
                                                <td><?php echo $item["email"]; ?></td>
                                                <td><?php echo $item["Telefono"]; ?></td>
                                                <td><?php echo $item["Cellulare"]; ?></td>
                                                <td><?php echo $item["PartitaIva"]; ?></td>
                                                <td class="text-center"><?php echo $item["IdGestionale"]; ?></td>
                                                <td class="text-center"><?php echo $item["IdAgenzia"]; ?></td>
                                                <td><?php echo $item["NrAnnunci"]; ?></td>
                                                <td><?php echo date("d/m/y", strtotime($item["DataInzio_d"])) ; ?></td>
                                                <td><?php echo date("d/m/y", strtotime($item["DataFine_d"])) ; ?></td>
                                                <td class="text-center">
                                                    <?php 
                                                    if ($item["status"]=='on') {
                                                        ?>
                                                        <i class="fa fa-circle text-success" title="Attiva"></i>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <i class="fa fa-circle text-danger" title="Non Attiva"></i>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?php echo URL ?>agency/edit?iditem=<?php echo $item["id"] ?>">
                                                        <i class="fa fa-pencil text-flussi-light"></i> <span class=" text-flussi-light">Modifica</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="16">
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

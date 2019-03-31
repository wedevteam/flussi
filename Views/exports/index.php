<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>Esportazioni (<?php echo $this->numItems?>)</h5>
                    </div>
                    <div class="ibox-content">
                        <?php if (!is_array($this->exportsList) && !is_object($this->exportsList) ) { ?>
                            <div class="col-md-12 text-center">
                                <p class="alert alert-info">Non ci sono Esportazioni</p>
                            </div>
                        <?php } else { ?>
                            <div class="table-responsive">
                               <table class="footable table table-stripped toggle-arrow-tiny">
                                    <thead>
                                    <tr>
                                        <th data-toggle="true">Id#</th>
                                        <th class="text-center">Creazione</th>
                                        <th class="text-center">Num. Agenzie</th>
                                        <th class="text-center">Num. Aste</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Dettagli</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($this->exportsList as $item) { ?>
                                            <tr>
                                                <td><?php echo $item["id"]; ?></td>
                                                <td class="text-center"><?php echo date("d/m/y H:i:s", strtotime($item["createdAt"])); ?></td>
                                                <td class="text-center"><?php echo $item["numAgenzie"]; ?></td>
                                                <td class="text-center"><?php echo $item["numAste"]; ?></td>
                                                <td class="text-center">
                                                    <?php 
                                                    if ($item["status"]=='on') {
                                                        ?>
                                                        <i class="fa fa-check text-success"></i>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <i class="fa fa-remove text-danger"></i>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?php echo URL ?>exports/details?iditem=<?php echo $item["id"] ?>">
                                                        <i class="fa fa-pencil text-flussi-light"></i> <span class=" text-flussi-light">Dettagli</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="6">
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




<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>Importazioni  (<?php echo $this->numItems?>)</h5>
                    </div>
                    <div class="ibox-content">
                        <?php if (!is_array($this->importsList) && !is_object($this->importsList) ) { ?>
                            <div class="col-md-12 text-center">
                                <p class="alert alert-info">Non ci sono Importazioni</p>
                            </div>
                        <?php } else { ?>
                            <div class="table-responsive">
                               <table class="footable table table-stripped toggle-arrow-tiny"
                                      data-limit-navigation="3">
                                    <thead>
                                    <tr>
                                        <th data-toggle="true">Id#</th>
                                        <th class="text-center">Creazione</th>
                                        <th>File</th>
                                        <th class="text-center">Status</th>
<!--                                        <th class="text-center">Modifica</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($this->importsList as $item) { ?>
                                            <tr>
                                                <td><?php echo $item["id"]; ?></td>
                                                <td class="text-center"><?php echo date("d/m/y H:i:s", strtotime($item["createdAt"])); ?></td>
                                                <td>
                                                    <?php echo $item["fileName"]; ?>
                                                </td>
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
<!--                                                <td class="text-center">
                                                    <a href="#">
                                                        <i class="fa fa-pencil text-flussi-light"></i> Modifica
                                                    </a>
                                                </td>-->
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="4">
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



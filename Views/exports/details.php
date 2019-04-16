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
                            </small>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <?php if (!is_array($this->detailsList) && !is_object($this->detailsList) ) { ?>
                            <div class="col-md-12 text-center">
                                <p class="alert alert-info">Non ci dati</p>
                            </div>
                        <?php } else { ?>
                            <div class="table-responsive">
                               <table class="footable table table-stripped toggle-arrow-tiny"
                                      data-limit-navigation="3">
                                    <thead>
                                    <tr>
                                        <th data-toggle="true">Agenzia</th>
                                        <th>Asta</th>
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
                                                <td>
                                                    <?php echo $item["nomeAsta"]; ?>
                                                    <a target="_blank" href="<?php echo URL ?>aste/edit?iditem=<?php echo $item["idAsta"] ?>">
                                                         <i class="fa fa-external-link"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
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
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>




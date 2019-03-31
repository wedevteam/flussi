<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>Importa file CSV </h5>
                    </div>
                    <div class="ibox-content">
                        <form method="POST"  action="<?php echo URL ?>imports/resultImport" enctype="multipart/form-data">
                            <div class="row">
                                <?php if ($this->error) { ?>
                                    <div class="col-md-12 text-center">
                                        <p class="alert alert-danger"><?php echo $this->error;?></p>
                                    </div>
                                <?php }  ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>File CSV*</label>
                                        <input type="file" placeholder="Email" class="form-control"
                                               required="" name="filecsv" />
                                    </div>
                                </div>
                                
                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <a href="<?php echo URL; ?>imports/index" class="btn btn-default">
                                            Annulla
                                        </a>
                                        <button class="btn btn-flussi-light" type="submit" name="btnImportCsv">
                                            Importa
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


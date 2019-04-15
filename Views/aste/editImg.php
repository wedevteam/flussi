

<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>
                            Dettaglio Asta  
                            <small>
                                <br>
                                Id# <?php echo $this->data["id"]  ?> | Creazione: <?php echo date("d/m/y", strtotime($this->data["DataInserimento_d"])) ;?>
                                <br>
                                Titolo: <?php echo $this->data["Titolo"] ?>
                            </small>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="col-lg-12">
                            <div class="tabs-container">
                                <div class="tabs-left">
                                    <ul class="nav nav-tabs">
                                        <li>
                                            <a class="nav-link" href="<?php echo URL ?>aste/overview?iditem=<?php echo $this->data["id"]; ?>"> 
                                                <i class="fa fa-home"></i> Informazioni
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link"  href="<?php echo URL ?>aste/gallery?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-photo"></i> Gallery
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="<?php echo URL ?>aste/edit?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-pencil"></i> Modifica
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link active" style="color:#6DC4E9;" href="<?php echo URL ?>aste/editImg?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-picture-o"></i> Immagini
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content ">
                                        <div id="tab-6" class="tab-pane active">
                                            <div class="panel-body">
                                                <?php if ($this->userLogged["role"]=="admin") { ?> 
                                                    <form method="POST" action="<?php echo URL ?>aste/executeEditImg?iditem=<?php echo  $this->data["id"] ?>"
                                                                                            enctype="multipart/form-data">
                                                        <div class="row">
                                                              <?php if ($this->error) { ?>
                                                                  <div class="col-md-12 text-center">
                                                                      <p class="alert alert-danger"><strong>Oops...</strong><?php echo $this->error;?></p>
                                                                  </div>
                                                              <?php }  ?>
                                                              <?php if ($this->message) { ?>
                                                                  <div class="col-md-12 text-center">
                                                                      <p class="alert alert-success"><?php echo $this->message;?></p>
                                                                  </div>
                                                              <?php }  ?>
                                                              <div class="col-md-12">
                                                                  <div class="form-group">
                                                                      <label>Immagine Principale:</label>
                                                                      <div class="">
                                                                          <img src="<?php echo $this->data["immagine_URL"]; ?>" style="max-width: 200px !important;" />
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <div class="form-group">
                                                                      <label>Nuova immagine*</label>
                                                                      <input type="file" class="form-control" name="avatar" required="">
                                                                  </div>
                                                              </div>

                                                              <div class="col-md-12 text-right">
                                                                  <div class="form-group">
                                                                      <a href="<?php echo URL; ?>aste/index" class="btn btn-default">
                                                                          Annulla
                                                                      </a>
                                                                      <button class="btn btn-flussi-light" type="submit">
                                                                          Upload Immagine
                                                                      </button>
                                                                  </div>
                                                              </div>
                                                        </div>
                                                    </form>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <h4>Immagini Aggiuntive</h4>
                                                        </div>
                                                    </div>

                                                    <form method="POST" action="<?php echo URL ?>aste/executeEditImagesA?iditem=<?php echo  $this->data["id"] ?>"
                                                          enctype="multipart/form-data">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Nuove immagini*</label>
                                                                    <input type="file" class="form-control" name="avatar[]" required="" multiple="multiple">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 text-right">
                                                                <div class="form-group">
                                                                    <a href="<?php echo URL; ?>aste/index" class="btn btn-default">
                                                                        Annulla
                                                                    </a>
                                                                    <button class="btn btn-flussi-light" type="submit">
                                                                        Upload Immagini
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <?php
                                                    if (is_array($this->relImg) || is_object($this->relImg)) {
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="footable table table-stripped toggle-arrow-tiny"
                                                                           data-filter="#filter" data-limit-navigation="3">
                                                                        <thead>
                                                                        <tr class="bg-flussi-light">
                                                                            <th class="text-center" data-toggle="true">
                                                                                Immagine
                                                                            </th>
                                                                            <th class="text-center">Elimina</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                        foreach ($this->relImg as $img) {
                                                                            ?>
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    <img src="<?php echo $img["immagine_URL"];?>" style="max-width: 100px;">
                                                                                </td>
                                                                                <td class="text-center text-danger">
                                                                                    <?php
                                                                                    if ($img["idAgenzia"]==0) {
                                                                                        ?>
                                                                                        <a href="<?php echo URL ?>aste/removeImgA?iditem=<?php echo  $this->data["id"] ?>&idrel=<?php echo $img["id"]?>">
                                                                                            <span class="text-danger"><i class="fa fa-trash"></i> Elimina</span>
                                                                                        </a>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
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
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>

<!--                                                    --><?php
//                                                    if (is_array($this->relImg) || is_object($this->relImg)) {
//                                                        ?>
<!--                                                        <div class="row">-->
<!--                                                            <div class="col-md-12">-->
<!--                                                                <div class="lightBoxGallery">-->
<!--                                                                    --><?php
//                                                                    foreach ($this->relImg as $img) {
//                                                                        ?>
<!--                                                                        <a href="--><?php //echo $img["immagine_URL"];?><!--" title="Image from Unsplash" data-gallery="">-->
<!--                                                                            <img src="--><?php //echo $img["immagine_URL"];?><!--" style="max-width: 100px;">-->
<!--                                                                        </a>-->
<!--                                                                        --><?php
//                                                                    }
//                                                                    ?>
<!---->
<!--                                                                    <div id="blueimp-gallery" class="blueimp-gallery">-->
<!--                                                                        <div class="slides"></div>-->
<!--                                                                        <h3 class="title"></h3>-->
<!--                                                                        <a class="prev">‹</a>-->
<!--                                                                        <a class="next">›</a>-->
<!--                                                                        <a class="close">×</a>-->
<!--                                                                        <a class="play-pause"></a>-->
<!--                                                                        <ol class="indicator"></ol>-->
<!--                                                                    </div>-->
<!---->
<!--                                                                </div>-->
<!---->
<!--                                                            </div>-->
<!--                                                        </div>-->
<!--                                                        --><?php
//                                                    }
//                                                    ?>



                                                <?php } else { ?> 
                                                    <form method="POST" action="<?php echo URL ?>aste/executeEditImg?iditem=<?php echo  $this->data["id"] ?>&idrel=<?php echo  $this->relAsteAgenzia["id"] ?>" 
                                                        enctype="multipart/form-data">
                                                        <div class="row">
                                                              <?php if ($this->error) { ?>
                                                                  <div class="col-md-12 text-center">
                                                                      <p class="alert alert-danger"><strong>Oops...</strong><?php echo $this->error;?></p>
                                                                  </div>
                                                              <?php }  ?>
                                                              <?php if ($this->message) { ?>
                                                                  <div class="col-md-12 text-center">
                                                                      <p class="alert alert-success"><?php echo $this->message;?></p>
                                                                  </div>
                                                              <?php }  ?>
                                                              <div class="col-md-12">
                                                                  <div class="form-group">
                                                                      <label>Immagine Principale:</label>
                                                                      <div class="">
                                                                          <img src="<?php echo $this->relAsteAgenzia["immagine_URL"]; ?>" style="max-width: 200px !important;" />
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <div class="form-group">
                                                                      <label>Nuova immagine*</label>
                                                                      <input type="file" class="form-control" name="avatar" required="">
                                                                  </div>
                                                              </div>

                                                              <div class="col-md-12 text-right">
                                                                  <div class="form-group">
                                                                      <a href="<?php echo URL; ?>aste/index" class="btn btn-default">
                                                                          Annulla
                                                                      </a>
                                                                      <button class="btn btn-flussi-light" type="submit">
                                                                          Upload Immagine
                                                                      </button>
                                                                  </div>
                                                              </div>
                                                        </div>
                                                    </form>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <h4>Immagini Aggiuntive</h4>
                                                        </div>
                                                    </div>

                                                    <form method="POST" action="<?php echo URL ?>aste/executeEditImages?iditem=<?php echo  $this->data["id"] ?>&idrel=<?php echo  $this->relAsteAgenzia["id"] ?>"
                                                          enctype="multipart/form-data">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Nuove immagini*</label>
                                                                    <input type="file" class="form-control" name="avatar[]" required="" multiple="multiple">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 text-right">
                                                                <div class="form-group">
                                                                    <a href="<?php echo URL; ?>aste/index" class="btn btn-default">
                                                                        Annulla
                                                                    </a>
                                                                    <button class="btn btn-flussi-light" type="submit">
                                                                        Upload Immagini
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <?php
                                                    if (is_array($this->relImg) || is_object($this->relImg)) {
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="footable table table-stripped toggle-arrow-tiny"
                                                                           data-filter="#filter" data-limit-navigation="3">
                                                                        <thead>
                                                                        <tr class="bg-flussi-light">
                                                                            <th class="text-center" data-toggle="true">
                                                                                Immagine
                                                                            </th>
                                                                            <th class="text-center">Elimina</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php foreach ($this->relImg as $img) {
                                                                            if ($img["immagine_Posizione"]!=0) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td class="text-center">
                                                                                        <img src="<?php echo $img["immagine_URL"];?>" style="max-width: 100px;">
                                                                                    </td>
                                                                                    <td class="text-center text-danger">
                                                                                        <?php
                                                                                        if ($img["fonte"]=="manuale" && $img["idAgenzia"]==$this->userLogged["id"]) {
                                                                                            ?>
                                                                                            <a href="<?php echo URL ?>aste/removeImgAgency?iditem=<?php echo  $this->data["id"] ?>&idrel=<?php echo $img["id"]?>">
                                                                                                <span class="text-danger"><i class="fa fa-trash"></i> Elimina</span>
                                                                                            </a>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                                }
                                                                            } ?>
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
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                <?php } ?> 
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>



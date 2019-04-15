

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
                                            <a class="nav-link active" style="color:#6DC4E9;" href="<?php echo URL ?>aste/gallery?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-photo"></i> Gallery
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="<?php echo URL ?>aste/edit?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-pencil"></i> Modifica
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="<?php echo URL ?>aste/editImg?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-picture-o"></i> Immagini
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content ">
                                        <div id="tab-6" class="tab-pane active">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <h4>Gallery</h4>
                                                    </div>
                                                </div>
                                                <?php
                                                if (is_array($this->relImg) || is_object($this->relImg)) {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="lightBoxGallery">
                                                                <?php
                                                                foreach ($this->relImg as $img) {
                                                                    ?>
                                                                    <a href="<?php echo $img["immagine_URL"];?>" title="Image from Unsplash" data-gallery="">
                                                                        <img src="<?php echo $img["immagine_URL"];?>" style="max-width: 100px;">
                                                                    </a>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <div id="blueimp-gallery" class="blueimp-gallery">
                                                                    <div class="slides"></div>
                                                                    <h3 class="title"></h3>
                                                                    <a class="prev">‹</a>
                                                                    <a class="next">›</a>
                                                                    <a class="close">×</a>
                                                                    <a class="play-pause"></a>
                                                                    <ol class="indicator"></ol>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <?php
                                                    }
                                                    ?>
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



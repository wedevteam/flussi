

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
                                            <a class="nav-link active" style="color:#6DC4E9;" href="<?php echo URL ?>aste/overview?iditem=<?php echo $this->data["id"]; ?>"> 
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
                                            <a class="nav-link" href="<?php echo URL ?>aste/editImg?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-picture-o"></i> Immagini
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content ">
                                        <div id="tab-6" class="tab-pane active">
                                            <div class="panel-body">
                                                <?php 
                                                if ($this->userLogged["role"]=="admin") {
                                                    ?>
                                                    <div class="row" style="margin-bottom: 10px;">
                                                        <div class="col-md-12 text-right">
                                                            <form method="POST" action="<?php echo URL ?>aste/removeAsta?iditem=<?php echo  $this->data["id"] ?>">
                                                                <button class="btn btn-danger btn-xs confirmRemoveAsta" type="button">
                                                                    <i class="fa fa-trash"></i> Elimina
                                                                </button>
                                                                <input hidden type="submit" id="btnRemoveAsta" name="btnRemoveAsta" value="<?php echo  $this->data["id"] ?>">
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <form method="POST"  action="<?php echo URL ?>aste/executeEdit?iditem=<?php echo  $this->data["id"] ?>" 
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
                                                        <div class="col-md-6">
                                                            <h5><strong>DATI ASTA</strong></h5>
                                                            <address>
                                                                  <strong>Rge: </strong><?php echo $this->data["rge"] ?>
                                                                  <br>
                                                                  <strong>Lotto: </strong><?php echo $this->data["lotto"] ?>
                                                                  <br>
                                                                  <strong>Tribunale: </strong><?php echo $this->data["ComuneTribunale"]." (".$this->data["SiglaProvTribunale"].")" ?> <small>(Codice: <?php echo $this->data["codiceComuneTribunale"] ?>)</small>
                                                                  <br>
                                                                  <strong>Comune: </strong><?php echo $this->data["ComuneProvinciaCompleto"] ?> <small>(Codice: <?php echo $this->data["CodiceComune"] ?>)</small>
                                                                  <br>
                                                                  <strong>Indirizzo: </strong><?php echo $this->data["Strada_testo"]." ".$this->data["Indirizzo"]." ".$this->data["Civico"] ?>
                                                                  <br>
                                                                  <strong>Cap: </strong><?php echo $this->data["Cap"] ?>
                                                                  <br>
                                                                  <strong>Tipo Procedura: </strong><?php echo $this->data["tipoProcedura"] ?>
                                                                  <br>
                                                                  <strong>Rito: </strong><?php echo $this->data["rito"] ?>
                                                                  <br>
                                                                  <strong>Tipologia: </strong>Immobili Residenziali<?php //echo $this->data["IDTipologia"] ?>
                                                                  <br>
                                                                  <strong>
                                                                      <a href="<?php echo URL ?>aste/gallery?iditem=<?php echo $this->data["id"]; ?>">
                                                                          Galleria Immagini
                                                                      </a>
                                                                  </strong>
                                                                  <?php
//                                                                  if ( is_array($this->relImg) || is_object($this->relImg) ) {
//                                                                      foreach($this->relImg as $img){
//                                                                          ?>
<!--                                                                          <br>-->
<!--                                                                          <a href="--><?php //echo $img["immagine_URL"]; ?><!--" target="_blank"">-->
<!--                                                                          --><?php //echo substr($img["immagine_URL"],0,15)."..."; ?><!--<i class="fa fa-external-link"></i>-->
<!--                                                                          </a>-->
<!--                                                                        --><?php
//                                                                      }
//                                                                  } else {
//                                                                      if ($this->userLogged["role"]=="admin") {
//    //                                                                    ?>
<!--                                                                          <a href="--><?php //echo $this->data["immagine_URL"]; ?><!--" target="_blank">-->
<!--                                                                              --><?php //echo substr($this->data["immagine_URL"],0,15)."..."; ?><!--<i class="fa fa-external-link"></i>-->
<!--                                                                          </a>-->
<!--                                                                          --><?php
//                                                                      } else {
//                                                                            ?>
<!--                                                                            <a href="--><?php //echo $this->relAsteAgenzia["immagine_URL"]; ?><!--" target="_blank">-->
<!--                                                                                --><?php //echo substr($this->relAsteAgenzia["immagine_URL"],0,15)."..."; ?><!--<i class="fa fa-external-link"></i>-->
<!--                                                                            </a>-->
<!--                                                                            --><?php
//                                                                      }
//                                                                  }
                                                                  ?>
                                                            </address>
                                                          </div>
                                                        <?php
                                                        if ($this->userLogged["role"]=="admin") {
                                                            if ($this->data["immagine_URL"]!="") {
                                                                ?>
                                                                <div class="col-md-6 text-center">
                                                                    <br>
                                                                    <img src="<?php echo $this->data["immagine_URL"]; ?>" style="max-width: 400px !important;" />
                                                                </div>
                                                                <?php
                                                            }
                                                        } else {
                                                            if ($this->relAsteAgenzia["immagine_URL"]!="") {
                                                                ?>
                                                                <div class="col-md-6 text-center">
                                                                    <img src="<?php echo $this->relAsteAgenzia["immagine_URL"]; ?>" 
                                                                         style="max-width: 400px !important;" />
                                                                </div>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        <div class="col-md-6">
                                                            <br>
                                                            <address>
                                                                <strong>Valore Perizia: </strong>€ <?php echo number_format($this->data["valorePerizia"],2,',','.') ?>
                                                                <br>
                                                                <strong>Base Asta: </strong>€ <?php echo number_format($this->data["importoBaseAsta"],2,',','.') ?>
                                                                <br>
                                                                <strong>Offerta Minima: </strong>€ <?php echo number_format($this->data["importoOffertaMinima"],2,',','.') ?>
                                                                <br>
                                                                <strong>Data Asta: </strong><?php echo date("d/m/y", strtotime($this->data["dataAsta"])) ?>
                                                                <br>
                                                                <strong>Data Pubblicazione: </strong><?php echo date("d/m/y", strtotime($this->data["dataPubblicazione"])) ?>
                                                                <br>
                                                                <strong>Link Tribunale: </strong>
                                                                <?php 
                                                                if ($this->data["linkTribunale"]!="") {
                                                                  ?>
                                                                  <a href="<?php echo $this->data["linkTribunale"]; ?>" target="_blank">
                                                                      <?php echo substr($this->data["linkTribunale"],0,15)."..."; ?><i class="fa fa-external-link"></i>
                                                                  </a>
                                                                  <?php
                                                                } else {
                                                                    ?>
                                                                    <span>-</span>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Link Planimetria: </strong>
                                                                <?php 
                                                                if ($this->data["URLPlanimetria"]!="") {
                                                                  ?>
                                                                  <a href="<?php echo $this->data["URLPlanimetria"]; ?>" target="_blank">
                                                                      <?php echo substr($this->data["URLPlanimetria"],0,15)."..."; ?><i class="fa fa-external-link"></i>
                                                                  </a>
                                                                  <?php
                                                                } else {
                                                                    ?>
                                                                    <span>-</span>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Link Virtual Tour: </strong>
                                                                <?php 
                                                                if ($this->data["URLVirtualTour"]!="") {
                                                                  ?>
                                                                  <a href="<?php echo $this->data["URLVirtualTour"]; ?>" target="_blank">
                                                                      <?php echo substr($this->data["URLVirtualTour"],0,15)."..."; ?><i class="fa fa-external-link"></i>
                                                                  </a>
                                                                  <?php
                                                                } else {
                                                                    ?>
                                                                    <span>-</span>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Link Video: </strong>
                                                                <?php 
                                                                if ($this->data["URLVideo"]!="") {
                                                                  ?>
                                                                  <a href="<?php echo $this->data["URLVideo"]; ?>" target="_blank">
                                                                      <?php echo substr($this->data["URLVideo"],0,15)."..."; ?><i class="fa fa-external-link"></i>
                                                                  </a>
                                                                  <?php
                                                                } else {
                                                                    ?>
                                                                    <span>-</span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </address>
                                                        </div>
                                                        <?php
                                                        if ( $this->data["linkAllegati"]!="" ) {
                                                            $arrLink = explode("~", $this->data["linkAllegati"]);
                                                            ?>
                                                            <div class="col-md-6">
                                                                <br>
                                                                <address>
                                                                    <strong>Link Allegati: </strong><br>
                                                                    <?php 
                                                                    foreach ($arrLink as $link ){
                                                                        if (isset($link) && trim($link) != '') {
                                                                            ?>
                                                                          <a href="<?php echo $link; ?>" target="_blank">
                                                                              <?php
                                                                              $position = strrpos($link, '/');
                                                                              ?>
                                                                              <?php // echo substr($link,0,20)."..."; ?>
                                                                              <?php echo substr($link,$position+1, strlen($link)); ?><i class="fa fa-external-link"></i>
                                                                                  
                                                                          </a>
                                                                          <br>
                                                                          <?php
                                                                        }
                                                                    } 
                                                                    ?>
                                                                </address>
                                                            </div>
                                                            <?php
                                                        }
                                                        if ($this->data["Latitudine"]!="" && $this->data["Longitudine"]!="") {
                                                            ?>
                                                            <!--<div class="col-md-12">
                                                                <br>
                                                                <div class="google-map" id="pano" style="height: 250px"></div>
                                                            </div>-->
                                                            <div class="col-md-12">
                                                                <br>
                                                                <div class="google-map" id="map1" style="height:250px;"></div>
                                                            </div>

                                                            <?php
                                                        }
                                                        ?>
                                                        
                                                        
                                                        
<!--                // ======================================================IMMAGINI
                //foto28
                iif (isset($row[28]) && trim($row[28]) != '') {
                    $arrImg = array();
                    $arrImg = split("~", $row[28]);
                    foreach ($arrImg as $img ){
                        if (isset($img) && trim($img) != '') {
                            $immImmagine = new ImmobileDocs($db);
                            $immImmagine->dataCreazione = date("Y-m-d H:i:s");
                            $immImmagine->type = "img";
                            $immImmagine->idImmobile = $idRecord;
                            $immImmagine->filePath = $img;
                            $immImmagine->status = "on";
                            $immImmagine->createNew();
                        }
                    }
                }-->
                                                      
                                                      
                                                      <div class="col-md-12">
                                                          <hr>
                                                          <address>
                                                                <strong>Giudice:</strong>
                                                                <?php 
                                                                if ($this->data["giudice"]!=""){
                                                                    echo '<br>'.$this->data["giudice"];
                                                                } else {
                                                                    echo '-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Delegato:</strong>
                                                                <?php 
                                                                if ($this->data["delegato"]!=""){
                                                                    echo '<br>'.$this->data["delegato"];
                                                                } else {
                                                                    echo '-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Custode:</strong>
                                                                <?php 
                                                                if ($this->data["custode"]!=""){
                                                                    echo '<br>'.$this->data["custode"];
                                                                } else {
                                                                    echo '-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Curatore:</strong>
                                                                <?php 
                                                                if ($this->data["curatore"]!=""){
                                                                    echo '<br>'.$this->data["curatore"];
                                                                } else {
                                                                    echo '-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Note Aggiuntive:</strong>
                                                                <?php 
                                                                if ($this->data["noteAggiuntive"]!=""){
                                                                    echo '<br>'.$this->data["noteAggiuntive"];
                                                                } else {
                                                                    echo '-';
                                                                }
                                                                ?>
                                                          </address>
                                                      </div>
                                                      <div class="col-md-12">
                                                          <hr>
                                                          <h5><strong>DETTAGLIO IMMOBILE</strong></h5>
                                                          <address>
                                                                <strong>Superficie: </strong>
                                                                <?php 
                                                                if ($this->data["MQSuperficie"]>0){
                                                                    echo '<br>'.$this->data["MQSuperficie"].' Mq';
                                                                } else {
                                                                    echo '<br><small>non definita</small>';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Dati Catastali:</strong>
                                                                <?php 
                                                                if ($this->data["datiCatastali"]!=""){
                                                                    echo '<br>'.$this->data["datiCatastali"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Classe Catastale:</strong>
                                                                <?php 
                                                                if ($this->data["ClasseCatastale"]!=""){
                                                                    echo '<br>'.$this->data["ClasseCatastale"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Rendita Catastale:</strong>
                                                                <?php 
                                                                if ($this->data["RenditaCatastale"]>0){
                                                                    echo '<br>€ '.$this->data["RenditaCatastale"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                          </address>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <address>
                                                                <strong>Piano: </strong>
                                                                <?php 
                                                                if ($this->data["Piano"]>0){
                                                                    echo '<br>'.$this->data["Piano"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>PianoFuoriTerra: </strong>
                                                                <?php 
                                                                if ($this->data["PianoFuoriTerra"]>0){
                                                                    $PianoFuoriTerraArr = unserialize (GX_PIANOFUORITERRA);
                                                                    for ($i=0; $i<= sizeof($PianoFuoriTerraArr);$i++){
                                                                        if ( $i == $this->data["PianoFuoriTerra"]) {
                                                                             echo '<br>'.$PianoFuoriTerraArr[$i];
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Piani Edificio: </strong>
                                                                <?php 
                                                                if ($this->data["PianiEdificio"]>0){
                                                                    echo '<br>'.$this->data["PianiEdificio"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                  <br>
                                                                  <strong>Totale Locali: </strong>
                                                                  <?php
                                                                  if ($this->data["NrLocali"]>0){
                                                                      echo '<br>'.$this->data["NrLocali"];
                                                                  } else {
                                                                      echo '<br>-';
                                                                  }
                                                                  ?>
                                                                <br>
                                                                <strong>Camere da Letto: </strong>
                                                                <?php 
                                                                if ($this->data["NrCamereLetto"]>0){
                                                                    echo '<br>'.$this->data["NrCamereLetto"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Altre Camere: </strong>
                                                                <?php 
                                                                if ($this->data["NrAltreCamere"]>0){
                                                                    echo '<br>'.$this->data["NrAltreCamere"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Bagni: </strong>
                                                                <?php 
                                                                if ($this->data["NrBagni"]>0){
                                                                    echo '<br>'.$this->data["NrBagni"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Cucina: </strong>
                                                                <?php 
                                                                if ($this->data["Cucina"]>0){
                                                                    if($this->data["Cucina"]==255){
                                                                        echo '<br>Assente';
                                                                    } else {
                                                                        $CucinaArr = unserialize (GX_CUCINA);
                                                                        for ($i=0; $i<= sizeof($CucinaArr);$i++){
                                                                            if ( $i == $this->data["Cucina"]) {
                                                                                 echo '<br>'.$CucinaArr[$i];
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Terrazzi: </strong>
                                                                <?php 
                                                                if ($this->data["NrTerrazzi"]>0){
                                                                    echo '<br>'.$this->data["NrTerrazzi"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Balconi: </strong>
                                                                <?php 
                                                                if ($this->data["NrBalconi"]>0){
                                                                    echo '<br>'.$this->data["NrBalconi"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Ascensore: </strong>
                                                                <?php 
                                                                if ($this->data["Ascensore"]=="false"){
                                                                    echo '<br>Assente';
                                                                } else {
                                                                    echo '<br>Presente';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Ascensori: </strong>
                                                                <?php 
                                                                if ($this->data["NrAscensori"]>0){
                                                                    echo '<br>'.$this->data["NrAscensori"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Box Auto: </strong>
                                                                <?php 
                                                                if ($this->data["BoxAuto"]>0){
                                                                    if($this->data["BoxAuto"]==255){
                                                                        echo '<br>Assente';
                                                                    } else {
                                                                        $BoxAutoArr = unserialize (GX_BOXAUTO);
                                                                        for ($i=0; $i<= sizeof($BoxAutoArr);$i++){
                                                                            if ( $i == $this->data["BoxAuto"]) {
                                                                                 echo '<br>'.$BoxAutoArr[$i];
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                          </address>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <address>
                                                              <strong>Box Incluso: </strong>
                                                              <?php
                                                              if ($this->data["BoxIncluso"]=="false"){
                                                                  echo '<br>Assente';
                                                              } else {
                                                                  echo '<br>Presente';
                                                              }
                                                              ?>
                                                              <br>
                                                                <strong>Num. Box: </strong>
                                                                <?php 
                                                                if ($this->data["NrBox"]>0){
                                                                    echo '<br>'.$this->data["NrBox"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Posti Auto: </strong>
                                                                <?php 
                                                                if ($this->data["NrPostiAuto"]>0){
                                                                    echo '<br>'.$this->data["NrPostiAuto"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Cantina: </strong>
                                                                <?php 
                                                                $CantinaArr = unserialize (GX_CANTINA);
                                                                for ($i=0; $i<= sizeof($CantinaArr);$i++){
                                                                    if ( $i == $this->data["Cantina"]) {
                                                                         echo '<br>'.$CantinaArr[$i];
                                                                    }
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Portineria: </strong>
                                                                <?php 
                                                                if ($this->data["Portineria"]=="false"){
                                                                    echo '<br>Assente';
                                                                } else {
                                                                    echo '<br>Presente';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Giardino Cond.: </strong>
                                                                <?php 
                                                                if ($this->data["GiardinoCondominiale"]=="false"){
                                                                    echo '<br>Assente';
                                                                } else {
                                                                    echo '<br>Presente';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Giardino Privato: </strong>
                                                                <?php 
                                                                $GiardinoPrivatoArr = unserialize (GX_GIARDINOPRIVATO);
                                                                for ($i=0; $i<= sizeof($GiardinoPrivatoArr);$i++){
                                                                    if ( $i == $this->data["GiardinoPrivato"]) {
                                                                         echo '<br>'.$GiardinoPrivatoArr[$i];
                                                                    }
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Aria Condizionata: </strong>
                                                                <?php 
                                                                if ($this->data["AriaCondizionata"]>0){
                                                                    if($this->data["AriaCondizionata"]==255){
                                                                        echo '<br>Assente';
                                                                    } else {
                                                                        $AriaCondizionataArr = unserialize (GX_ARIACONDIZIONATA);
                                                                        for ($i=0; $i<= sizeof($AriaCondizionataArr);$i++){
                                                                            if ( $i == $this->data["AriaCondizionata"]) {
                                                                                 echo '<br>'.$AriaCondizionataArr[$i];
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Riscaldamento: </strong>
                                                                <?php 
                                                                if ($this->data["Riscaldamento"]>0){
                                                                    if($this->data["Riscaldamento"]==255){
                                                                        echo '<br>Assente';
                                                                    } else {
                                                                        $RiscaldamentoArr = unserialize (GX_RISCALDAMENTO);
                                                                        for ($i=0; $i<= sizeof($RiscaldamentoArr);$i++){
                                                                            if ( $i == $this->data["Riscaldamento"]) {
                                                                                 echo '<br>'.$RiscaldamentoArr[$i];
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Tipo Impianto Risc.: </strong>
                                                                <?php 
                                                                $TipoImpiantoRiscaldamentoArr = unserialize (GX_TIMPOIMPIANTORISCALDAMENTO);
                                                                for ($i=0; $i<= sizeof($TipoImpiantoRiscaldamentoArr);$i++){
                                                                    if ( $i == $this->data["TipoImpiantoRiscaldamento"]) {
                                                                         echo '<br>'.$TipoImpiantoRiscaldamentoArr[$i];
                                                                    }
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Tipo Risc.: </strong>
                                                                <?php 
                                                                if ($this->data["TipoRiscaldamento"]>0){
                                                                    if($this->data["TipoRiscaldamento"]==255){
                                                                        echo '<br>Altro';
                                                                    } else {
                                                                        $TipoRiscaldamentoArr = unserialize (GX_TIPORISCALDAMENTO);
                                                                        for ($i=0; $i<= sizeof($TipoRiscaldamentoArr);$i++){
                                                                            if ( $i == $this->data["TipoRiscaldamento"]) {
                                                                                 echo '<br>'.$TipoRiscaldamentoArr[$i];
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Spese Risc.: </strong>
                                                                <?php 
                                                                if ($this->data["SpeseRiscaldamento"]>0){
                                                                    echo '<br>'.$this->data["SpeseRiscaldamento"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Arredamento: </strong><br>Assente<?php //echo $this->data["Arredamento"] ?>
                                                          </address>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <address>
                                                                <strong>Stato Arredamento: </strong><br>Buono<?php //echo $this->data["StatoArredamento"] ?>
                                                                <br>
                                                                <strong>Anno Costruzione: </strong>
                                                                <?php 
                                                                if ($this->data["AnnoCostruzione"]>0){
                                                                    echo '<br>'.$this->data["AnnoCostruzione"];
                                                                } else {
                                                                    echo '<br>-';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Tipo Costruzione: </strong><br>Medio Signorile<?php //echo $this->data["TipoCostruzione"] ?>
                                                                <br>
                                                                <strong>Stato Costruzione: </strong><br>Buono<?php //echo $this->data["StatoCostruzione"] ?>
                                                                <br>
                                                                <strong>Allarme: </strong>
                                                                <?php 
                                                                if ($this->data["Allarme"]=="false"){
                                                                    echo '<br>Assente';
                                                                } else {
                                                                    echo '<br>Presente';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Piscina: </strong>
                                                                <?php 
                                                                if ($this->data["Piscina"]=="false"){
                                                                    echo '<br>Assente';
                                                                } else {
                                                                    echo '<br>Presente';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Tennis: </strong>
                                                                <?php 
                                                                if ($this->data["Tennis"]=="false"){
                                                                    echo '<br>Assente';
                                                                } else {
                                                                    echo '<br>Presente';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Caminetto: </strong>
                                                                <?php 
                                                                if ($this->data["Caminetto"]=="false"){
                                                                    echo '<br>Assente';
                                                                } else {
                                                                    echo '<br>Presente';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Idromassaggio: </strong>
                                                                <?php 
                                                                if ($this->data["Idromassaggio"]=="false"){
                                                                    echo '<br>Assente';
                                                                } else {
                                                                    echo '<br>Presente';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>VideoCitofono: </strong>
                                                                <?php 
                                                                if ($this->data["VideoCitofono"]=="false"){
                                                                    echo '<br>Assente';
                                                                } else {
                                                                    echo '<br>Presente';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Fibra Ottica: </strong>
                                                                <?php 
                                                                if ($this->data["FibraOttica"]=="false"){
                                                                    echo '<br>Assente';
                                                                } else {
                                                                    echo '<br>Presente';
                                                                }
                                                                ?>
                                                                <br>
                                                                <strong>Classe Energetica: </strong><br><?php echo $this->data["ClasseEnergetica"] ?>
                                                          </address>
                                                      </div>
                                                      <div class="col-md-12">
                                                          <hr>
                                                          <address>
                                                                <strong>Testo: </strong>
                                                                <br>
                                                                <?php echo $this->data["Testo"] ?>
                                                          </address>
                                                      </div>
                                                      
                                                  </div>

                                              </form>
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





<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>Importa file CSV </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="alert alert-info">
                                    Numero Aste totali <?php echo $this->numAsteTot;?> (Valide: <?php echo $this->numAsteValide; ?>)
                                </p>
                            </div>
                            <div class="col-md-12 text-right">
                                <form method="POST"  action="<?php echo URL ?>imports/saveImport" enctype="multipart/form-data">
                                    <input type="hidden" name="target_file" value="<?php echo $this->target_file ;?>">
                                    <div class="form-group">
                                        <a href="<?php echo URL; ?>imports/create" class="btn btn-default">
                                            Abbandona
                                        </a>
                                        <button class="btn btn-flussi-light" type="submit" name="btnImport">
                                            Salva
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <?php if (!is_array($this->arrAsteList) && !is_object($this->arrAsteList) ) { ?>
                                <div class="col-md-12 text-center">
                                    <p class="alert alert-info">Non ci sono Records</p>
                                </div>
                            <?php } else { ?>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                       <table class="footable table table-stripped toggle-arrow-tiny"
                                               data-limit-navigation="5" data-page-size="8">
                                            <thead>
                                            <tr>
                                                <th data-toggle="true">
                                                    Rge
                                                </th>
                                                <th>
                                                    Lotto
                                                </th>
                                                <th>
                                                    Tribunale
                                                </th>
                                                <th>
                                                    Comune
                                                </th>
                                                <th>
                                                    Strada - Indirizzo - Civico
                                                </th>
                                                <th class="text-center" data-type="numeric">
                                                    Data Asta
                                                </th>
                                                <th class="text-center" data-type="numeric">
                                                    Base Asta
                                                </th>
                                                <th class="text-center" data-type="numeric">
                                                    Offerta Min.
                                                </th>
                                                <th class="text-center" data-type="numeric">
                                                    Mq
                                                </th>
                                                <th data-hide="all">
                                                    Titolo
                                                </th>
                                                <th data-hide="all">
                                                    Link Tribunale
                                                </th>
                                                <th data-hide="all">
                                                    Link Allegati
                                                </th>
                                                <th data-hide="all">
                                                    Tipo Procedura
                                                </th>
                                                <th data-hide="all">
                                                    Rito
                                                </th>
                                                <th data-hide="all">
                                                    Giudice
                                                </th>
                                                <th data-hide="all">
                                                    Delegato
                                                </th>
                                                <th data-hide="all">
                                                    Custode
                                                </th>
                                                <th data-hide="all">
                                                    Curatore
                                                </th>
                                                <th data-hide="all">
                                                    Latitudine
                                                </th>
                                                <th data-hide="all">
                                                    Longitudine
                                                </th>
                                                <th data-hide="all">
                                                    Valore Perizia
                                                </th>
                                                <th data-hide="all">
                                                    Data Pubblicazione
                                                </th>
                                                <th data-hide="all">
                                                    Dati Catastali
                                                </th>
                                                <th data-hide="all">
                                                    Dettagli Immobile
                                                </th>
                                                <th data-hide="all">
                                                    Immagine
                                                </th>
                                                <th data-hide="all">
                                                    Descrizione
                                                </th>
                                                <th class="text-center">Tipo</th>
                                                <th class="text-center">Valida</th>
                                                <th>Errori</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($this->arrAsteList as $item) { ?>
                                                    <tr>
                                                        <td><?php echo $item->rge; ?></td>
                                                        <td><?php echo $item->lotto; ?></td>
                                                        <td>
                                                            <?php 
                                                            if ($item->ComuneTribunale!="" && $item->ComuneTribunale!=NULL) {
                                                                echo $item->ComuneTribunale." (".$item->SiglaProvTribunale.")";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $item->ComuneProvinciaCompleto; ?></td>
                                                        <td>
                                                            <?php echo $item->IndirizzoCompleto // $item->Strada_testo . " ". $item->Indirizzo. " ". $item->Civico; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($item->dataAsta!=Null) {echo date("d/m/y", strtotime($item->dataAsta));} ?>
                                                        </td>
                                                        <td class="text-center"><?php echo number_format($item->importoBaseAsta,2,',','.'); ?></td>
                                                        <td class="text-center"><?php echo number_format($item->importoOffertaMinima,2,',','.');  ?></td>
                                                        <td class="text-center" data-type="numeric" data-value="<?php echo $item->MQSuperficie; ?>"><?php echo $item->MQSuperficie; ?></td>
                                                        <td><?php echo $item->Titolo; ?></td>
                                                        <td>
                                                            <?php 
                                                            if ($item->linkTribunale!="") {
                                                                ?>
                                                                <a href="<?php echo $item->linkTribunale; ?>" target="_blank">
                                                                    <?php echo $item->linkTribunale; ?>
                                                                </a>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                            if ($item->linkAllegati!="") {
                                                                ?>
                                                                <a href="<?php echo $item->linkAllegati; ?>" target="_blank">
                                                                    <?php echo $item->linkAllegati; ?>
                                                                </a>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $item->tipoProcedura; ?></td>
                                                        <td><?php echo $item->rito; ?></td>
                                                        <td><?php echo $item->giudice; ?></td>
                                                        <td><?php echo $item->delegato; ?></td>
                                                        <td><?php echo $item->custode; ?></td>
                                                        <td><?php echo $item->curatore; ?></td>
                                                        <td><?php echo $item->Latitudine; ?></td>
                                                        <td><?php echo $item->Longitudine; ?></td>
                                                        <td>
                                                            <?php if ($item->valorePerizia!=0) {echo $item->valorePerizia;} ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($item->dataPubblicazione!=Null) {echo date("d/m/y", strtotime($item->dataPubblicazione));} ?>
                                                        </td>
                                                        <td><?php echo $item->datiCatastali; ?></td>
                                                        <td>
                                                            Num. Locali:<?php echo $item->NrLocali; ?>
                                                            | Num. Camere Letto:<?php echo $item->NrCamereLetto; ?>
                                                            | Num. altre Camere:<?php echo $item->NrAltreCamere; ?>
                                                            | Num. altre Camere:<?php echo $item->NrAltreCamere; ?>
                                                            | Num. Bagni:<?php echo $item->NrBagni; ?>
                                                            | Cucina:<?php echo $item->CucinaDesc; ?>
                                                            | Num. Terrazzi:<?php echo $item->NrTerrazzi; ?>
                                                            | Num. NrBalconi:<?php echo $item->NrBalconi; ?>
                                                            | Ascensore:<?php echo $item->Ascensore; ?>
                                                            | Num. Ascensori:<?php echo $item->NrAscensori; ?>
                                                            | BoxAuto:<?php echo $item->BoxAuto; ?>
                                                            | BoxIncluso:<?php echo $item->BoxIncluso; ?>
                                                            | Num. Box:<?php echo $item->NrBox; ?>
                                                            | Num. Posti Auto:<?php echo $item->NrPostiAuto; ?>
                                                            | Cantina:<?php echo $item->Cantina; ?>
                                                            | Portineria:<?php echo $item->Portineria; ?>
                                                            | Giardino Cond.:<?php echo $item->GiardinoCondominiale; ?>
                                                            | Giardino Privato:<?php echo $item->GiardinoPrivato; ?>
                                                            | Aria Condizionata:<?php echo $item->AriaCondizionata; ?>
                                                            | Riscaldamento:<?php echo $item->Riscaldamento; ?>
                                                            | Tipo Impianto Risc.:<?php echo $item->TipoImpiantoRiscaldamento; ?>
                                                            | Tipo Risc.:<?php echo $item->TipoRiscaldamento; ?>
                                                            | Spese Risc.:<?php echo $item->SpeseRiscaldamento; ?>
                                                            | Allarme:<?php echo $item->Allarme; ?>
                                                            | Piscina:<?php echo $item->Piscina; ?>
                                                            | Tennis:<?php echo $item->Tennis; ?>
                                                            | Caminetto:<?php echo $item->Caminetto; ?>
                                                            | Idromassaggio:<?php echo $item->Idromassaggio; ?>
                                                            | VideoCitofono:<?php echo $item->VideoCitofono; ?>
                                                            | FibraOttica:<?php echo $item->FibraOttica; ?>
                                                            | ClasseEnergetica:<?php echo $item->ClasseEnergetica; ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                            if ($item->immagine_URL!="") {
                                                                ?>
                                                                <a href="<?php echo $item->immagine_URL; ?>" target="_blank">
                                                                    <?php echo $item->immagine_URL; ?>
                                                                </a>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $item->Testo; ?></td>
                                                        <td class="text-center">
                                                            <?php 
                                                            if ($item->uploadType=='uploadType') {
                                                                ?>
                                                                <span class="text-success">
                                                                    <?php echo $item->uploadType;?>
                                                                </span>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <span class="text-danger">
                                                                    <?php echo $item->uploadType;?>
                                                                </span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php 
                                                            if ($item->status=='on') {
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
                                                        <td><?php echo $item->uploadErrorsTxt; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="29">
                                                    <ul class="pagination float-right"></ul>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                                
                                
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>



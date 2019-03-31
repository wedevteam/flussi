<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title bg-flussi-dark">
                        <h5>
                            Modifica Agenzia  
                            <small>
                                <br>
                                Id# <?php echo $this->data["id"]  ?> | Creazione: <?php echo date("d/m/y", strtotime($this->data["CreatedAt"])) ;?> | Ultima modifica: <?php echo date("d/m/y", strtotime($this->data["lastEdit"])) ;?>
                            </small>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="col-lg-12">
                            <div class="tabs-container">
                                <div class="tabs-left">
                                    <ul class="nav nav-tabs">
                                        <li>
                                            <a class="nav-link " href="<?php echo URL ?>agency/edit?iditem=<?php echo $this->data["id"]; ?>"> 
                                                <i class="fa fa-user"></i> Scheda
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link active" style="color:#6DC4E9;" href="<?php echo URL ?>agency/editPrefView?iditem=<?php echo $this->data["id"]; ?>"> 
                                                <i class="fa fa-eye"></i> Preferenze Vista
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link " href="<?php echo URL ?>agency/editPref?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-list"></i> Preferenze Export
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="<?php echo URL ?>agency/editImg?iditem=<?php echo $this->data["id"]; ?>">
                                                <i class="fa fa-picture-o"></i> Immagine
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content ">
                                        <div id="tab-6" class="tab-pane active">
                                            <div class="panel-body">
                                                <form method="POST"  action="<?php echo URL ?>agency/executeEditPrefView?iditem=<?php echo  $this->data["id"] ?>" 
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
                                                            <h4>
                                                                Preferenze Visualizzazione Aste
                                                                <br>
                                                                <small>(L'Agenzia visualizzerà solo le Aste che corrispondono ai filtri indicati in questa sezione)</small>
                                                                <br>
                                                            </h4>
                                                        </div>
                                                        <div class="col-md-12" style="margin-top:10px;">
                                                            <div class="form-group">
                                                                <label>
                                                                    Comune Immobile
                                                                </label>
                                                                <select data-placeholder="Seleziona Comuni" name="idComuni[]"
                                                                    class="chosen-select" multiple style="width:350px;" tabindex="4">
                                                                    <?php
                                                                    if (is_array($this->comuniList) || is_object($this->comuniList)) {
                                                                        foreach ($this->comuniList as $item) {
                                                                            $_selected = "";
                                                                            if (is_array($this->relAgPrefViewList) || is_object($this->relAgPrefViewList)) {
                                                                                foreach ($this->relAgPrefViewList as $pref) {
                                                                                    if ($pref["tipoPreferenza"]=="comune" && $pref["idOggetto"]==$item["codice_istat"]) {
                                                                                        $_selected = " selected ";
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $item["codice_istat"]; ?>" <?php echo $_selected;?>>
                                                                                <?php echo $item["nome"]." (".$item["siglaprovincia"].")";?>
                                                                            </option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>
                                                                    Provincia Immobile
                                                                </label>
                                                                <select data-placeholder="Seleziona Provincia" name="idProvince[]"
                                                                    class="chosen-select" multiple style="width:350px;" tabindex="4">
                                                                    <?php
                                                                    if (is_array($this->provinceList) || is_object($this->provinceList)) {
                                                                        foreach ($this->provinceList as $item) {
                                                                            $_selected = " ";
                                                                            if (is_array($this->relAgPrefViewList) || is_object($this->relAgPrefViewList)) {
                                                                                foreach ($this->relAgPrefViewList as $pref) {
                                                                                    if ($pref["tipoPreferenza"]=="provincia" && $pref["idOggetto"]==$item["sigla"]) {
                                                                                        $_selected = " selected ";
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $item["sigla"]; ?>" <?php echo $_selected?> >
                                                                                <?php echo $item["nome"]." (".$item["sigla"].")";?>
                                                                            </option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>
                                                                    Comune Tribunale
                                                                </label>
                                                                <select data-placeholder="Seleziona Comuni" name="idComuniTribunale[]"
                                                                    class="chosen-select" multiple style="width:350px;" tabindex="4">
                                                                    <?php
                                                                    if (is_array($this->comuniList) || is_object($this->comuniList)) {
                                                                        foreach ($this->comuniList as $item) {
                                                                            $_selected = " ";
                                                                            if (is_array($this->relAgPrefViewList) || is_object($this->relAgPrefViewList)) {
                                                                                foreach ($this->relAgPrefViewList as $pref) {
                                                                                    if ($pref["tipoPreferenza"]=="comuneTribunale" && $pref["idOggetto"]==$item["codice_istat"]) {
                                                                                        $_selected = " selected ";
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $item["codice_istat"]; ?>" <?php echo $_selected?> >
                                                                                <?php echo $item["nome"]." (".$item["siglaprovincia"].")";?>
                                                                            </option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12 text-right">
                                                            <div class="form-group">
                                                                <a href="<?php echo URL; ?>agency/index" class="btn btn-default">
                                                                    Annulla
                                                                </a>
                                                                <button class="btn btn-flussi-light" type="submit">
                                                                    Salva
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
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>



<!-- ------------------
END PAGE
--------------------> 
        <div class="footer">
            <div>
                <strong>&copy; Copyright</strong> <?php echo date('Y'); ?> <a target="_blank" href="<?php echo $this->platformData['officialPathHttp']; ?>"><?php echo $this->platformData['siteName']; ?></a> | Tutti i diritti riservati
            </div>
        </div>
    </div>
</div>

    <!-- Mainly scripts -->
    <script src="<?php echo URL_THEME; ?>js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo URL_THEME; ?>js/popper.min.js"></script>
    <script src="<?php echo URL_THEME; ?>js/bootstrap.js"></script>
    <script src="<?php echo URL_THEME; ?>js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?php echo URL_THEME; ?>js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    
    <!-- FooTable -->
    <script src="<?php echo URL_THEME; ?>js/plugins/footable/footable.all.min.js"></script>
    
    
    
    <!-- Custom and plugin javascript -->
    <script src="<?php echo URL_THEME; ?>js/inspinia.js"></script>
    <script src="<?php echo URL_THEME; ?>js/plugins/pace/pace.min.js"></script>

    <!-- Flot -->
    <script src="<?php echo URL_THEME; ?>js/plugins/flot/jquery.flot.js"></script>
    <script src="<?php echo URL_THEME; ?>js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="<?php echo URL_THEME; ?>js/plugins/flot/jquery.flot.resize.js"></script>

    <!-- ChartJS-->
    <script src="<?php echo URL_THEME; ?>js/plugins/chartJs/Chart.min.js"></script>

    <!-- Peity -->
    <script src="<?php echo URL_THEME; ?>js/plugins/peity/jquery.peity.min.js"></script>
    <!-- Peity demo -->
    <script src="<?php echo URL_THEME; ?>js/demo/peity-demo.js"></script>
    <!-- Typehead -->
    <script src="<?php echo URL_THEME;?>js/plugins/typehead/bootstrap3-typeahead.min.js"></script>
    <!--Sweet alert-->
    <script src="<?php echo URL_THEME;?>js/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Select2 -->
    <script src="<?php echo URL_THEME;?>js/plugins/select2/select2.full.min.js"></script>
    <!-- Chosen -->
    <script src="<?php echo URL_THEME;?>js/plugins/chosen/chosen.jquery.js"></script>
  <?php  
    // LOAD JS Example specifici della VIEW
    if (isset($this->includeFooterExampleJs) && $this->includeFooterExampleJs != null) {
        include(URL_DOCUMENT_ROOT . $this->includeFooterExampleJs);
    }
    ?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDvq9ebE1yTHAtBJCak5S_b-DtTamFf_9Y"></script>


    <script>

        $('.chosen-select').chosen({width: "100%"});
        
        <!--Footable-->
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

        });

        $(document).ready(function () {
            // Only numbers
            $(".only-numbers").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl/cmd+A
                    (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: Ctrl/cmd+C
                    (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: Ctrl/cmd+X
                    (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                        return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });

            //Input without Spaces
            $(".withoutspace").on({
               keydown: function(e) {
                 if (e.which === 32)
                   return false;
               },
               change: function() {
                 this.value = this.value.replace(/\s/g, "");
               }
           });

        });
        
        
        // Get cities
        $.get('<?php echo URL; ?>libs/Functions/GetCities', function (data) {
            $(".cities").typeahead({  source: data });
        }, 'json');
        
        
        // Agency ->send crfedential
            $('.confirmCredenziali').click(function () {
                swal({
                    title: "Sei sicuro di voler resettare le credenziali di Accesso?",
                    text: "In questo modo verrà creata una nuova Password e sarà inviata all'Agenzia!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#1ab394",
                    confirmButtonText: "Si invia!",
                    closeOnConfirm: false
                }, function () {
                    $( "#btnResetCred" ).click();
                    swal("Inviate!", "Le credenziali sono state inviate!", "success");
                });
            });
            
            
            // Asta(admin) ->remove 
            $('.confirmRemoveAsta').click(function () {
                swal({
                    title: "Sei sicuro di voler eliminare questa Asta?",
                    text: "La rimozione sarà definitiva e verranno eliminati anche tutti i dati ad essa correlati.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#1ab394",
                    confirmButtonText: "Si rimuovi!",
                    closeOnConfirm: false
                }, function () {
                    $( "#btnRemoveAsta" ).click();
                    swal("Eliminata", "L'Asta è stata rimossa.", "success");
                });
            }); 
            
            
            // Agency ->Noty Asta On
            $('.confirmNotyOn').click(function () {
                swal({
                    title: "Sei sicuro di voler abilitare questa opzione ?",
                    text: "In questo modo riverai un'email con l'appuntamento del giorno dell'Asta!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#6DC4E9",
                    confirmButtonText: "Si conferma!",
                    closeOnConfirm: false
                }, function () {
                    $( "#btnNoty" ).click();
                    swal("Email abilitata!", "L'opzione è stata abilitata con successo!", "success");
                });
            });
            // Agency ->Noty Asta On
            $('.confirmNotyOn').click(function () {
                swal({
                    title: "Sei sicuro di voler disabilitare questa opzione ?",
                    text: "In questo modo non riverai più le emails con l'appuntamento di questa Asta!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#6DC4E9",
                    confirmButtonText: "Si conferma!",
                    closeOnConfirm: false
                }, function () {
                    $( "#btnNoty" ).click();
                    swal("Email disabilitata!", "L'opzione è stata disabilitata con successo!", "success");
                });
            });

        <?php
        if (isset($this->Coordinate)) {
            if ($this->data["Latitudine"]!="" && $this->data["Longitudine"]!="") {
                ?>
                // When the window has finished loading google map
                google.maps.event.addDomListener(window, 'load', init);
                function init() {
                    // Options for Google map
                    // More info see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions

                    var fenway = new google.maps.LatLng(<?php echo $this->data["Latitudine"];?>, <?php echo $this->data["Longitudine"];?>);
                    var mapOptions5 = {
                        zoom: 14,
                        center: fenway,
                        // Style for Google Maps
                        styles: [{featureType:"landscape",stylers:[{saturation:-100},{lightness:65},{visibility:"on"}]},{featureType:"poi",stylers:[{saturation:-100},{lightness:51},{visibility:"simplified"}]},{featureType:"road.highway",stylers:[{saturation:-100},{visibility:"simplified"}]},{featureType:"road.arterial",stylers:[{saturation:-100},{lightness:30},{visibility:"on"}]},{featureType:"road.local",stylers:[{saturation:-100},{lightness:40},{visibility:"on"}]},{featureType:"transit",stylers:[{saturation:-100},{visibility:"simplified"}]},{featureType:"administrative.province",stylers:[{visibility:"off"}]/**/},{featureType:"administrative.locality",stylers:[{visibility:"off"}]},{featureType:"administrative.neighborhood",stylers:[{visibility:"on"}]/**/},{featureType:"water",elementType:"labels",stylers:[{visibility:"on"},{lightness:-25},{saturation:-100}]},{featureType:"water",elementType:"geometry",stylers:[{hue:"#ffff00"},{lightness:-25},{saturation:-97}]}]
                    };
                    var panoramaOptions = {
                        position: fenway,
                        pov: {
                            heading: 10,
                            pitch: 10
                        }
                    };
                    var panorama = new google.maps.StreetViewPanorama(document.getElementById('pano'), panoramaOptions);

                }                     
                <?php
            }
        }
        ?>
        


    </script>
</body> 
</html>
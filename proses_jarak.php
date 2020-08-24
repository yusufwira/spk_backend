<html>
    <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css">
    <script src='https://unpkg.com/leaflet@1.3.3/dist/leaflet.js'></script>
    </head>
    <body>
        <div id="map"></div>
        <?php
            get_distance('-7.2443513','112.790592');


            function get_distance($x, $y){
                
                $koor_X = $x; 
                $koor_Y = $y; 
                
                ?>
                    <script>
                            var x = '<?php echo $koor_X; ?>';
                            var y = '<?php echo $koor_Y; ?>';
                            var jarak =0;
                            var map = L.map('map').setView([-7.2653644,112.782888], 14);
                            navigator.geolocation.getCurrentPosition((position) => {               
                                var markerFrom = L.circleMarker([position.coords.latitude,position.coords.longitude], { color: "#F00", radius: 10 });
                                var markerTo =  L.circleMarker([x,y], { color: "#4AFF00", radius: 10 });
                                var from = markerFrom.getLatLng();
                                var to = markerTo.getLatLng();
                                markerFrom.bindPopup('Delhi ' + (from).toString());
                                markerTo.bindPopup('Mumbai ' + (to).toString());
                                map.addLayer(markerTo);
                                map.addLayer(markerFrom);
                                jarak = (from.distanceTo(to)).toFixed(0)/1000;
                                console.log(jarak);  
                                $.post("spk_backend/proses_jarak.php", {"jjarak": jarak});                                                              
                            });          
                    </script>     
                <?php
              
               
            }

            echo $_POST['jjarak'];

        ?>
    </body>
    <script>
            
    </script>
</html>
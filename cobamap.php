<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css">
        <script src='https://unpkg.com/leaflet@1.3.3/dist/leaflet.js
'></script>
        <style>
            #map {
                height: 500px
            }
        </style>
    </head>
    <body>
        <div id="map"></div>
    </body>
    <script>      
        var map = L.map('map').setView([-7.2653644,112.782888], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([-7.3169079,112.7543568]).addTo(map)
        .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
        .openPopup();
    </script>
    
</html>
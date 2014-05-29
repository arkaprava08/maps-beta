

<!DOCTYPE html>
<html>
    <head>
        <title>Zomato test maps</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>
            html { height: 100% }
            body { 
                height: 100%; 
                margin: 0; 
                padding: 0;
                font-family: Roboto,Arial,sans-serif;
                -ms-touch-action: none;
            }
            #map-canvas { 
                height: 100%;
            }
        </style>
    </head>
    <body>


        <div id="map-canvas"></div>



        <script type="text/javascript" src="js/jquery.js"></script>

        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqPwD-lQNxWqoHMPX9_0UckQlfBVlPVeY&sensor=FALSE"></script>
        <script>
            /* calculate distance */
            var origin1 = new google.maps.LatLng(28.4991670000, 77.1616670000);
            //var destinationA = 'Mehrauli Gurgaon Rd, Ghitorni, New Delhi, Delhi 110070';
<?php
$con = mysqli_connect("localhost", "root", "root", "zomato");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con, "SELECT * from metroexits where metro_id=1");

echo "var dest = [];";
while ($row = mysqli_fetch_array($result)) {
    echo 'dest.push(new google.maps.LatLng(' . $row['latitude'] . ', ' . $row['longitude'] . '));';
}
?>
            function calculateDistances() {
                var service = new google.maps.DistanceMatrixService();


                service.getDistanceMatrix(
                        {
                            origins: [origin1],
                            destinations: [
<?php
$r = mysqli_query($con, "SELECT * from metroexits where metro_id=1");

while ($row = mysqli_fetch_array($r)) {
    echo 'new google.maps.LatLng(' . $row['latitude'] . ', ' . $row['longitude'] . '),';
}



mysql_close($con);
?>
                            ],
                            travelMode: google.maps.TravelMode.WALKING,
                            unitSystem: google.maps.UnitSystem.METRIC,
                            avoidHighways: false,
                            avoidTolls: false
                        }, callback);
            }


            var lat = null;
            var lng = null;
            var dist = null;

            function callback(response, status) {
                if (status != google.maps.DistanceMatrixStatus.OK) {
                    alert('Error was: ' + status);
                } else {
                    console.log(response);

                    var origins = response.originAddresses;
                    var destinations = response.destinationAddresses;


                    for (var i = 0; i < origins.length; i++) {
                        var results = response.rows[i].elements;
                        for (var j = 0; j < results.length; j++) {

                            var img = 'img/exit.png';
                            var markersource = new google.maps.Marker({
                                position: new google.maps.LatLng(dest[j].k, dest[j].A),
                                map: map,
                                icon: img
                            });
                            console.log(origins + "___" + dest[j].k + "__" + dest[j].A + "________" + results[j].distance.value);
                            if (dist === null) {
                                lat = dest[j].k;
                                lng = dest[j].A;
                                dist = parseFloat(results[j].distance.value);
                                continue;
                            }
                            if (dist > parseInt(results[j].distance.value)) {
                                lat = dest[j].k;
                                lng = dest[j].A;
                                dist = parseFloat(results[j].distance.value);
                            }
                        }

//                        var img = 'img/exit.png';
//                        var markersource = new google.maps.Marker({
//                            position: origin1,
//                            map: map,
//                            icon: img
//                        });
//                        var markerdest = new google.maps.Marker({
//                            position: new google.maps.LatLng(lat, lng),
//                            map: map
//                        });
//
//                        google.maps.event.addListener(markersource, 'click', function() {
//                            map.setZoom(20);
//                            map.setCenter(markersource.getPosition());
//                        });
//
//                        google.maps.event.addListener(markerdest, 'click', function() {
//                            map.setZoom(20);
//                            map.setCenter(markersource.getPosition());
//                        });


                        //calcRoute(origin1, new google.maps.LatLng(lat, lng));
//                        
                        //calcRoute(origin1, dest[0]);

                    }
                    calcRoute(origin1, new google.maps.LatLng(lat, lng));
                }
            }


        </script>

        <script>
            var map;
            var rendererOptions = {
                draggable: true
            };
            var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
            var directionsService = new google.maps.DirectionsService();
            function initialize() {
                var mapOptions = {
                    center: new google.maps.LatLng(20, 20),
                    zoom: 2,
                    mapTypeId: google.maps.MapTypeId.HYBRID
                };
                map = new google.maps.Map(document.getElementById("map-canvas"),
                        mapOptions);

                directionsDisplay.setMap(map);
                directionsDisplay.setPanel(document.getElementById("directionsPanel"));



            }
            google.maps.event.addDomListener(window, 'load', initialize);


            function calcRoute(origin, dest) {

                console.log(lat);
                var request = {
                    origin: origin,
                    destination: dest,
                    travelMode: google.maps.TravelMode.WALKING
                };
                directionsService.route(request, function(response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                    }
                });
            }

            calculateDistances();
        </script>
    </body>
</html>


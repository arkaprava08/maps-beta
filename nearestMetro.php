<?php

$con = mysqli_connect("localhost", "root", "root", "zomato");

// Check connection
$name = "";
$lat = "";
$lng = "";
$dist;
$metro_id;
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {

    if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
        $result = mysqli_query($con, "SELECT * FROM metros ");

        while ($row = mysqli_fetch_array($result)) {

            if ($name == NULL) {
                $name = $row['name'];
                $lat = $row['latitude'];
                $long = $row['longitude'];
                $dist = floatval((($_GET['latitude'] - $row['latitude']) * ($_GET['latitude'] - $row['latitude'])) + (($_GET['longitude'] - $row['longitude']) * ($_GET['longitude'] - $row['longitude'])));
                $metro_id = $row['metro_id'];
                continue;
            }

            if ($dist > floatval((($_GET['latitude'] - $row['latitude']) * ($_GET['latitude'] - $row['latitude'])) + (($_GET['longitude'] - $row['longitude']) * ($_GET['longitude'] - $row['longitude'])))) {
                $name = $row['name'];
                $lat = $row['latitude'];
                $long = $row['longitude'];
                $metro_id = $row['metro_id'];
                $dist = floatval((($_GET['latitude'] - $row['latitude']) * ($_GET['latitude'] - $row['latitude'])) + (($_GET['longitude'] - $row['longitude']) * ($_GET['longitude'] - $row['longitude'])));
            }
        }

        echo "{\"name\":\"".$name."\",\"metro_id\":\"".$metro_id."\"}";
    }
}



mysql_close($con);

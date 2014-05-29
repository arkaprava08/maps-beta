<?php

$con = mysqli_connect("localhost", "root", "root", "zomato");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {

    if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
        $result = mysqli_query($con, "UPDATE metroexits set latitude = ".$_GET['latitude']
                ." , longitude=".$_GET['longitude']
                ." where latitude=".$_GET['inilatitude']
                ." and longitude=".$_GET['inilongitude']);
        if ($result) {
            echo "successfully updated";
        } else {
            echo "error in updation";
        }
    }
}



mysql_close($con);

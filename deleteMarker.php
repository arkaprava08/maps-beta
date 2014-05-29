<?php

$con = mysqli_connect("localhost", "root", "root", "zomato");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
    $result = mysqli_query($con, "DELETE FROM metroexits where latitude=" . $_GET['latitude'] . " and longitude=" . $_GET['longitude']);

    if(result)
    {
        echo 'Marker deleted';
        //echo "DELETE FROM metroexits where latitude=" . $_GET['latitude'] . " and longitude=" . $_GET['longitude'];
    }
    else
    {
        echo 'Marker couldnot be deleted';
    }
} else {
    echo 'Marker couldnot be deleted**';
}


mysql_close($con);

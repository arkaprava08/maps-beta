<?php

$con = mysqli_connect("localhost", "root", "root", "zomato");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {

    if (isset($_GET['metro_id'])) {
        $result = mysqli_query($con, "INSERT INTO metroexits VALUES(" . $_GET['metro_id'] . ", " . $_GET['latitude'] . " ," . $_GET['longitude'] . ")");
        if ($result) {
            echo "successfully updated";
        } else {
            echo "error in updation";
        }
    }
}



mysql_close($con);

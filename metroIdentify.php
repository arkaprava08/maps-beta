<?php

$con = mysqli_connect("localhost", "root", "root", "zomato");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {

    if (isset($_GET['metro_id'])) {
        $result = mysqli_query($con, "SELECT * FROM metros where metro_id = ".$_GET['metro_id']);
        if ($result) {
            echo mysqli_fetch_array($result)['name'];
        } else {
            echo "No Metro Selected";
        }
    }
}



mysql_close($con);

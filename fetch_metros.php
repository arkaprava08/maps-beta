<?php

$con = mysqli_connect("localhost", "root", "root", "zomato");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (isset($_GET['real_city'])) {
    $result = mysqli_query($con, "SELECT * FROM metros where real_city LIKE \"%".$_GET['real_city']."%\"");

    echo '<div>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<p id="' . $row['metro_id'] . '" data-values=\'{"metro_id":"'
        . $row['metro_id'] . '", "latitude":"'
        . $row['latitude'] . '", "longitude":"'
        . $row['longitude'] . '"}\'><a onclick="locationLoad(this)">'
        . $row['name'] . '</a></p>';
    }
    echo '</div>';
} else {
    echo 'unable to fetch list';
}

mysql_close($con);


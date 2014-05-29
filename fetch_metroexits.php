<?php

$con = mysqli_connect("localhost", "root", "root", "zomato");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


    $result = mysqli_query($con, "SELECT * FROM metroexits");

    
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
        $latLngArrary[$i]['latitude'] = $row['latitude']; 
        $latLngArrary[$i]['longitude'] = $row['longitude']; 
        $i++;
    }
    
    echo json_encode($latLngArrary); 


mysql_close($con);
<?php

$con = mysqli_connect("localhost", "root", "root", "zomato");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con, "SELECT distinct real_city FROM metros ");

echo '<option value="">No Filter</option>';
while ($row = mysqli_fetch_array($result)) {
    echo '<option>'.$row['real_city'].'</option>';
}



mysql_close($con);

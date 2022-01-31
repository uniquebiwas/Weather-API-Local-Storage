<?php
header('Access-Control-Allow-Origin: *');

include('index1.php');
// Execute SQL query
$sql = "SELECT *
FROM weather
ORDER BY weather_when DESC limit 1";
$result= $conn -> query($sql);
// Get data, convert to JSON and print
$row = $result -> fetch_assoc();
print json_encode($row);
// Free result set and close connection
$result -> free_result();
$conn -> close();
?> 
<?php

// conect your database mysql with username , password and servername
$servername = "localhost";
$username = "root";
$password = "Samsung@123";

$conn = new mysqli($servername, $username, $password);

if($conn->connect_error){
    exit("Connection Failed: ".$conn->connect_error);
}

// Check if Database Exists, if it does not fire the Create Database Query 

$query = "SELECT SCHEMA_NAME
FROM INFORMATION_SCHEMA.SCHEMATA
WHERE SCHEMA_NAME = 'BIWAS'";

$result = $conn->query($query);
if($result->num_rows == 0){
    // Create Database
    $database = "BIWAS";
    $db_query = "CREATE DATABASE ".$database.";";
    $conn->query($db_query);
    // Select Database
    $conn->select_db($database);
    // Create Table
    $table_query = "
    create table weather(
        id INT AUTO_INCREMENT PRIMARY KEY,
        weather_description varchar(100),
        weather_temperature float ,
        weather_wind float ,
        city varchar(100),
        country varchar(100),
        weather_when datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        pressure float,
        humidity float,
        direction float
    );
    ";
    //fire create table code
        $conn->query($table_query);
}
//if database exist or created select database
$conn->select_db("BIWAS");

// sql query of selecting data and checking the time interval of 2 hrs (7200 second)

$sql = "SELECT *
FROM weather where
weather_when >= DATE_SUB(NOW(), INTERVAL 7200 SECOND)  
ORDER BY weather_when DESC limit 1";
//fire query of $sql 
$result=$conn->query($sql);
// your city name with variable
$city='phoenix';
// If 0 record found
if ($result->num_rows== 0) {
$url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=c8f7b805243cd8de19a2b0397192fd57";
// Get data from openweathermap and store in JSON object
$data = file_get_contents($url);
$json = json_decode($data, true); // decode json data for fetching
// Fetch required fields
// store data from api in variables
$weather_description = $json['weather'][0]['description'];
$weather_temperature = $json['main']['temp'];
$weather_wind = $json['wind']['speed'];
$city = $json['name'];
$country= $json['sys']['country'];
$pressure=$json['main']['pressure'];
$humidity=$json['main']['humidity'];
$direction =$json['wind']['deg'];
// Build INSERT SQL statement for inserting data in database
$sql = "INSERT INTO weather (weather_description, weather_temperature, weather_wind, city,country, pressure , humidity,direction)
VALUES('{$weather_description}', {$weather_temperature}, {$weather_wind}, '{$city}', '{$country}',{$pressure},{$humidity},{$direction})";
// Run SQL statement and report errors
if (!$conn->query($sql)) {
echo("<h1>SQL ERROR: " . $conn -> error . "</h1>");

}
}

?>
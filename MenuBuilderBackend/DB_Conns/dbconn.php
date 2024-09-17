<?php


$servername = "";
$username = "";
$password = "";
$database = "";
#echo "\n\n";
#echo "CALLED DBCONN!!2";
#echo "\n\n";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_errno) {
    //die("Connection failed: " . $conn->connect_error);
    exit("Error Connecting to the database");
}

//echo "Connected successfully";
return $conn;

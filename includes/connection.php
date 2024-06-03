<?php

$dbhost = "localhost"; 
$dbuser = "root"; 
$dbpass = ""; 
$dbname = "social_media"; 

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if($conn->connect_error){
    die("failed to connect: " . $conn->connect_error);
}
?>

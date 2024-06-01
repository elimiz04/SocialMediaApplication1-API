<?php

$dbhost = "localhost"; 
$dbuser = "root"; 
$dbpass = ""; 
$username = "username";
$dbname = "social_media"; 

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if(!$conn){
    die("failed to connect: " . mysqli_connect_error());
}
?>

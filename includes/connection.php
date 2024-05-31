<?php

$dbhost = "localhost"; 
$dbuser = "root"; 
$dbpass = ""; 
$username = "username";
$dbname = "social_media"; 

$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if(!$con){
    die("failed to connect: " . mysqli_connect_error());
}
?>

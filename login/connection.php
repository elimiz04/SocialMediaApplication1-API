<?php

$dbhost = "localhost"; 
$dbuser = "root"; 
$dbpass = ""; 
$dbname = "social_media"; 

if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)){

    die("failed to connect!");
}
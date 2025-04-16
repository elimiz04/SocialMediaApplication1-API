<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "social_media";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<?php
require_once('database.php'); 

$db = new Database();
$conn = $db->connect();

echo json_encode(["message" => "Connected to database"]);

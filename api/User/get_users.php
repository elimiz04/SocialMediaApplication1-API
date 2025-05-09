<?php
header("Content-Type: application/json");

include_once(__DIR__ . '/../utils/Database.php');

$database = new Database();
$db = $database->connect();

$query = "SELECT user_id, username, email FROM users";
$stmt = $db->prepare($query);
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($users);
?>

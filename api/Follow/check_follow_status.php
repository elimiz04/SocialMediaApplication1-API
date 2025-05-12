<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include DB connection
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get parameters from URL
$follower_id = isset($_GET['follower_id']) ? intval($_GET['follower_id']) : null;
$followed_id = isset($_GET['followed_id']) ? intval($_GET['followed_id']) : null;

// Validate input
if ($follower_id && $followed_id) {
    $query = "SELECT * FROM follows WHERE follower_id = :follower_id AND followed_id = :followed_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':follower_id', $follower_id);
    $stmt->bindParam(':followed_id', $followed_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(["following" => true]);
    } else {
        echo json_encode(["following" => false]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing follower_id or followed_id."]);
}

<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Include database
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get raw input
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->user_id)) {
    $user_id = intval($data->user_id);

    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id AND is_read = 0");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    http_response_code(200);
    echo json_encode(["message" => "Notifications marked as read."]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing user_id."]);
}

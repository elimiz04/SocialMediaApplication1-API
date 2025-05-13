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

    // Check if user exists
    $userCheck = $conn->prepare("SELECT user_id FROM users WHERE user_id = :user_id");
    $userCheck->bindParam(':user_id', $user_id);
    $userCheck->execute();

    if ($userCheck->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "User not found."]);
        exit;
    }

    // Mark notifications as read
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id AND is_read = 0");
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Notifications marked as read."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to update notifications."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing user_id."]);
}

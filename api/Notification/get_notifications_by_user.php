<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include database
include_once(__DIR__ . '/../utils/database.php');

// Connect to database
$db = new Database();
$conn = $db->connect();

// Get user_id from query string
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id > 0) {
    // Prepare query
    $stmt = $conn->prepare("SELECT notification_id, message, is_read, created_at FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($notifications) {
        http_response_code(200);
        echo json_encode($notifications);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "No notifications found."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing or invalid user_id."]);
}

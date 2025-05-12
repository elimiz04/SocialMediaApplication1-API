<?php
// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include DB config
include_once(__DIR__ . '/../utils/database.php');

// DB connect
$db = new Database();
$conn = $db->connect();

// Get user_id and contact_id from query string
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;

// Check input
if ($user_id > 0 && $contact_id > 0) {
    $stmt = $conn->prepare("
        SELECT * FROM messages
        WHERE (sender_id = :user_id AND receiver_id = :contact_id)
           OR (sender_id = :contact_id AND receiver_id = :user_id)
        ORDER BY created_at ASC
    ");

    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':contact_id', $contact_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($messages);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "No messages found between users."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing or invalid user_id and contact_id."]);
}

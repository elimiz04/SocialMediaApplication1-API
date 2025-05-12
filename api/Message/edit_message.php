<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");

// DB config
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get raw input
$data = json_decode(file_get_contents("php://input"));

// Validate required fields
if (!empty($data->message_id) && !empty($data->user_id) && !empty($data->content)) {
    $message_id = intval($data->message_id);
    $user_id = intval($data->user_id);
    $content = htmlspecialchars(strip_tags($data->content));

    // Only allow edit if user is the sender
    $stmt = $conn->prepare("UPDATE messages SET content = :content, updated_at = NOW() WHERE message_id = :message_id AND sender_id = :user_id");
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':message_id', $message_id);
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(["message" => "Message updated successfully."]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Message not found or you're not the sender."]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to update message."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "message_id, user_id, and content are required."]);
}

<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");

// DB config
include_once(__DIR__ . '/../utils/database.php');

// DB connection
$db = new Database();
$conn = $db->connect();

// Get input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->message_id) && !empty($data->user_id)) {
    $message_id = intval($data->message_id);
    $user_id = intval($data->user_id);

    // Only allow deleting if user is sender
    $stmt = $conn->prepare("DELETE FROM messages WHERE message_id = :message_id AND sender_id = :user_id");
    $stmt->bindParam(':message_id', $message_id);
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(["message" => "Message deleted successfully."]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Message not found or you're not the sender."]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete message."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["message" => "message_id and user_id are required."]);
}

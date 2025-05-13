<?php
// Allow requests from any origin and define JSON content
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Include database config
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get JSON input
$data = json_decode(file_get_contents("php://input"));

// Validate required fields
if (!empty($data->sender_id) && !empty($data->receiver_id) && !empty($data->content)) {
    $sender_id = intval($data->sender_id);
    $receiver_id = intval($data->receiver_id);
    $content = htmlspecialchars(strip_tags($data->content));

    // Check if sender exists
    $checkSender = $conn->prepare("SELECT user_id FROM users WHERE user_id = :sender_id");
    $checkSender->bindParam(':sender_id', $sender_id);
    $checkSender->execute();
    if ($checkSender->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "Sender not found."]);
        exit;
    }

    // Check if receiver exists
    $checkReceiver = $conn->prepare("SELECT user_id FROM users WHERE user_id = :receiver_id");
    $checkReceiver->bindParam(':receiver_id', $receiver_id);
    $checkReceiver->execute();
    if ($checkReceiver->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "Receiver not found."]);
        exit;
    }

    // Insert message
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, content, is_read, created_at, updated_at) 
                            VALUES (:sender_id, :receiver_id, :content, 0, NOW(), NOW())");

    $stmt->bindParam(':sender_id', $sender_id);
    $stmt->bindParam(':receiver_id', $receiver_id);
    $stmt->bindParam(':content', $content);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Message sent successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to send message."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing sender_id, receiver_id, or content."]);
}

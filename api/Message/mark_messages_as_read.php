<?php
// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Include DB config
include_once(__DIR__ . '/../utils/database.php');

// DB connect
$db = new Database();
$conn = $db->connect();

// Get JSON input
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->receiver_id) && !empty($data->sender_id)) {
    $receiver_id = intval($data->receiver_id);
    $sender_id = intval($data->sender_id);

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

    // Update query
    $stmt = $conn->prepare("
        UPDATE messages 
        SET is_read = 1 
        WHERE sender_id = :sender_id 
          AND receiver_id = :receiver_id 
          AND is_read = 0
    ");
    $stmt->bindParam(':sender_id', $sender_id);
    $stmt->bindParam(':receiver_id', $receiver_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Messages marked as read."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to mark messages as read."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing sender_id or receiver_id."]);
}

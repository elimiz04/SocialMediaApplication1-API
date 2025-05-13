<?php
// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");

// Include DB config
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get raw input
$data = json_decode(file_get_contents("php://input"));

// Check if user_id is provided
if (!empty($data->user_id)) {
    $user_id = intval($data->user_id);

    // Check if user exists
    $check = $conn->prepare("SELECT user_id FROM users WHERE user_id = :user_id");
    $check->bindParam(":user_id", $user_id);
    $check->execute();

    if ($check->rowCount() === 0) {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "User not found."]);
        exit;
    }

    // Proceed with deletion
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $user_id);

    if ($stmt->execute()) {
        http_response_code(200); // OK
        echo json_encode(["message" => "User deleted successfully."]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["message" => "Failed to delete user."]);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing user_id."]);
}

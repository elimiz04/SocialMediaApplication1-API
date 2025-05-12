<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Include DB connection
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->follower_id) && !empty($data->followed_id)) {
    $follower_id = intval($data->follower_id);
    $followed_id = intval($data->followed_id);

    // Prepare DELETE query
    $query = "DELETE FROM follows WHERE follower_id = :follower_id AND followed_id = :followed_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':follower_id', $follower_id);
    $stmt->bindParam(':followed_id', $followed_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Unfollowed successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to unfollow."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing follower_id or followed_id."]);
}

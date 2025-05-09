<?php
// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include DB config
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get user_id from query string
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = htmlspecialchars(strip_tags($_GET['user_id']));

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT user_id, username, email FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Check if user exists
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($user);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "User not found."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Invalid or missing user_id."]);
}

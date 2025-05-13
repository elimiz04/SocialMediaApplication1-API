<?php
// Allow requests from any origin and define content type as JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Include database connection
include_once(__DIR__ . '/../utils/database.php');

// Connect to database
$db = new Database();
$conn = $db->connect();

// Get raw JSON input
$data = json_decode(file_get_contents("php://input"));

// Check for required fields
if (!empty($data->follower_id) && !empty($data->followed_id) && !empty($data->action)) {
    $follower_id = intval($data->follower_id);
    $followed_id = intval($data->followed_id);
    $action = strtolower(trim($data->action));

    // Prevent self-follow
    if ($follower_id === $followed_id) {
        http_response_code(400);
        echo json_encode(["message" => "You cannot follow yourself."]);
        exit;
    }

    // Check if both users exist
    $checkQuery = "SELECT user_id FROM users WHERE user_id IN (:follower_id, :followed_id)";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bindParam(':follower_id', $follower_id);
    $stmt->bindParam(':followed_id', $followed_id);
    $stmt->execute();

    if ($stmt->rowCount() < 2) {
        http_response_code(404);
        echo json_encode(["message" => "One or both users not found."]);
        exit;
    }

    if ($action === "follow") {
        // Follow logic
        $query = "INSERT IGNORE INTO follows (follower_id, followed_id) VALUES (:follower_id, :followed_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':follower_id', $follower_id);
        $stmt->bindParam(':followed_id', $followed_id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Followed successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to follow user."]);
        }

    } elseif ($action === "unfollow") {
        // Unfollow logic
        $query = "DELETE FROM follows WHERE follower_id = :follower_id AND followed_id = :followed_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':follower_id', $follower_id);
        $stmt->bindParam(':followed_id', $followed_id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Unfollowed successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to unfollow user."]);
        }

    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid action. Use 'follow' or 'unfollow'."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields: follower_id, followed_id, and action."]);
}
?>

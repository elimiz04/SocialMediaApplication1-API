<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");

// Include DB
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get input data
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->post_id)) {
    $post_id = intval($data->post_id);

    // Check if the post exists
    $checkStmt = $conn->prepare("SELECT post_id FROM posts WHERE post_id = :post_id");
    $checkStmt->bindParam(':post_id', $post_id);
    $checkStmt->execute();

    if ($checkStmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "Post not found."]);
        exit;
    }

    // Delete the post
    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $post_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Post deleted successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete post."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing required field: post_id."]);
}

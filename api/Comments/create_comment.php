<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// DB config
include_once(__DIR__ . '/../utils/database.php');

// Connect
$db = new Database();
$conn = $db->connect();

// Get input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->user_id) && !empty($data->post_id) && !empty($data->content)) {
    $user_id = intval($data->user_id);
    $post_id = intval($data->post_id);
    $content = htmlspecialchars(strip_tags($data->content));

    // Check if post exists
    $check = $conn->prepare("SELECT post_id FROM posts WHERE post_id = :post_id");
    $check->bindParam(':post_id', $post_id);
    $check->execute();

    if ($check->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "Post not found."]);
        exit;
    }

    // Insert comment
    $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (:user_id, :post_id, :content)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':content', $content);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Comment added successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to add comment."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields: user_id, post_id, or content."]);
}

<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");

// Include DB
include_once(__DIR__ . '/../utils/database.php');

// Connect
$db = new Database();
$conn = $db->connect();

// Get raw input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->post_id) && (!empty($data->content) || isset($data->image))) {
    $post_id = intval($data->post_id);
    $content = !empty($data->content) ? htmlspecialchars(strip_tags($data->content)) : null;
    $image = isset($data->image) ? htmlspecialchars(strip_tags($data->image)) : null;

    // Check if post exists
    $checkStmt = $conn->prepare("SELECT post_id FROM posts WHERE post_id = :post_id");
    $checkStmt->bindParam(':post_id', $post_id);
    $checkStmt->execute();

    if ($checkStmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "Post not found."]);
        exit;
    }

    // Build dynamic SQL query
    $sql = "UPDATE posts SET ";
    $params = [];

    if ($content !== null) {
        $sql .= "content = :content, ";
        $params[':content'] = $content;
    }
    if ($image !== null) {
        $sql .= "image = :image, ";
        $params[':image'] = $image;
    }

    $sql = rtrim($sql, ', ') . " WHERE post_id = :post_id";
    $params[':post_id'] = $post_id;

    $stmt = $conn->prepare($sql);
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value);
    }

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Post updated successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to update post."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "post_id and at least one field (content or image) are required."]);
}

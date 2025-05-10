<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include DB
include_once(__DIR__ . '/../utils/database.php');

// Connect
$db = new Database();
$conn = $db->connect();

// Get post_id from URL
if (!isset($_GET['post_id'])) {
    http_response_code(400);
    echo json_encode(["message" => "post_id is required"]);
    exit;
}

$post_id = intval($_GET['post_id']);

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = :post_id");
$stmt->bindParam(':post_id', $post_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($post);
} else {
    http_response_code(404);
    echo json_encode(["message" => "Post not found"]);
}

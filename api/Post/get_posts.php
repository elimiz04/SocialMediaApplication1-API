<?php
// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include database
include_once(__DIR__ . '/../utils/database.php');

// Create DB connection
$db = new Database();
$conn = $db->connect();

// SQL to fetch all posts with user info
$sql = "SELECT p.post_id, p.user_id, u.username, p.content, p.image, p.created_at 
        FROM posts p 
        JOIN users u ON p.user_id = u.user_id 
        ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$posts = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $posts[] = $row;
}

// Return result
if (!empty($posts)) {
    http_response_code(200);
    echo json_encode($posts);
} else {
    http_response_code(404);
    echo json_encode(["message" => "No posts found."]);
}

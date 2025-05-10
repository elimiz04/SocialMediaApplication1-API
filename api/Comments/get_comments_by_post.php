<?php
// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include database connection
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get post_id from URL (GET parameter)
if (!isset($_GET['post_id'])) {
    http_response_code(400);
    echo json_encode(["message" => "Missing post_id in request"]);
    exit;
}

$post_id = intval($_GET['post_id']);

// Query comments for the post, join with users for username
$query = "
    SELECT 
        c.comment_id, 
        c.user_id, 
        u.username, 
        c.content, 
        c.created_at 
    FROM comments c
    JOIN users u ON c.user_id = u.user_id
    WHERE c.post_id = :post_id
    ORDER BY c.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bindParam(':post_id', $post_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($comments);
} else {
    http_response_code(404);
    echo json_encode(["message" => "No comments found for this post"]);
}
?>

<?php
// Set response headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include database connection
include_once(__DIR__ . '/../utils/database.php');
$db = new Database();
$conn = $db->connect();

// Validate GET param
if (isset($_GET['post_id']) && is_numeric($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);

    // Check if post exists
    $checkPost = $conn->prepare("SELECT post_id FROM posts WHERE post_id = :post_id");
    $checkPost->bindParam(':post_id', $post_id);
    $checkPost->execute();

    if ($checkPost->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "Post not found."]);
        exit;
    }

    // Prepare SQL query to count likes
    $stmt = $conn->prepare("SELECT COUNT(*) AS like_count FROM likes WHERE post_id = :post_id AND status = 'liked'");
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Respond with like count
    http_response_code(200);
    echo json_encode([
        "post_id" => $post_id,
        "likes" => intval($result['like_count'])
    ]);

} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing or invalid post_id."]);
}
?>

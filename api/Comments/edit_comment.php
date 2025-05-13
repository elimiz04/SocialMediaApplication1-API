<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Include DB
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get JSON input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->comment_id) && !empty($data->content)) {
    $comment_id = intval($data->comment_id);
    $content = htmlspecialchars(strip_tags($data->content));

    // First check if the comment exists
    $checkQuery = "SELECT comment_id FROM comments WHERE comment_id = :comment_id";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':comment_id', $comment_id);
    $checkStmt->execute();

    if ($checkStmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "Comment not found."]);
        exit;
    }

    // Prepare update query
    $query = "UPDATE comments SET content = :content WHERE comment_id = :comment_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':comment_id', $comment_id);

    // Execute and respond
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Comment updated successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to update comment."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing comment_id or content."]);
}
?>

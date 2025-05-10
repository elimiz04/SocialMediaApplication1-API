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
if (!empty($data->comment_id)) {
    $comment_id = intval($data->comment_id);

    // Prepare DELETE query
    $query = "DELETE FROM comments WHERE comment_id = :comment_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':comment_id', $comment_id);

    // Execute and respond
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Comment deleted successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete comment."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing comment_id."]);
}
?>

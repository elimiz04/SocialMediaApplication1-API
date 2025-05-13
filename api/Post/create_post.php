<?php
// Allow requests from any origin and define content type as JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Include the database connection
include_once(__DIR__ . '/../utils/database.php');

// Connect to the database
$db = new Database();
$conn = $db->connect();

// Get POST data from the request
$data = json_decode(file_get_contents("php://input"));

// Check if required fields are provided
if (!empty($data->user_id) && !empty($data->content)) {

    // Sanitize inputs
    $user_id = intval($data->user_id);
    $content = htmlspecialchars(strip_tags($data->content));
    $image = !empty($data->image) ? htmlspecialchars(strip_tags($data->image)) : null;

    // Check if the user exists
    $userCheck = $conn->prepare("SELECT user_id FROM users WHERE user_id = :user_id");
    $userCheck->bindParam(':user_id', $user_id);
    $userCheck->execute();

    if ($userCheck->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "User not found."]);
        exit;
    }

    // Prepare SQL query
    $sql = "INSERT INTO posts (user_id, content, image, created_at) VALUES (:user_id, :content, :image, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':image', $image);

    // Execute and respond
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Post created successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to create post."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields: user_id and content."]);
}

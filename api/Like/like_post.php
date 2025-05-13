<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// DB Connection
include_once(__DIR__ . '/../utils/database.php');
$db = new Database();
$conn = $db->connect();

// Get input
$data = json_decode(file_get_contents("php://input"));

// Validate required fields
if (!empty($data->user_id) && !empty($data->post_id)) {
    $user_id = intval($data->user_id);
    $post_id = intval($data->post_id);

    // Check if user exists
    $userCheck = $conn->prepare("SELECT user_id FROM users WHERE user_id = :user_id");
    $userCheck->bindParam(':user_id', $user_id);
    $userCheck->execute();
    if ($userCheck->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "User not found."]);
        exit;
    }

    // Check if post exists
    $postCheck = $conn->prepare("SELECT post_id FROM posts WHERE post_id = :post_id");
    $postCheck->bindParam(':post_id', $post_id);
    $postCheck->execute();
    if ($postCheck->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "Post not found."]);
        exit;
    }

    // Check if already liked
    $check = $conn->prepare("SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id");
    $check->bindParam(':user_id', $user_id);
    $check->bindParam(':post_id', $post_id);
    $check->execute();

    if ($check->rowCount() > 0) {
        // Update status
        $update = $conn->prepare("UPDATE likes SET status = 'liked' WHERE user_id = :user_id AND post_id = :post_id");
        $update->bindParam(':user_id', $user_id);
        $update->bindParam(':post_id', $post_id);

        if ($update->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Post liked."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to update like."]);
        }
    } else {
        // Insert new like
        $insert = $conn->prepare("INSERT INTO likes (user_id, post_id, status) VALUES (:user_id, :post_id, 'liked')");
        $insert->bindParam(':user_id', $user_id);
        $insert->bindParam(':post_id', $post_id);

        if ($insert->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Post liked."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to like post."]);
        }
    }

} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing user_id or post_id."]);
}
?>

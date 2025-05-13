<?php
// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Include DB
include_once(__DIR__ . '/../utils/database.php');
$db = new Database();
$conn = $db->connect();

// Get and decode input
$data = json_decode(file_get_contents("php://input"));

// Check required fields
if (!empty($data->post_id) && !empty($data->user_id)) {
    $post_id = intval($data->post_id);
    $user_id = intval($data->user_id);

    // Check if post exists
    $postCheck = $conn->prepare("SELECT post_id FROM posts WHERE post_id = :post_id");
    $postCheck->bindParam(':post_id', $post_id);
    $postCheck->execute();
    if ($postCheck->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "Post not found."]);
        exit;
    }

    // Check if user exists
    $userCheck = $conn->prepare("SELECT user_id FROM users WHERE user_id = :user_id");
    $userCheck->bindParam(':user_id', $user_id);
    $userCheck->execute();
    if ($userCheck->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "User not found."]);
        exit;
    }

    // Check if like already exists
    $check = $conn->prepare("SELECT status FROM likes WHERE post_id = :post_id AND user_id = :user_id");
    $check->bindParam(':post_id', $post_id);
    $check->bindParam(':user_id', $user_id);
    $check->execute();

    if ($check->rowCount() > 0) {
        $like = $check->fetch(PDO::FETCH_ASSOC);
        $new_status = $like['status'] === 'liked' ? 'unliked' : 'liked';

        $update = $conn->prepare("UPDATE likes SET status = :status WHERE post_id = :post_id AND user_id = :user_id");
        $update->bindParam(':status', $new_status);
        $update->bindParam(':post_id', $post_id);
        $update->bindParam(':user_id', $user_id);
        $update->execute();
    } else {
        $new_status = 'liked';
        $insert = $conn->prepare("INSERT INTO likes (post_id, user_id, status) VALUES (:post_id, :user_id, :status)");
        $insert->bindParam(':post_id', $post_id);
        $insert->bindParam(':user_id', $user_id);
        $insert->bindParam(':status', $new_status);
        $insert->execute();
    }

    http_response_code(200);
    echo json_encode(["message" => "Like status updated.", "status" => $new_status]);

} else {
    http_response_code(400);
    echo json_encode(["message" => "post_id and user_id are required."]);
}
?>

<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// DB Connection
include_once(__DIR__ . '/../utils/database.php');
$db = new Database();
$conn = $db->connect();

// Get data
$data = json_decode(file_get_contents("php://input"));

// Validate
if (!empty($data->user_id) && !empty($data->post_id)) {
    $user_id = intval($data->user_id);
    $post_id = intval($data->post_id);

    // Check if like exists
    $check = $conn->prepare("SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id");
    $check->bindParam(':user_id', $user_id);
    $check->bindParam(':post_id', $post_id);
    $check->execute();

    if ($check->rowCount() > 0) {
        // Update to unliked
        $update = $conn->prepare("UPDATE likes SET status = 'unliked' WHERE user_id = :user_id AND post_id = :post_id");
        $update->bindParam(':user_id', $user_id);
        $update->bindParam(':post_id', $post_id);

        if ($update->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Post unliked."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to unlike post."]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Like not found."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing user_id or post_id."]);
}
?>

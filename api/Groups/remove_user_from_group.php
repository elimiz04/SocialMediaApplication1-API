<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");

// Include database
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get raw JSON input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->group_id) && !empty($data->user_id)) {
    $group_id = intval($data->group_id);
    $user_id = intval($data->user_id);

    // Prepare delete query
    $query = "DELETE FROM group_members WHERE group_id = :group_id AND user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':group_id', $group_id);
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(["message" => "User removed from group successfully."]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "User not found in the group."]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to remove user from group."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields: group_id and user_id."]);
}
?>

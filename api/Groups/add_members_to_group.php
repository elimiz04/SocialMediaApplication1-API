<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Include DB connection
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get JSON input
$data = json_decode(file_get_contents("php://input"));

// Check for valid input
if (!empty($data->group_id) && !empty($data->user_id)) {
    $group_id = intval($data->group_id);
    $user_id = intval($data->user_id);

    // Check if user is already in the group
    $checkQuery = "SELECT * FROM group_members WHERE group_id = :group_id AND user_id = :user_id";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':group_id', $group_id);
    $checkStmt->bindParam(':user_id', $user_id);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(["message" => "User is already a member of this group."]);
    } else {
        $query = "INSERT INTO group_members (group_id, user_id) VALUES (:group_id, :user_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':group_id', $group_id);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "User added to group successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to add user to group."]);
        }
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "group_id and user_id are required."]);
}

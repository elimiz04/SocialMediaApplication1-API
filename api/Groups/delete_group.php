<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");

// Include DB connection
include_once(__DIR__ . '/../utils/database.php');

// Connect
$db = new Database();
$conn = $db->connect();

// Get input
$data = json_decode(file_get_contents("php://input"));

// Check required input
if (!empty($data->group_id)) {
    $group_id = intval($data->group_id);

    // First, delete related members
    $delete_members = $conn->prepare("DELETE FROM group_members WHERE group_id = :group_id");
    $delete_members->bindParam(':group_id', $group_id);
    $delete_members->execute();

    // Now delete the group
    $stmt = $conn->prepare("DELETE FROM groups WHERE group_id = :group_id");
    $stmt->bindParam(':group_id', $group_id);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(["message" => "Group deleted successfully."]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Group not found."]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete group."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing group_id."]);
}
?>

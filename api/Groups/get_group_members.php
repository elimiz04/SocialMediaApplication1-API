<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include DB connection
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Check if group_id is provided via GET
if (isset($_GET['group_id'])) {
    $group_id = intval($_GET['group_id']);

    // Query to get members of the group
    $query = "
        SELECT u.user_id, u.username, u.email
        FROM group_members gm
        JOIN users u ON gm.user_id = u.user_id
        WHERE gm.group_id = :group_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':group_id', $group_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($members);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "No members found for this group."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing group_id parameter."]);
}

<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include database
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Query to get all groups
$query = "SELECT group_id, name, description, created_at FROM groups ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();

// Check if groups found
if ($stmt->rowCount() > 0) {
    $groups = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $groups[] = $row;
    }

    http_response_code(200);
    echo json_encode($groups);
} else {
    http_response_code(404);
    echo json_encode(["message" => "No groups found"]);
}
?>

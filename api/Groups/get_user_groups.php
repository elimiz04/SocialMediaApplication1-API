<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include database
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Check if user_id is provided
if (!isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(["message" => "Missing user_id parameter."]);
    exit;
}

$user_id = intval($_GET['user_id']);

// Query to get groups the user belongs to
$query = "
    SELECT g.group_id, g.name, g.description, g.created_at 
    FROM group_members gm 
    JOIN groups g ON gm.group_id = g.group_id 
    WHERE gm.user_id = :user_id
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

// Check and return results
if ($stmt->rowCount() > 0) {
    $groups = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $groups[] = $row;
    }

    http_response_code(200);
    echo json_encode($groups);
} else {
    http_response_code(404);
    echo json_encode(["message" => "This user is not in any group."]);
}
?>

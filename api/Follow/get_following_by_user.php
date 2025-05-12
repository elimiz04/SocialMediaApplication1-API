<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include DB config
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Check for user_id in query string
if (!isset($_GET['user_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing user_id parameter"]);
    exit;
}

$user_id = intval($_GET['user_id']);

// Fetch users this user is following
$query = "
    SELECT u.user_id, u.username, u.email
    FROM follows f
    JOIN users u ON f.followed_id = u.user_id
    WHERE f.follower_id = :user_id
";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $following = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($following);
} else {
    http_response_code(404);
    echo json_encode(["message" => "This user is not following anyone."]);
}

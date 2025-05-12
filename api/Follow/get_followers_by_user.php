<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include database
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get user_id from query string
if (!isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(["message" => "Missing user_id parameter"]);
    exit;
}

$user_id = intval($_GET['user_id']);

// Prepare SQL to fetch followers
$query = "
    SELECT u.user_id, u.username, u.email
    FROM Follows f
    JOIN users u ON f.follower_id = u.user_id
    WHERE f.followed_id = :user_id
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

// Return results
if ($stmt->rowCount() > 0) {
    $followers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($followers);
} else {
    echo json_encode(["message" => "No followers found"]);
}

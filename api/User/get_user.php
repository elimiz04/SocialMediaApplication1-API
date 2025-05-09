<?php
// Allow access from any origin and return JSON responses
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include database connection
include_once(__DIR__ . '/../utils/database.php');

$db = new Database();
$conn = $db->connect();

// Check if a user ID was provided in the query string
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Prepare SQL query to fetch user by ID
    $stmt = $conn->prepare("SELECT user_id, username, email FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Check if user was found
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Send success response
        http_response_code(200);
        echo json_encode($user);
    } else {
        // No user found
        http_response_code(404);
        echo json_encode(["message" => "User not found."]);
    }

    $stmt = null;
    $conn = null;
} else {
    // Invalid or missing ID
    http_response_code(400);
    echo json_encode(["message" => "Invalid or missing user_id parameter."]);
}
?>

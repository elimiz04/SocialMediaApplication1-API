<?php
// Set headers for cross-origin and JSON handling
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

// Check required fields
if (!empty($data->username) && !empty($data->email) && !empty($data->password)) {
    // Sanitize inputs
    $username = htmlspecialchars(strip_tags(trim($data->username)));
    $email = htmlspecialchars(strip_tags(trim($data->email)));
    $password = password_hash($data->password, PASSWORD_DEFAULT);

    // Check if username already exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $checkStmt->bindParam(":username", $username);
    $checkStmt->execute();
    if ($checkStmt->fetchColumn() > 0) {
        http_response_code(409); // Conflict
        echo json_encode(["message" => "Username already exists."]);
        exit;
    }

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password);

    if ($stmt->execute()) {
        http_response_code(201); // Created
        echo json_encode(["message" => "User created successfully."]);
    } else {
        http_response_code(500); // Server error
        echo json_encode(["message" => "Failed to create user."]);
    }
} else {
    http_response_code(400); // Bad request
    echo json_encode(["message" => "Missing required fields (username, email, password)."]);
}
?>

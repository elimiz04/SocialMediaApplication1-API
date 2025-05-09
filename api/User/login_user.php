<?php
// Allow access from any origin
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Include database connection
include_once(__DIR__ . '/../utils/database.php');

// Create DB connection
$db = new Database();
$conn = $db->connect();

// Get JSON input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!empty($data->username) && !empty($data->password)) {
    // Sanitize input
    $username = trim(htmlspecialchars(strip_tags($data->username)));
    $password = $data->password;

    // Optional: case-insensitive username match
    $stmt = $conn->prepare("SELECT * FROM users WHERE LOWER(username) = LOWER(:username) LIMIT 1");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Check if user exists
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if (password_verify($password, $user['password'])) {
            http_response_code(200);
            echo json_encode([
                "message" => "Login successful",
                "user_id" => $user['user_id'],
                "username" => $user['username'],
                "email" => $user['email']
            ]);
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(["message" => "Invalid password"]);
        }
    } else {
        http_response_code(404); // Not found
        echo json_encode(["message" => "User not found"]);
    }
} else {
    http_response_code(400); // Bad request
    echo json_encode(["message" => "Username and password are required"]);
}
?>

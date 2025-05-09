<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once(__DIR__ . '/../utils/database.php');

$db = new Database();
$conn = $db->connect();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->username) && !empty($data->email) && !empty($data->password)) {
    $username = htmlspecialchars(strip_tags($data->username));
    $email = htmlspecialchars(strip_tags($data->email));
    $password = password_hash($data->password, PASSWORD_DEFAULT);

    // 🔎 Check if the username already exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $checkStmt->bindParam(':username', $username);
    $checkStmt->execute();
    $userExists = $checkStmt->fetchColumn();

    if ($userExists > 0) {
        http_response_code(409); // Conflict
        echo json_encode(["message" => "Username already exists. Please choose another."]);
        exit;
    }

    // ✅ Proceed to insert if username is unique
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "User created successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "User creation failed."]);
    }

    $stmt = null;
    $db = null;
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields (username, email, password)."]);
}

$conn = null;

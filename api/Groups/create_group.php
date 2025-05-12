<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Include DB connection
include_once(__DIR__ . '/../utils/database.php');

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Get input
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->name) && !empty($data->description)) {
    $name = htmlspecialchars(strip_tags($data->name));
    $description = htmlspecialchars(strip_tags($data->description));

    $query = "INSERT INTO groups (name, description) VALUES (:name, :description)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Group created successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to create group."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Group name and description are required."]);
}

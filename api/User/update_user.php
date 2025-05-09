<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");

include_once(__DIR__ . '/../utils/database.php');

$db = new Database();
$conn = $db->connect();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->user_id)) {
    $user_id = htmlspecialchars(strip_tags($data->user_id));

    $fields = [];
    $params = [];

    if (!empty($data->username)) {
        $fields[] = "username = :username";
        $params[':username'] = htmlspecialchars(strip_tags($data->username));
    }

    if (!empty($data->email)) {
        $fields[] = "email = :email";
        $params[':email'] = htmlspecialchars(strip_tags($data->email));
    }

    if (!empty($data->password)) {
        $fields[] = "password = :password";
        $params[':password'] = password_hash($data->password, PASSWORD_DEFAULT);
    }

    if (empty($fields)) {
        http_response_code(400);
        echo json_encode(["message" => "No fields provided for update."]);
        exit;
    }

    $setClause = implode(', ', $fields);
    $sql = "UPDATE users SET $setClause WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $params[':user_id'] = $user_id;

    if ($stmt->execute($params)) {
        echo json_encode(["message" => "User updated successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Update failed."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "User ID is required."]);
}
?>

<?php
session_start();
include("../includes/connection.php");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    if (empty($name) || empty($description)) {
        echo "Name or description cannot be empty";
        exit;
    }

    $insert_group_query = "INSERT INTO groups (name, description, created_at) VALUES (?, ?, NOW())";
    $stmt_insert_group = $conn->prepare($insert_group_query);
    if ($stmt_insert_group === false) {
        echo "Failed to prepare statement: " . $conn->error;
        exit;
    }
    $stmt_insert_group->bind_param("ss", $name, $description);

    if ($stmt_insert_group->execute()) {
        header("Location: group.php");
        exit;
    } else {
        echo "Error executing query: " . $stmt_insert_group->error;
        exit;
    }
} else {
    echo "Invalid request method";
    exit;
}
?>

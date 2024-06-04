<?php
session_start();
include("../includes/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve user ID from session
    $user_id = $_SESSION['user_id'];

    // Retrieve form data
    $color_scheme = $_POST['color_scheme'];
    $receive_notifications = isset($_POST['receive_notifications']) ? 1 : 0;

    // Update settings in the database
    $query = "REPLACE INTO Settings (user_id, color_scheme, notifications_enabled, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $color_scheme, $receive_notifications);

    if ($stmt->execute()) {
        // Settings updated successfully
        echo json_encode(array('success' => true));
    } else {
        // Log the error
        error_log('Error updating settings: ' . $stmt->error);
        echo json_encode(array('success' => false, 'error' => 'Error updating settings'));
    }
    
    $stmt->close();
}

$conn->close();
?>

<?php
session_start();
include("../includes/connection.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('success' => false, 'error' => 'User not logged in.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $color_scheme = isset($_POST['color_scheme']) ? $_POST['color_scheme'] : 'light'; // Default to light mode if not set
    $notifications_enabled = isset($_POST['receive_notifications']) ? 1 : 0; // 1 if checked, 0 if not checked

    // Update user's settings in the user_settings table
    $query = "INSERT INTO user_settings (user_id, color_scheme, receive_notifications) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE color_scheme = VALUES(color_scheme), receive_notifications = VALUES(receive_notifications)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $color_scheme, $notifications_enabled);

    if ($stmt->execute()) {
        // Update session values
        $_SESSION['color_scheme'] = $color_scheme; // Update color scheme in session
        $_SESSION['receive_notifications'] = $notifications_enabled; // Update notification preference in session

        echo json_encode(array('success' => true, 'color_scheme' => $color_scheme));
    } else {
        // Log the error
        error_log('Error updating settings: ' . $stmt->error);
        // Return error response
        echo json_encode(array('success' => false, 'error' => 'Error updating settings'));
    }

    $stmt->close();
}

$conn->close();
?>

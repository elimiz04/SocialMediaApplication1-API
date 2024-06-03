<?php
session_start();
include("../includes/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Update the notification count to 0 for the logged-in user
    $update_query = "UPDATE Messages SET is_read = 1 WHERE receiver_id = ?";
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param("i", $user_id);
    $stmt_update->execute();
    
    // Close the statement and connection
    $stmt_update->close();
    $conn->close();
}

?>

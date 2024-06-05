<?php
// Include database connection
include("../includes/connection.php");

// Start session
session_start();

// Check if group_id and message are provided in the POST data
if (isset($_POST['group_id'], $_POST['message'])) {
    $group_id = $_POST['group_id'];
    $message = $_POST['message'];

    // Retrieve sender's username from the session
    $sender_id = $_SESSION['user_id'];
    $get_sender_query = "SELECT username FROM users WHERE user_id = ?";
    $stmt_get_sender = $conn->prepare($get_sender_query);

    if ($stmt_get_sender === false) {
        echo "Failed to prepare statement: " . $conn->error;
        exit;
    }

    $stmt_get_sender->bind_param("i", $sender_id);

    if (!$stmt_get_sender->execute()) {
        echo "Error executing query: " . $stmt_get_sender->error;
        exit;
    }

    $sender_result = $stmt_get_sender->get_result();

    if ($sender_result->num_rows > 0) {
        $sender = $sender_result->fetch_assoc();
        $sender_username = $sender['username'];
    } else {
        echo "Sender not found.";
        exit;
    }

    // Insert the message into the messages table
    $insert_query = "INSERT INTO messages (group_id, sender_id, content, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
    $stmt_insert_message = $conn->prepare($insert_query);

    if ($stmt_insert_message === false) {
        echo "Failed to prepare statement: " . $conn->error;
        exit;
    }

    $stmt_insert_message->bind_param("iis", $group_id, $sender_id, $message);

    if (!$stmt_insert_message->execute()) {
        echo "Error inserting message: " . $stmt_insert_message->error;
        exit;
    }

    // Redirect back to the group.php page after sending the message
    header("Location: group.php?group_id=$group_id");
    exit;
} else {
    // Redirect back to the group.php page with an error message if group_id or message are not provided
    header("Location: group.php?group_id=$group_id&error=1");
    exit;
}
?>

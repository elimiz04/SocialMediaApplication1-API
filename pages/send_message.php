<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $content = $_POST['content'];

    // Insert message into Messages table
    $query = "INSERT INTO Messages (sender_id, receiver_id, content, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    } $stmt->bind_param("iis", $sender_id, $receiver_id, $content);

    if ($stmt->execute()) {
        // Get the last inserted message ID
        $message_id = $stmt->insert_id;

        // Insert notification into Notifications table
        $notification_message = "You have received a new message.";
        $notification_query = "INSERT INTO Notifications (user_id, message, is_read, created_at) VALUES (?, ?, 0, NOW())";
        $stmt_notification = $conn->prepare($notification_query);
        if ($stmt_notification === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt_notification->bind_param("is", $receiver_id, $notification_message);

        if ($stmt_notification->execute()) {
            // Redirect to messages.php after successful message send
            header("Location: messages.php");
            exit();
        } else {
            die('Execute failed: ' . htmlspecialchars($stmt_notification->error));
        }
        $stmt_notification->close();
    } else {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
}
// Check if the user has set a color mode preference
if (!isset($_SESSION['color_mode'])) {
    // If not, set a default color mode (e.g., light mode)
    $_SESSION['color_mode'] = 'light';
}
// Function to apply the appropriate CSS class based on the color mode
function getColorModeClass() {
    return $_SESSION['color_mode'] === 'light' ? 'light-mode' : 'dark-mode';
}

$conn->close();
?>
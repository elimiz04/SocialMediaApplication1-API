<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die('User not logged in.');
    }

    if (!isset($_POST['receiver_id']) || !isset($_POST['content'])) {
        die('Missing required parameters.');
    }

    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $content = $_POST['content'];

    $query = "INSERT INTO Messages (sender_id, receiver_id, content, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("iis", $sender_id, $receiver_id, $content);

    if ($stmt->execute()) {
        $notification_message = "You have received a new message.";
        $notification_query = "INSERT INTO Notifications (user_id, message, is_read, created_at) VALUES (?, ?, 0, NOW())";
        $stmt_notification = $conn->prepare($notification_query);
        if ($stmt_notification === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt_notification->bind_param("is", $receiver_id, $notification_message);

        if ($stmt_notification->execute()) {
            echo "Message sent successfully";
        } else {
            die('Execute failed: ' . htmlspecialchars($stmt_notification->error));
        }
        $stmt_notification->close();
    } else {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
}

$conn->close();
?>

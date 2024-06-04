<?php
session_start();
include("../includes/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message_id'])) {
    $message_id = $_POST['message_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the message belongs to the logged-in user
    $query = "SELECT sender_id FROM Messages WHERE message_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sender_id = $row['sender_id'];

        if ($sender_id == $user_id) {
            // Delete the message from the database
            $delete_query = "DELETE FROM Messages WHERE message_id = ?";
            $stmt_delete = $conn->prepare($delete_query);
            $stmt_delete->bind_param("i", $message_id);

            if ($stmt_delete->execute()) {
                // Redirect to the messages page after successful deletion
                header("Location: messages.php");
                exit();
            } else {
                echo "Error deleting message: " . $conn->error;
            }
        } else {
            echo "You are not authorized to delete this message.";
        }
    } else {
        echo "Message not found.";
    }

    $stmt->close();
    $stmt_delete->close();
}

$conn->close();
?>

<?php
session_start();
include("../includes/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message_id']) && isset($_POST['content']) && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $message_id = $_POST['message_id'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $query = "UPDATE Messages SET content = ?, updated_at = NOW() WHERE message_id = ? AND sender_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $content, $message_id, $user_id);
    $stmt->execute();

    header("Location: messages.php");
    exit;
}
?>

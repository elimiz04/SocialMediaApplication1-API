<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message_id = $_POST['message_id'];
    $content = $_POST['content'];

    $query = "UPDATE Messages SET content = ? WHERE message_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $content, $message_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to messages page
    header("Location: messages.php");
    exit();
}
?>

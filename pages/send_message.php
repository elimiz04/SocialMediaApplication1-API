<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $content = $_POST['content'];

    $query = "INSERT INTO Messages (sender_id, receiver_id, content, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $sender_id, $receiver_id, $content);
    $stmt->execute();
    $stmt->close();

    // Redirect back to messages page
    header("Location: messages.php");
    exit();
}
?>

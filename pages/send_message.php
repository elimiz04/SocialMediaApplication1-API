<?php
session_start();
include("../includes/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content']) && isset($_POST['action']) && $_POST['action'] == 'send') {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $content = $_POST['content'];
    $created_at = date('Y-m-d H:i:s');

    $query = "INSERT INTO Messages (sender_id, receiver_id, content, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss", $sender_id, $receiver_id, $content, $created_at);
    $stmt->execute();

    header("Location: ../pages/messages.php");
    exit;
}
?>

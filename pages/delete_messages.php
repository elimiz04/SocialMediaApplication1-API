<?php
session_start();
include("../includes/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message_id']) && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $message_id = $_POST['message_id'];
    $user_id = $_SESSION['user_id'];

    $query = "DELETE FROM Messages WHERE message_id = ? AND sender_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $message_id, $user_id);
    $stmt->execute();

    header("Location: messages.php");
    exit;
}
?>
<form id="deleteForm<?php echo $message['message_id']; ?>" action="pages/delete_message.php" method="POST">

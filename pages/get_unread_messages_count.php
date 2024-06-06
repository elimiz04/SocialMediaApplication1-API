<?php
include("../includes/connection.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT COUNT(*) AS unread_count FROM messages WHERE receiver_id = ? AND is_read = 0"; // Assuming the column name is 'receiver_id'
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo $row['unread_count'];
} else {
    echo 0;
}

$stmt->close();
$conn->close();
?>

<?php
session_start();
include("../includes/connection.php");

if (isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];

    if (empty($group_id)) {
        echo "Group ID is missing";
        exit;
    }

    $get_members_query = "SELECT u.username, u.email FROM group_members gm JOIN users u ON gm.user_id = u.user_id WHERE gm.group_id = ?";
    $stmt_get_members = $conn->prepare($get_members_query);

    if ($stmt_get_members === false) {
        echo "Failed to prepare statement: " . $conn->error;
        exit;
    }

    $stmt_get_members->bind_param("i", $group_id);

    if (!$stmt_get_members->execute()) {
        echo "Error executing query: " . $stmt_get_members->error;
        exit;
    }

    $members_result = $stmt_get_members->get_result();
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Group Members</title>
    </head>
    <body>
    <h2>Group Members</h2>";

    if ($members_result->num_rows > 0) {
        while ($member = $members_result->fetch_assoc()) {
            echo htmlspecialchars($member['username']) . " - " . htmlspecialchars($member['email']) . "<br>";
        }
    } else {
        echo "No members found in this group.";
    }

    echo "</body>
    </html>";

} else {
    header("Location: error.php");
    exit;
}
?>

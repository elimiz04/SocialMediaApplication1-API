<?php
session_start();
include("../includes/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_id = $_POST['group_id'];
    $user_ids = explode(',', $_POST['user_ids']); 

    $insert_member_query = "INSERT INTO group_members (group_id, user_id, joined_at) VALUES (?, ?, NOW())";
    $stmt_insert_member = $conn->prepare($insert_member_query);

    foreach ($user_ids as $user_id) {
        $user_id = trim($user_id);
        $stmt_insert_member->bind_param("ii", $group_id, $user_id);
        $stmt_insert_member->execute();
    }

    header("Location: group.php");
    exit;
} else {
    header("Location: error.php");
    exit;
}
?>

<?php
session_start();
include("../includes/connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && isset($_GET['target_user_id'])) {
    $follower_id = $_SESSION['user_id'];
    $target_user_id = $_GET['target_user_id'];
    $action = $_GET['action'];

    if ($action == 'follow') {
        // Insert a new record into the Follows table
        $follow_query = "INSERT INTO Follows (follower_id, followed_id, created_at) VALUES (?, ?, NOW())";
        $stmt_follow = $conn->prepare($follow_query);
        $stmt_follow->bind_param("ii", $follower_id, $target_user_id);
        $stmt_follow->execute();
    } elseif ($action == 'unfollow') {
        // Delete the existing record from the Follows table
        $unfollow_query = "DELETE FROM Follows WHERE follower_id = ? AND followed_id = ?";
        $stmt_unfollow = $conn->prepare($unfollow_query);
        $stmt_unfollow->bind_param("ii", $follower_id, $target_user_id);
        $stmt_unfollow->execute();
    }
}

header("Location: ../pages/profile.php");
exit;
?>

<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['action']) || !isset($_POST['user_id'])) {
    header("Location: profile.php");
    exit;
}

$currentUserId = $_SESSION['user_id'];
$userId = $_POST['user_id'];
$action = $_POST['action'];

if ($action === 'follow') {
    $query = "INSERT INTO follows (follower_id, followed_id, created_at) VALUES ($currentUserId, $userId, NOW())";
} elseif ($action === 'unfollow') {
    $query = "DELETE FROM follows WHERE follower_id = $currentUserId AND followed_id = $userId";
}

if (mysqli_query($conn, $query)) {
    header("Location: profile.php?id=$userId");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

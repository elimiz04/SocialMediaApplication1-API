<?php
session_start();
include("../includes/connection.php"); 
include("../includes/functions.php");
include("../includes/header.php");

// Check if user is logged in, otherwise redirect to login page
if(!isset($_SESSION['user_id'])){
    header("Location: ../pages/login.php");
    die;
}

// Retrieve users who are following the current user from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT u.username FROM follows f JOIN users u ON f.follower_id = u.user_id WHERE f.followed_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Display followers
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Followers</title>
</head>
<body>
    <h1>Followers</h1>
    <ul>
        <?php while($follower = $result->fetch_assoc()): ?>
            <li><?php echo $follower['username']; ?></li>
        <?php endwhile; ?>
    </ul>
</body>
</html>

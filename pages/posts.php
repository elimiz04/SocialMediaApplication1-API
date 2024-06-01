<?php
session_start();
include("../includes/connection.php"); 
include("../includes/functions.php");
include("../includes/header.php");

// Check if user is logged in, otherwise redirect to login page
if(!isset($_SESSION['user_id'])){
    header("Location: ../login/login.php");
    die;
}

// Retrieve user's posts from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM posts WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Display user's posts
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Posts</title>
</head>
<body>
    <h1>Your Posts</h1>
    <?php while($post = $result->fetch_assoc()): ?>
        <div>
            <p><?php echo $post['content']; ?></p>
        </div>
    <?php endwhile; ?>
    <!-- Add form to allow user to add new posts -->
    <form method="post" action="add_post.php">
        <textarea name="content" placeholder="Write a new post..."></textarea>
        <button type="submit">Post</button>
    </form>
</body>
</html>

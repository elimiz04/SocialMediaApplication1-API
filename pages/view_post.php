<?php
ob_start(); // Start output buffering
session_start();
include("../includes/connection.php"); 
include("../includes/functions.php");
include("../includes/header.php");

// Check if user is logged in, otherwise redirect to login page
if(!isset($_SESSION['user_id'])){
    header("Location: ../pages/login.php");
    die;
}

// Check if post_id is provided in the URL
if(isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
} else {
    // Redirect if post_id is not provided
    header("Location: profile.php");
    exit;
}

// Retrieve post details from the database
$query_post = "SELECT * FROM posts WHERE post_id = ?";
$stmt_post = $conn->prepare($query_post);
$stmt_post->bind_param("i", $post_id);
$stmt_post->execute();
$post_result = $stmt_post->get_result();

// Check if the post exists
if($post_result->num_rows == 1) {
    $post = $post_result->fetch_assoc();
} else {
    // Redirect if post does not exist
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Post</title>
</head>
<body>
    <!-- Add your HTML content here -->
    <div class="post-container">
        <div class="post">
            <img src="../assets/<?php echo $post['image']; ?>" alt="Post Image">
            <div class="post-content">
                <p><?php echo $post['caption']; ?></p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
ob_end_flush(); // End output buffering and flush buffer contents
?>

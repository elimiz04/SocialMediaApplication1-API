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

// Check if the comment ID is provided in the URL
if(isset($_GET['comment_id'])) {
    $comment_id = $_GET['comment_id'];

    // Retrieve comment data from the database
    $query = "SELECT * FROM comments WHERE comment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
        $comment = $result->fetch_assoc();
        // Check if the comment belongs to the current user
        if ($_SESSION['user_id'] != $comment['user_id']) {
            // Redirect to view image page if user doesn't own the comment
            header("Location: view_image.php?image_id=" . $comment['post_id']);
            die;
        }
    } else {
        // Comment not found, handle error
        echo "Comment not found.";
        die;
    }
} else {
    // Comment ID not provided, handle error
    echo "Comment ID not provided.";
    die;
}

// Handle comment update submission
if(isset($_POST['content'])) {
    // Retrieve updated comment content from the form
    $content = $_POST['content'];
    
    // Update the comment in the database
    $query = "UPDATE comments SET content = ? WHERE comment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $content, $comment_id);
    
    if($stmt->execute()) {
        // Comment updated successfully, redirect to view image page
        header("Location: view_image.php?image_id=" . $comment['post_id']);
        die;
    } else {
        // Error updating comment
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Comment</title>
</head>
<body>
    <form method="post">
        <textarea name="content"><?php echo htmlspecialchars($comment['content']); ?></textarea>
        <button type="submit">Update Comment</button>
    </form>
</body>
</html>

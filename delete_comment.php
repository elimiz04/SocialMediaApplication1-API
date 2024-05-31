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
    $stmt = $con->prepare($query);
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

        // Delete the comment
        $query = "DELETE FROM comments WHERE comment_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $comment_id);
        
        if($stmt->execute()) {
            // Comment deleted successfully, redirect to view image page
            header("Location: view_image.php?image_id=" . $comment['post_id']);
            die;
        } else {
            // Error deleting comment
            echo "Error: " . $stmt->error;
        }
    } else {
        // Comment not found, handle error
        echo "Comment not found.";
    }
} else {
    // Comment ID not provided, handle error
    echo "Comment ID not provided.";
}
?>

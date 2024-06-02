<?php
session_start();
include("../includes/connection.php"); 
include("../includes/functions.php");
include("../includes/header.php");

// Check if post_id is provided in the URL
if(isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Retrieve post data from the database based on the post_id
    $query = "SELECT * FROM posts WHERE post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
        $post = $result->fetch_assoc();
    } else {
        // Post not found, handle error
        echo "Post not found.";
        die;
    }
} else {
    // Post ID not provided, handle error
    echo "Post ID not provided.";
    die;
}

// Handle adding a post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content'])) {
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];

    // Check if an image was uploaded
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    }

    // Insert new post into database
    $query = "INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $content, $image);
    $stmt->execute();

    header("Location: ../pages/profile.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content']) && isset($_POST['comment_id'])) {
    $content = $_POST['content'];
    $comment_id = $_POST['comment_id'];

    $query = "UPDATE comments SET content = ? WHERE comment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $content, $comment_id);
    
    if($stmt->execute()) {
        header("Location: post_handler.php?comment_id=" . $comment_id);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Handler</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        #box {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            text-align: justify;
        }
        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .image-container a {
            width: calc(33.33% - 20px); 
            margin: 10px;
            text-align: center;
            text-decoration: none; 
            color: inherit; 
            height: 200px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            overflow: hidden; 
        }
        .image-container img {
            max-width: 50%; 
            max-height: 50%; 
            height: auto; 
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); 
        }
        #commentForm {
            margin-top: 20px;
        }

        .comment-input {
            width: 100%;
            height: 80px;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: solid thin #aaa;
            resize: none;
        }

        .comment-submit {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #337ab7;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .comment-submit:hover {
            background-color: #286090;
        }   
        .like-button {
            width: 50px;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #337ab7;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .like-button:hover {
            background-color: #286090;
        }

        .button-container {
            margin-top: 10px;
        }

        .edit-button, .delete-button {
            padding: 5px 10px;
            margin-right: 5px;
            border: none;
            border-radius: 5px;
            background-color: #337ab7;
            color: white;
            cursor: pointer;
            text-decoration: none;
        }

        .edit-button:hover, .delete-button:hover {
            background-color: #286090;
        }

        .comment-form-container {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div id="box">
    

    <!-- Edit Comment Form -->
    <?php if(isset($_GET['comment_id'])): ?>
    <?php
    $comment_id = $_GET['comment_id'];
    $query = "SELECT * FROM comments WHERE comment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
        $comment = $result->fetch_assoc();
        if ($_SESSION['user_id'] == $comment['user_id']) {
    ?>
    <h1>Edit Comment</h1>
    <form method="post">
        <textarea name="content"><?php echo htmlspecialchars($comment['content']); ?></textarea>
        <button type="submit">Update Comment</button>
    </form>
    <?php
        }
    }
    ?>
    <?php endif; ?>

    <!-- Display post -->
    <?php if(isset($post) && !empty($post)): ?>
        <h2>Post</h2>
        <p><?php echo $post['content']; ?></p>
        <img src="../assets/<?php echo $post['image']; ?>" alt="Post Image">
    <?php else: ?>
        <p>Post not found or unavailable.</p>
    <?php endif; ?>

    <!-- Like button -->
    <form method="post" id="likeForm">
        <button type="submit" name="like" class="like-button">Like</button>
    </form>
    
    <!-- Comment form -->
    <form method="post" id="commentForm">
        <textarea name="content" placeholder="Leave a comment..." class="comment-input"></textarea>
        <button type="submit" class="comment-submit">Submit</button>
    </form>
    
    <!-- Display comments -->
    <div class="comments">
    <?php
    // Fetch comments from the database
    $query = "SELECT * FROM comments WHERE post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $comments_result = $stmt->get_result();

    // Loop through comments
    while ($comment = $comments_result->fetch_assoc()) {
        // Fetch the username of the commenter
        $query = "SELECT username FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $comment['user_id']);
        $stmt->execute();
        $username_result = $stmt->get_result();
        $username = $username_result->fetch_assoc()['username'];

        // Display the comment and username
        echo "<p><strong>$username</strong>: " . $comment['content'] . "</p>";
        
        // If the user is the owner of the comment, show Edit/Delete options
        if ($comment['user_id'] == $_SESSION['user_id']) {
            echo "<div class='button-container'>";
            echo "<a href='post_handler.php?post_id=$post_id&edit_comment_id=" . $comment['comment_id'] . "' class='edit-button'>Edit</a>";
            echo "<form method='post' style='display:inline;'>";
            echo "<input type='hidden' name='delete_comment_id' value='" . $comment['comment_id'] . "'>";
            echo "<button type='submit' class='delete-button'>Delete</button>";
            echo "</form>";
            echo "</div>";
            
            // Display edit form if this comment is being edited
            if (isset($_GET['edit_comment_id']) && $_GET['edit_comment_id'] == $comment['comment_id']) {
                echo "<div class='comment-form-container'>";
                echo "<form method='post'>";
                echo "<textarea name='content' class='comment-input'>" . htmlspecialchars($comment['content']) . "</textarea>";
                echo "<input type='hidden' name='comment_id' value='" . $comment['comment_id'] . "'>";
                echo "<button type='submit' class='comment-submit'>Update Comment</button>";
                echo "</form>";
                echo "</div>";
            }
        }
    }
    ?>
</div>

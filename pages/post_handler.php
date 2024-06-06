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


// Check if post ID is provided in the URL
if (!isset($_GET['post_id'])) {
    echo "Post ID not provided.";
    die;
}

// Get the post ID
$post_id = $_GET['post_id'];

// Retrieve the post details from the database
$query_post = "SELECT * FROM posts WHERE post_id = ?";
$stmt_post = $conn->prepare($query_post);
$stmt_post->bind_param("i", $post_id);
$stmt_post->execute();
$post_result = $stmt_post->get_result();

if ($post_result->num_rows != 1) {
    echo "Post not found.";
    die;
}

$post = $post_result->fetch_assoc();

// Handle new comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment_content'])) {
    $user_id = $_SESSION['user_id'];
    $comment_content = $_POST['comment_content'];
    $query_comment = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
    $stmt_comment = $conn->prepare($query_comment);
    $stmt_comment->bind_param("iis", $post_id, $user_id, $comment_content);
    $stmt_comment->execute();

    header("Location: post_handler.php?post_id=" . $post_id);
    exit;
}
// Handle delete comment request
if (isset($_POST['delete_comment_id'])) {
    $comment_id = $_POST['delete_comment_id'];
    $query_delete = "DELETE FROM comments WHERE comment_id = ? AND user_id = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param("ii", $comment_id, $_SESSION['user_id']);
    $stmt_delete->execute();
    header("Location: post_handler.php?post_id=" . $post_id);
    exit;
}

// Handle edit comment request
if (isset($_POST['edit_comment_id']) && isset($_POST['edit_comment_content'])) {
    $comment_id = $_POST['edit_comment_id'];
    $comment_content = $_POST['edit_comment_content'];
    $query_edit = "UPDATE comments SET content = ? WHERE comment_id = ? AND user_id = ?";
    $stmt_edit = $conn->prepare($query_edit);
    $stmt_edit->bind_param("sii", $comment_content, $comment_id, $_SESSION['user_id']);
    $stmt_edit->execute();
    header("Location: post_handler.php?post_id=" . $post_id);
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Post</title>
    <style>
        /* CSS Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            background-color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#333' : '#f8f9fa'; ?>;
            color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#f8f9fa' : '#333'; ?>;
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        #box {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            background-color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '000' : '#d7d9db'; ?>;
            color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#f8f9fa' : '#333'; ?>;
            
        }
        .btn-container {
            margin-top: 20px;
            text-align: center;
        }
        .minimal-btn {
            padding: 10px 20px;
            background-color: transparent;
            color: #337ab7;
            border: 1px solid #337ab7;
            border-radius: 5px;
            text-decoration: none;
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3
        }
            .minimal-btn:hover {
            background-color: #337ab7;
            color: white;
            border-color: #337ab7;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #337ab7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #286090;
        }
        .comment-container {
            text-align: left;
            margin-top: 20px;
        }
        .comment {
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            position: relative;
        }
        .comment-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .comment-buttons form {
            display: inline;
        }
        .comment-buttons button {
            background-color: #dc3545;
            border: none;
            padding: 5px 10px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
        }
        .comment-buttons button:hover {
            background-color: #c823
            <div class="comment-buttons button">
            background-color: #c82333;
        }
        .edit-form {
            display: none;
        }
    </style>
</head>
<div id="box">
        <h1>View Post</h1>
        <div>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <?php if ($post['image']): ?>
                <img src="../assets/<?php echo $post['image']; ?>" alt="Post Image" style="max-width:100%;">
            <?php endif; ?>
        </div>
        
        <form method="post" action="">
            <textarea name="comment_content" placeholder="Write a comment..."></textarea>
            <button type="submit">Add Comment</button>
        </form>
        
        <div class="comment-container">
            <h2>Comments</h2>
            <?php
            // Retrieve comments for the post
            $query_comments = "SELECT * FROM comments WHERE post_id = ? ORDER BY comment_id DESC";
            $stmt_comments = $conn->prepare($query_comments);
            $stmt_comments->bind_param("i", $post_id);
            $stmt_comments->execute();
            $comments_result = $stmt_comments->get_result();
            if ($comments_result->num_rows > 0) {
                while ($comment = $comments_result->fetch_assoc()) {
                    echo "<div class='comment' id='comment-".$comment['comment_id']."'>
                            <p>" . htmlspecialchars($comment['content']) . "</p>";
                    if ($comment['user_id'] == $_SESSION['user_id']) {
                        echo "<div class='comment-buttons'>
                                <form method='post' action='' onsubmit='return confirm(\"Are you sure you want to delete this comment?\");'>
                                    <input type='hidden' name='delete_comment_id' value='" . $comment['comment_id'] . "'>
                                    <button type='submit'>Delete</button>
                                </form>
                                <button onclick='editComment(".$comment['comment_id'].")'>Edit</button>
                              </div>
                              <form method='post' action='' class='edit-form' id='edit-form-".$comment['comment_id']."'>
                                <textarea name='edit_comment_content'>".htmlspecialchars($comment['content'])."</textarea>
                                <input type='hidden' name='edit_comment_id' value='" . $comment['comment_id'] . "'>
                                <button type='submit'>Save</button>
                              </form>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>No comments yet.</p>";
            }
            ?>
        </div>
    </div>
    <script>
        function editComment(commentId) {
            const editForm = document.getElementById('edit-form-' + commentId);
            editForm.style.display = 'block';
        }
    </script>
</body>
</html>


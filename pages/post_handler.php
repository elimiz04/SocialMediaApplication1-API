<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");
include("../includes/header.php");

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div id="box">
        <h1>View Post</h1>
        <div class="post-content">
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <?php if ($post['image']): ?>
                <img src="../assets/<?php echo $post['image']; ?>" alt="Post Image" class="post-image">
            <?php endif; ?>
        </div>
        
        <form method="post" action="" class="comment-form">
            <textarea name="comment_content" placeholder="Write a comment..." required></textarea>
            <button type="submit" class="btn comment-submit">Add Comment</button>
        </form>


        <div class="comment-container">
            <h2>Comments</h2>
            <?php
            // Retrieve comments and associated usernames for the post
            $query_comments = "
                SELECT comments.*, users.username 
                FROM comments 
                LEFT JOIN users ON comments.user_id = users.user_id 
                WHERE comments.post_id = ? 
                ORDER BY comments.comment_id DESC
            ";
            $stmt_comments = $conn->prepare($query_comments);
            $stmt_comments->bind_param("i", $post_id);
            $stmt_comments->execute();
            $comments_result = $stmt_comments->get_result();

            if ($comments_result->num_rows > 0) {
                while ($comment = $comments_result->fetch_assoc()) {
                    echo "<div class='comment' id='comment-".$comment['comment_id']."'>
                            <div class='comment-text'>
                                <p><strong>" . htmlspecialchars($comment['username']) . ":</strong> " . htmlspecialchars($comment['content']) . "</p>
                            </div>";
                    if ($comment['user_id'] == $_SESSION['user_id']) {
                        echo "<div class='comment-buttons'>
                                <form method='post' action='' onsubmit='return confirm(\"Are you sure you want to delete this comment?\");'>
                                    <input type='hidden' name='delete_comment_id' value='" . $comment['comment_id'] . "'>
                                    <button type='submit' class='btn delete-btn'>Delete</button>
                                </form>
                                <button onclick='editComment(".$comment['comment_id'].")' class='btn edit-btn'>Edit</button>
                            </div>
                            <form method='post' action='' class='edit-form' id='edit-form-".$comment['comment_id']."'>
                                <textarea name='edit_comment_content'>".htmlspecialchars($comment['content'])."</textarea>
                                <input type='hidden' name='edit_comment_id' value='" . $comment['comment_id'] . "'>
                                <button type='submit' class='btn'>Save</button>
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

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

// Assuming $_GET['image_id'] is the image ID passed via the URL
if(isset($_GET['image_id'])) {
    $image_id = $_GET['image_id'];

    // Retrieve image data from the imagesdata table
    $query = "SELECT * FROM imagesdata WHERE image = ?";  // Use the correct table name 'imagesdata'
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
        $image = $result->fetch_assoc();
    } else {
        echo "Image not found.";
        die;
    }
} else {
    echo "Image ID not provided.";
    die;
}

// Handle like submission
if(isset($_POST['like'])) {
    // Insert the like into the database
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $image_id);
    
    if(!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
}

// Handle comment submission
if(isset($_POST['content']) && !isset($_POST['comment_id'])) {
    // Retrieve comment content from the form
    $content = $_POST['content'];
    
    // Insert the comment into the database
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $user_id, $image_id, $content);
    
    if(!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
}

// Handle comment update submission
if(isset($_POST['edit_content']) && isset($_POST['comment_id'])) {
    // Retrieve updated comment content from the form
    $content = $_POST['edit_content'];
    $comment_id = $_POST['comment_id'];

    // Update the comment in the database
    $query = "UPDATE comments SET content = ? WHERE comment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $content, $comment_id);
    
    if(!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    } else {
        // Redirect to avoid form resubmission
        header("Location: view_image.php?image_id=" . $image_id);
        die;
    }
}

// Handle comment delete submission
if(isset($_POST['delete_comment_id'])) {
    $comment_id = $_POST['delete_comment_id'];

    // Delete the comment from the database
    $query = "DELETE FROM comments WHERE comment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $comment_id);
    
    if(!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    } else {
        // Redirect to avoid form resubmission
        header("Location: view_image.php?image_id=" . $image_id);
        die;
    }
}

// Fetch comments with user data
$query = "SELECT comments.content, users.username, users.profile_image 
        FROM comments 
        JOIN users ON comments.user_id = users.user_id 
        WHERE comments.post_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $image_id);
$stmt->execute();
$comments_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Image</title>
    <style>
        /* Add your CSS here */
    </style>
</head>
<body>
    <div id="box">
        <!-- Display image -->
        <?php if(isset($image) && !empty($image)): ?>
            <img src="../assets/<?php echo $image['filename']; ?>" alt="<?php echo $image['filename']; ?>" style="width:100%; max-width:800px;">
        <?php else: ?>
            <p>Image not found or unavailable.</p>
        <?php endif; ?>
        
        <!-- Like button -->
        <form method="post">
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
            while ($comment = $comments_result->fetch_assoc()) {
                $profileImage = !empty($comment['profile_image']) ? '../' . htmlspecialchars($comment['profile_image']) : '../assets/default-profile.png';
                echo "<div class='comment'>";
                echo "<img src='$profileImage' alt='Profile Picture' style='width:50px; height:50px; border-radius:50%; object-fit:cover;'>";
                echo "<p><strong>" . htmlspecialchars($comment['username']) . "</strong>: " . htmlspecialchars($comment['content']) . "</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>

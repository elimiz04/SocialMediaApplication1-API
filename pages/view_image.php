<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");
include("../includes/header.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    die;
}

// Color scheme logic (copied from home page)
if (!isset($_SESSION['color_scheme'])) {
    $_SESSION['color_scheme'] = 'light';
}
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT color_scheme FROM user_settings WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_color_scheme);
        $stmt->fetch();
        $_SESSION['color_scheme'] = $db_color_scheme;
    }
    $stmt->close();
}
$color_scheme = $_SESSION['color_scheme'];
$bg_color = $color_scheme === 'dark' ? '#333' : '#f8f9fa';
$text_color = $color_scheme === 'dark' ? '#f8f9fa' : '#333';
$box_bg_color = $color_scheme === 'dark' ? '#000' : '#d7d9db';

// Check if image_id is passed
if (isset($_GET['image_id'])) {
    $image_id = $_GET['image_id'];

    $query = "
        SELECT images.image_name, CONCAT('assets/', imagesdata.filename) AS image_path
        FROM images
        JOIN imagesdata ON images.image_id = imagesdata.image_id
        WHERE images.image_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $image = $result->fetch_assoc();
    } else {
        echo "<p>Image not found in the database.</p>";
        die;
    }
} else {
    echo "Image ID not provided.";
    die;
}

// Handle like/unlike submission
if (isset($_POST['like'])) {
    $user_id = $_SESSION['user_id'];
    $image_id = $_GET['image_id'];

    // Check if the user has already liked or unliked the post
    $query = "SELECT status FROM likes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $image_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // User hasn't liked the post yet, so we insert a new like with status 'liked'
        $query = "INSERT INTO likes (user_id, post_id, status) VALUES (?, ?, 'liked')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $image_id);
        $stmt->execute();
    } else {
        // User has already interacted with the post, so toggle like/unlike
        $like_status = $result->fetch_assoc()['status'];

        if ($like_status == 'liked') {
            // If already liked, we change status to 'unliked'
            $query = "UPDATE likes SET status = 'unliked' WHERE user_id = ? AND post_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $user_id, $image_id);
            $stmt->execute();
        } else {
            // If already unliked, we change status to 'liked'
            $query = "UPDATE likes SET status = 'liked' WHERE user_id = ? AND post_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $user_id, $image_id);
            $stmt->execute();
        }
    }
}

// Check the current like status and set button text accordingly
$query = "SELECT status FROM likes WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $_SESSION['user_id'], $image_id);
$stmt->execute();
$result = $stmt->get_result();

// Set the button text based on the current like status
if ($result->num_rows > 0) {
    $like_status = $result->fetch_assoc()['status'];
    if ($like_status == 'liked') {
        $button_text = "Unlike"; // Change button text if already liked
    } else {
        $button_text = "Like"; // Change button text if already unliked
    }
} else {
    $button_text = "Like"; // Default to "Like" if the user hasn't liked this post yet
}

// Count the number of likes for this post
$query = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = ? AND status = 'liked'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $image_id);
$stmt->execute();
$result = $stmt->get_result();
$like_count = $result->fetch_assoc()['like_count'];

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content']) && !empty(trim($_POST['content']))) {
    $comment = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];
    $post_id = $image_id;

    $insertQuery = "INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iis", $user_id, $post_id, $comment);
    $stmt->execute();

    // Optional: redirect to prevent form resubmission on refresh
    header("Location: view_image.php?image_id=" . $image_id);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Image</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div id="box">
        <?php if (isset($image)): ?>
            <img src="../<?php echo htmlspecialchars($image['image_path']); ?>" 
                alt="<?php echo htmlspecialchars($image['image_name']); ?>" 
                style="width:100%; max-width:800px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <?php else: ?>
            <p>Image not found or unavailable.</p>
        <?php endif; ?>

        <!-- Like button with like count next to it -->
        <form method="post" style="display: inline-block;">
            <button type="submit" name="like" class="like-button"><?php echo $button_text; ?></button>
            <span class="like-count"><?php echo $like_count; ?> Likes</span>
        </form>

        <!-- Comment form -->
        <form method="post" id="commentForm">
            <textarea name="content" placeholder="Leave a comment..." class="comment-input"></textarea>
            <button type="submit" class="comment-submit">Submit</button>
        </form>

        <!-- Display comments -->
        <div class="comments">
            <?php
            // Fetch comments
            $query = "
                SELECT comments.content, users.username, users.profile_image
                FROM comments
                LEFT JOIN users ON comments.user_id = users.user_id
                WHERE comments.post_id = ?
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $image_id);
            $stmt->execute();
            $comments_result = $stmt->get_result();

            while ($comment = $comments_result->fetch_assoc()):
            ?>
                <div class="comment">
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> 
                    <?php echo htmlspecialchars($comment['content']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
s
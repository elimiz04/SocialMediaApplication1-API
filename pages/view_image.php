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

// Check if image_id is passed in the URL
if (isset($_GET['image_id'])) {
    $image_id = $_GET['image_id'];

    // Fetch image details by joining images and imagesdata tables
    $query = "
        SELECT images.image_name, imagesdata.filename 
        FROM images
        JOIN imagesdata ON images.image_id = imagesdata.image_id
        WHERE images.image_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the image exists
    if ($result->num_rows == 1) {
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
if (isset($_POST['like'])) {
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $image_id);
    $stmt->execute();
}

// Handle comment submission
if (isset($_POST['content']) && !isset($_POST['comment_id'])) {
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $user_id, $image_id, $content);
    $stmt->execute();
}

// Fetch comments with user data
$query = "
    SELECT images.image_name, imagesdata.image_url
    FROM images
    LEFT JOIN imagesdata ON images.image_id = imagesdata.image_id
    WHERE images.image_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $image_id);  // Bind image_id as an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $image = $result->fetch_assoc();
} else {
    echo "Image not found.";
    die;
}
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
        <?php if (isset($image) && !empty($image)): ?>
            <img src="../assets/<?php echo htmlspecialchars($image['filename']); ?>" 
                alt="<?php echo htmlspecialchars($image['image_name']); ?>" 
                style="width:100%; max-width:800px;">
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
            <?php while ($comment = $comments_result->fetch_assoc()): ?>
                <div class="comment">
                    <?php 
                    $profileImage = !empty($comment['profile_image']) ? '../' . htmlspecialchars($comment['profile_image']) : '../assets/default-profile.png'; 
                    ?>
                    <img src="<?php echo $profileImage; ?>" 
                        alt="Profile Picture" 
                        style="width:50px; height:50px; border-radius:50%; object-fit:cover;">
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong>: 
                    <?php echo htmlspecialchars($comment['content']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

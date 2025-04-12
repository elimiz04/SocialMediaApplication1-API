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

// Like handler
if (isset($_POST['like'])) {
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $image_id);
    $stmt->execute();
}

// Comment handler
if (isset($_POST['content']) && !isset($_POST['comment_id'])) {
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $user_id, $image_id, $content);
    $stmt->execute();
}

// Get comments
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Image</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: <?php echo $bg_color; ?>;
            color: <?php echo $text_color; ?>;
            margin: 0;
            padding: 0;
        }
        #box {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: <?php echo $box_bg_color; ?>;
            border-radius: 10px;
            text-align: center;
        }
        .like-button, .comment-submit {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
        }
        .comment-input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .comments {
            margin-top: 20px;
            text-align: left;
        }
        .comment {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div id="box">
        <?php if (isset($image)): ?>
            <img src="../<?php echo htmlspecialchars($image['image_path']); ?>" 
                alt="<?php echo htmlspecialchars($image['image_name']); ?>" 
                style="width:100%; max-width:800px; border-radius: 10px;">
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
                    <!--<img src="<?php echo $profileImage; ?>" 
                        alt="Profile Picture" 
                        style="width:50px; height:50px; border-radius:50%; object-fit:cover;">-->
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong>: 
                    <?php echo htmlspecialchars($comment['content']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

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

// Check if the image ID is provided in the URL
if(isset($_GET['image_id'])) {
    $image_id = $_GET['image_id'];

    // Retrieve image data from the database
    $query = "SELECT * FROM images WHERE id = ?";
    $stmt = $conn->prepare($query); // Use $con to prepare the statement
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

// Check if the user has set a color mode preference
if (!isset($_SESSION['color_mode'])) {
    // If not, set a default color mode (e.g., light mode)
    $_SESSION['color_mode'] = 'light';
}

// Function to apply the appropriate CSS class based on the color mode
function getColorModeClass() {
    return $_SESSION['color_mode'] === 'light' ? 'light-mode' : 'dark-mode';
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

while ($comment = $comments_result->fetch_assoc()) {
    $profileImage = !empty($comment['profile_image']) 
        ? '../' . htmlspecialchars($comment['profile_image']) 
        : '../assets/default-profile.png';

    echo "<div class='comment'>";
    echo "<img src='$profileImage' alt='Profile Picture' style='width:50px; height:50px; border-radius:50%; object-fit:cover;'>";
    echo "<p><strong>" . htmlspecialchars($comment['username']) . "</strong>: " . htmlspecialchars($comment['content']) . "</p>";
    echo "</div>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Image</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            background-color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#333' : '#f8f9fa'; ?>;
            color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#f8f9fa' : '#333'; ?>;
        }
        #box {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '000' : '#d7d9db'; ?>;
            color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#f8f9fa' : '#333'; ?>;
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
<body class="<?php echo getColorModeClass(); ?>">
<div id="box">
    <!-- Display image -->
    <?php if(isset($image) && !empty($image)): ?>
        <img src="../assets/<?php echo $image['filename']; ?>" alt="<?php echo $image['filename']; ?>">
    <?php else: ?>
        <p>Image not found or unavailable.</p>
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
    $stmt->bind_param("i", $image_id);
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
            echo "<a href='view_image.php?image_id=$image_id&edit_comment_id=" . $comment['comment_id'] . "' class='edit-button'>Edit</a>";
            echo "<form method='post' style='display:inline;'>";
            echo "<input type='hidden' name='delete_comment_id' value='" . $comment['comment_id'] . "'>";
            echo "<button type='submit' class='delete-button'>Delete</button>";
            echo "</form>";
            echo "</div>";
            
            // Display edit form if this comment is being edited
            if (isset($_GET['edit_comment_id']) && $_GET['edit_comment_id'] == $comment['comment_id']) {
                echo "<div class='comment-form-container'>";
                echo "<form method='post'>";
                echo "<textarea name='edit_content' class='comment-input'>" . htmlspecialchars($comment['content']) . "</textarea>";
                echo "<input type='hidden' name='comment_id' value='" . $comment['comment_id'] . "'>";
                echo "<button type='submit' class='comment-submit'>Update Comment</button>";
                echo "</form>";
                echo "</div>";
            }
        }
    }
    ?>
</div>
</div>
</body>
</html>
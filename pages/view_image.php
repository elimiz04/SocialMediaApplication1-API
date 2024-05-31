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
    $stmt = $con->prepare($query); // Use $con to prepare the statement
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
        $image = $result->fetch_assoc();
        // Update views count if needed
    } else {
        // Image not found, handle error
    }
} else {
    // Image ID not provided, handle error
}

// Handle like submission
if(isset($_POST['like'])) {
    // Insert the like into the database
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $query = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $user_id, $image_id);
    
    if($stmt->execute()) {
        // Like inserted successfully
    } else {
        // Error inserting like
        echo "Error: " . $stmt->error;
    }
}

// Handle comment submission
if(isset($_POST['content'])) {
    // Retrieve comment content from the form
    $content = $_POST['content'];
    
    // Insert the comment into the database
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $query = "INSERT INTO comments (user_id, image_id, content) VALUES (?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("iis", $user_id, $image_id, $content);
    
    if($stmt->execute()) {
        // Comment inserted successfully
    } else {
        // Error inserting comment
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Image</title>
    <style>
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
        
    </style>
</head>
<body>
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
        <!-- Fetch comments from database and display -->
    </div>
</div>

</body>
</html>

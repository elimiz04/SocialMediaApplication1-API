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
    /* Your CSS styles */
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
    <form method="post">
        <button type="submit" name="like">Like</button>
    </form>
    
    <!-- Comment form -->
    <form method="post">
        <textarea name="content" placeholder="Leave a comment..."></textarea>
        <button type="submit">Submit</button>
    </form>
    
    <!-- Display comments -->
    <div class="comments">
        <!-- Fetch comments from database and display -->
    </div>
</div>

</body>
</html>

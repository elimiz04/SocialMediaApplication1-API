<?php
// Include necessary files and start session
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

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
    $stmt = $connection->prepare($query);
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

// Handle like button click
if(isset($_POST['like'])) {
    // Update database to record like
}

// Handle comment submission
if(isset($_POST['comment'])) {
    // Insert comment into database
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Image</title>
    <!-- Include any necessary CSS -->
</head>
<body>
    <div id="box">
        <h2>Hello, <?php echo $_SESSION['username']; ?></h2>
        <!-- Display image -->
        <img src="../assets/<?php echo $image['filename']; ?>" alt="<?php echo $image['filename']; ?>">
        
        <!-- Like button -->
        <form method="post">
            <button type="submit" name="like">Like</button>
        </form>
        
        <!-- Comment form -->
        <form method="post">
            <textarea name="comment" placeholder="Leave a comment..."></textarea>
            <button type="submit">Submit</button>
        </form>
        
        <!-- Display comments -->
        <div class="comments">
            <!-- Fetch comments from database and display -->
        </div>
    </div>
</body>
</html>

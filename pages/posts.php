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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content'])) {
    // Process the form data and add the new post to the database

    // Get user ID
    $user_id = $_SESSION['user_id'];

    // Get post content
    $content = $_POST['content'];

    // Check if an image was uploaded
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Handle file upload
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_type = $_FILES['image']['type'];

        // Get file extension
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // Check if the uploaded file is an image
        if (!in_array($image_extension, array('jpg', 'jpeg', 'png', 'gif'))) {
            echo "Only JPG, JPEG, PNG, GIF files are allowed.";
            exit;
        }

        // Move uploaded file to the uploads directory
        $upload_dir = "../assets/";
        $target_file = $upload_dir . uniqid('post_image_') . '.' . $image_extension;

        if (move_uploaded_file($image_tmp, $target_file)) {
            $image = basename($target_file);
        } else {
            echo "Error uploading file.";
            exit;
        }
    }

    // Insert new post into database
    $query = "INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $content, $image);
    $stmt->execute();

    // Redirect back to profile page
    header("Location: ../pages/profile.php");
    exit;
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Post</title>
    <style>
        /* CSS Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
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
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }
        .minimal-btn:hover {
            background-color: #337ab7;
            color: white;
            border-color: #337ab7;
        }
    </style>
</head>
<body class="<?php echo getColorModeClass(); ?>">

    <div id="box">
        <h1>Add Post</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <textarea name="content" placeholder="Write a new post..."></textarea>
            <input type="file" name="image">
            <button type="submit">Post</button>
        </form>
    </div>
</body>
</html>

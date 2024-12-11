<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php"); // Ensure this is included

// Ensure color_scheme is set in the session
if (!isset($_SESSION['color_scheme'])) {
    $_SESSION['color_scheme'] = 'light'; // Default to light mode
}

// Retrieve color scheme from the database if the user is logged in
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
        $_SESSION['color_scheme'] = $db_color_scheme; // Update session with the user's preference
    }
    $stmt->close();
}
$color_scheme = $_SESSION['color_scheme']; // Safe to use now
$bg_color = $color_scheme === 'dark' ? '#333' : '#f8f9fa';
$text_color = $color_scheme === 'dark' ? '#f8f9fa' : '#333';
$box_bg_color = $color_scheme === 'dark' ? '#000' : '#d7d9db';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <?php include "../includes/header.php"; ?> 
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
        h1, h2 {
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
            line-height: 1.6;
        }
        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .image-container a {
            width: calc(33.33% - 20px);
            margin: 10px;
            text-decoration: none;
        }
        .image-container img {
            max-width: 100%;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div id="box">
        <?php 
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../pages/login.php");
            die;
        }

        $user_id = $_SESSION['user_id'];
        $user_data = get_user_data($conn, $user_id); // Fetch user data using the function

        if (isset($user_data['username'])): ?>
            <h2>Hello, <?php echo htmlspecialchars($user_data['username']); ?></h2>
        <?php endif; ?>

        <div class="image-container">
            <a href="view_image.php?image_id=1"><img src="../assets/image1.jpg" alt="Image 1"></a>
            <a href="view_image.php?image_id=2"><img src="../assets/image2.jpg" alt="Image 2"></a>
            <a href="view_image.php?image_id=3"><img src="../assets/image3.jpg" alt="Image 3"></a>
            <!-- Add more images as needed -->
        </div>
    </div>
</body>
</html>

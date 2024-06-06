<?php
session_start();
include("../includes/connection.php");

// Check if the user has set a color mode preference
if (!isset($_SESSION['color_mode'])) {
    // If not, set a default color mode (e.g., light mode)
    $_SESSION['color_mode'] = 'light';
}

// Function to apply the appropriate CSS class based on the color mode
function getColorModeClass() {
    return $_SESSION['color_mode'] === 'light' ? 'light-mode' : 'dark-mode';
}

// Retrieve the user's color scheme setting from the database
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT color_scheme FROM user_settings WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($color_scheme);
        $stmt->fetch();
    } else {
        // Default color scheme if not found in the database
        $color_scheme = 'light';
    }

    $stmt->close();
} else {
    // Default color scheme if user is not logged in
    $color_scheme = 'light';
}
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT color_scheme FROM user_settings WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($color_scheme);
        $stmt->fetch();
    } else {
        // Default color scheme if not found in the database
        $color_scheme = 'light';
    }

    $stmt->close();
} else {
    // Default color scheme if user is not logged in
    $color_scheme = 'light';
}
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
            <?php if ($color_scheme === 'dark'): ?>
                background-color: #333;
                color: #f8f9fa;
            <?php else: ?>
                background-color: #f8f9fa;
                color: #333;
            <?php endif; ?>
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
            width: calc(33.33% - 20px); /* 20px padding between images */
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
            max-width: 100%; /* Make images fill their containers */
            max-height: 100%; /* Make images fill their containers */
            height: auto; /* Ensure images maintain their aspect ratio */
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Add a subtle shadow effect */
        }
        /* Light Mode Styles */
        .light-mode {
            --text-color: #333;
            --bg-color: #f8f9fa;
        }

        /* Dark Mode Styles */
        .dark-mode {
            --text-color: #f8f9fa;
            --bg-color: #333;
        }

        /* Apply color variables to body */
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        <?php if ($color_scheme === 'dark'): ?>
                background-color: #333;
                color: #f8f9fa;
            <?php else: ?>
                background-color: #f8f9fa;
                color: #333;
            <?php endif; ?>
    </style>
</head>
<body>
<body class="<?php echo $_SESSION['color_scheme']; ?>">

    <div id="box">
    <?php 
            include("../includes/functions.php");

            if(!isset($_SESSION['user_id'])){
                header("Location: ../pages/login.php");
                die;
            }

            $user_id = $_SESSION['user_id'];
            $user_data = array(); 

            if(isset($user_data['username'])): ?>
                <h2>Hello, <?php echo $user_data['username']; ?></h2>
            <?php endif; ?>

            <div class="image-container">
                <a href="view_image.php?image_id=1">
                    <img src="../assets/image1.jpg" alt="Image 1">
                </a>
                <a href="view_image.php?image_id=2">
                    <img src="../assets/image2.jpg" alt="Image 2">
                </a>
                <a href="view_image.php?image_id=3">
                    <img src="../assets/image3.jpg" alt="Image 3">
                </a>
                <a href="view_image.php?image_id=4">
                    <img src="../assets/image4.jpg" alt="Image 4">
                </a>
                <a href="view_image.php?image_id=5">
                    <img src="../assets/image5.jpg" alt="Image 5">
                </a>
                <a href="view_image.php?image_id=6">
                    <img src="../assets/image6.jpg" alt="Image 6">
                </a>
                <a href="view_image.php?image_id=7">
                    <img src="../assets/image7.jpg" alt="Image 7">
                </a>
                <a href="view_image.php?image_id=8">
                    <img src="../assets/image8.jpg" alt="Image 8">
                </a>
                <a href="view_image.php?image_id=9">
                    <img src="../assets/image9.jpg" alt="Image 9">
                </a>
            </div>
    </div>
</body>
</html>

<?php
session_start();

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
    <title>Home</title>
    <?php include ("../includes/header.php"); ?>
    <link rel="stylesheet" href="../assets/style.css">    
</head>
<body class="<?php echo getColorModeClass(); ?>">
    <div id="box">
        <?php 
            include('../includes/connection.php');
            include("../includes/functions.php");

            if(!isset($_SESSION['user_id'])){
                header("Location: ../pages/login.php");
                die;
            }

            $user_id = $_SESSION['user_id'];
            $user_data = array(); // Initialize an empty array

            // Retrieve the username from the session and store it in $user_data
            if(isset($_SESSION['username'])){
                $user_data['username'] = $_SESSION['username'];
            }

            if(isset($user_data['username'])): ?>
                <h2>Hello, <?php echo $user_data['username']; ?></h2>
            <?php endif; ?>
            <h1>Welcome to SocialHive</h1>
            <p>This is a social media platform where you can connect with friends, share your thoughts, and stay updated with what's happening in your community.</p>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
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
            text-align: center;
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
            max-width: 100%; 
            max-height: 100%; 
            height: auto; 
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); 
            width: 100%; 
            height: 100%; 
        }

        /* Style for minimalistic buttons */
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
<body>
    
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

// Retrieve user's profile information from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} 
?>

<div id="box">
    <h1>Welcome, <?php echo isset($user['username']) ? $user['username'] : 'User'; ?></h1>
    <div class="btn-container">
        <a href="posts.php" class="minimal-btn"> Add Post</a>
        <a href="followers.php" class="minimal-btn">Followers</a>
        <a href="following.php" class="minimal-btn">Following</a>
        <a href="messages.php" class="minimal-btn">Messages</a>
        <a href="groups.php" class="minimal-btn">Groups</a>
        <a href="settings.php" class="minimal-btn">Settings</a>
    </div>
    <br>
    <br>
    <div class="image-container">
        <a href="view_image.php?image_id=10">
            <img src="../assets/image10.jpg" alt="Image 10">
        </a>
        <a href="view_image.php?image_id=11">
            <img src="../assets/image11.jpg" alt="Image 11">
        </a>
        <a href="view_image.php?image_id=12">
            <img src="../assets/image12.jpg" alt="Image 12">
        </a>
    </div>
</div>

</body>
</html>

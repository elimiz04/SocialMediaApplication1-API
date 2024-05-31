<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <?php include "../includes/header.php"; ?> 
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
            width: calc(33.33% - 20px); /* 20px padding between images */
            margin: 10px;
            text-align: center;
            text-decoration: none; /* Remove default link styles */
            color: inherit; /* Inherit text color from parent */
            height: 200px; /* Set a fixed height for the image containers */
            display: flex; /* Use flexbox to vertically center images */
            justify-content: center; /* Center images horizontally */
            align-items: center; /* Center images vertically */
            overflow: hidden; /* Hide overflowing parts of images */
        }
        .image-container img {
            max-width: 100%; /* Make images fill their containers */
            max-height: 100%; /* Make images fill their containers */
            height: auto; /* Ensure images maintain their aspect ratio */
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Add a subtle shadow effect */
        }
    </style>
</head>
<body>
    <div id="box">
        <?php 
            session_start();
            include("../includes/connection.php");
            include("../includes/functions.php");

            if(!isset($_SESSION['user_id'])){
                header("Location: ../login/login.php");
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

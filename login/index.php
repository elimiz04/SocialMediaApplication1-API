<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <?php include "header.php"; ?>
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
    </style>
</head>
<body>
    <div id="box">
        <?php 
            session_start();
            include("connection.php");
            include("functions.php");

            if(!isset($_SESSION['user_id'])){
                header("Location: login.php");
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
            <br>
            <h1>Welcome to SocialHive</h1>
            <p>This is a social media platform where you can connect with friends, share your thoughts, and stay updated with what's happening in your community.</p>
            <br>
    </div>
</body>
</html>

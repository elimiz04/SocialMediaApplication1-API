<?php
session_start();
include("../includes/connection.php");

// Fetch a list of users except the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT user_id, username FROM users WHERE user_id <> ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$users_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Follow Users</title>
    <?php include "../includes/header.php"; ?> 
    <style>
        /* CSS styles from the second page */
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
        }
        .light-mode {
            --text-color: #333;
            --bg-color: #f8f9fa;
        }

        .dark-mode {
            --text-color: #f8f9fa;
            --bg-color: #333;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
        }

    </style>
</head>
<body>
<body class="<?php echo $_SESSION['color_scheme']; ?>">
    <div id="box">
    <h2>Follow Users</h2>
    <?php
    if ($users_result->num_rows > 0) {
        while ($user_row = $users_result->fetch_assoc()) {
            $user_id_to_follow = $user_row['user_id'];
            $username_to_follow = $user_row['username'];

            // Check if the logged-in user is already following this user
            $is_following_query = "SELECT * FROM Follows WHERE follower_id = ? AND followed_id = ?";
            $stmt_is_following = $conn->prepare($is_following_query);
            $stmt_is_following->bind_param("ii", $user_id, $user_id_to_follow);
            $stmt_is_following->execute();
            $is_following_result = $stmt_is_following->get_result();
            $is_following = $is_following_result->num_rows > 0;

            // Display the username and follow button
            echo "<p>$username_to_follow ";
            if ($is_following) {
                echo "<a href='../pages/follow_handler.php?action=unfollow&target_user_id=$user_id_to_follow'>Unfollow</a>";
            } else {
                echo "<a href='../pages/follow_handler.php?action=follow&target_user_id=$user_id_to_follow'>Follow</a>";
            }
            echo "</p>";
        }
    } else {
        echo "<p>No users found.</p>";
    }
    ?>
    </div>
</body>
</html>

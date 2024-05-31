<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <?php include "../includes/header.php"; ?> 
</head>
<body>
    <h1>User Profile</h1>
    <?php
    session_start();
    include '../includes/connection.php'; 

    // Retrieve user information from the database
    if(isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id']; 
        $query = "SELECT * FROM users WHERE id = $userId";
        
        // Check if the connection is valid before querying
        if ($conn) {
            $result = mysqli_query($conn, $query);

            // Display user information
            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                echo "<p>Username: " . $user['username'] . "</p>";
                echo "<p>Bio: " . $user['bio'] . "</p>";
            } else {
                echo "User not found";
            }
        } else {
            echo "Database connection failed";
        }
    } else {
        echo "User not logged in";
    }
    ?>
    <ul>
        <li><a href="posts.php">Posts</a></li>
        <li><a href="followers.php">Followers</a></li>
        <li><a href="following.php">Following</a></li>
        <li><a href="messages.php">Messages</a></li>
        <li><a href="groups.php">Groups</a></li>
        <li><a href="settings.php">Settings</a></li>
    </ul>
</body>
</html>

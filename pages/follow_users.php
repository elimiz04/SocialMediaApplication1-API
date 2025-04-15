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
    <link rel="stylesheet" href="../assets/style.css">    
</head>
<body class="<?php echo $_SESSION['color_scheme']; ?>">
    <div id="box">
        <h1>Follow Users</h1>
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
                echo "<div class='user-item'>";
                echo "<span class='username'>$username_to_follow</span>";

                // Display Follow/Unfollow buttons
                if ($is_following) {
                    echo "<form method='post' action='../pages/follow_handler.php' class='follow-form'>
                            <input type='hidden' name='action' value='unfollow'>
                            <input type='hidden' name='target_user_id' value='$user_id_to_follow'>
                            <button type='submit' class='unfollow-btn'>Unfollow</button>
                          </form>";
                } else {
                    echo "<form method='post' action='../pages/follow_handler.php' class='follow-form'>
                            <input type='hidden' name='action' value='follow'>
                            <input type='hidden' name='target_user_id' value='$user_id_to_follow'>
                            <button type='submit' class='follow-btn'>Follow</button>
                          </form>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No users found.</p>";
        }
        ?>
    </div>
</body>
</html>

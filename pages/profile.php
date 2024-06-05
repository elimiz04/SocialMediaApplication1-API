<?php
ob_start(); // Start output buffering
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
$user_result = $stmt->get_result();

if($user_result->num_rows == 1) {
    $user = $user_result->fetch_assoc();
}

// Fetch unread message count for the logged-in user
$unread_count_query = "SELECT COUNT(*) AS unread_count FROM Messages WHERE receiver_id = ? AND is_read = 0";
$stmt_unread_count = $conn->prepare($unread_count_query);
$stmt_unread_count->bind_param("i", $user_id);
$stmt_unread_count->execute();
$unread_result = $stmt_unread_count->get_result();
$unread_count = 0;
if ($unread_result && $unread_row = $unread_result->fetch_assoc()) {
    $unread_count = $unread_row['unread_count'];
}

// Count the number of followers
$count_followers_query = "SELECT COUNT(*) AS follower_count FROM Follows WHERE followed_id = ?";
$stmt_followers_count = $conn->prepare($count_followers_query);
$stmt_followers_count->bind_param("i", $user_id);
$stmt_followers_count->execute();
$followers_count_result = $stmt_followers_count->get_result();
$followers_count = 0;
if ($followers_count_result && $followers_count_row = $followers_count_result->fetch_assoc()) {
    $followers_count = $followers_count_row['follower_count'];
}

// Count the number of users the user is following
$count_following_query = "SELECT COUNT(*) AS following_count FROM Follows WHERE follower_id = ?";
$stmt_following_count = $conn->prepare($count_following_query);
$stmt_following_count->bind_param("i", $user_id);
$stmt_following_count->execute();
$following_count_result = $stmt_following_count->get_result();
$following_count = 0;
if ($following_count_result && $following_count_row = $following_count_result->fetch_assoc()) {
    $following_count = $following_count_row['following_count'];
}

// Display follower and following counts
echo "<p>Followers: $followers_count</p>";
echo "<p>Following: $following_count</p>";

// Check if the user has set a color mode preference
if (!isset($_SESSION['color_mode'])) {
    // If not, set a default color mode (e.g., light mode)
    $_SESSION['color_mode'] = 'light';
}

// Function to apply the appropriate CSS class based on the color mode
function getColorModeClass() {
    return $_SESSION['color_mode'] === 'light' ? 'light-mode' : 'dark-mode';
}

// Fetch the user's followers
$follower_query = "SELECT u.user_id, u.username FROM Follows f JOIN users u ON f.follower_id = u.user_id WHERE f.followed_id = ?";
$stmt_follower = $conn->prepare($follower_query);
$stmt_follower->bind_param("i", $user_id);
$stmt_follower->execute();
$follower_result = $stmt_follower->get_result();

// Handle follow/unfollow actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $target_user_id = $_POST['target_user_id'];

    if ($action == 'follow') {
        $follow_query = "INSERT INTO Follows (follower_id, followed_id) VALUES (?, ?)";
        $stmt_follow = $conn->prepare($follow_query);
        $stmt_follow->bind_param("ii", $user_id, $target_user_id);
        $stmt_follow->execute();
    } elseif ($action == 'unfollow') {
        $unfollow_query = "DELETE FROM Follows WHERE follower_id = ? AND followed_id = ?";
        $stmt_unfollow = $conn->prepare($unfollow_query);
        $stmt_unfollow->bind_param("ii", $user_id, $target_user_id);
        $stmt_unfollow->execute();
    }

    // Redirect to avoid form resubmission
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
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
        p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            text-align: justify;
        }
        /* Box Styles */
        #box {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        /* Button Styles */
        .btn-container {
            margin-top: 20px;
            text-align: center;
        }
        .minimal-btn, .message-btn {
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
        .minimal-btn:hover, .message-btn:hover {
            background-color: #337ab7;
            color: white;
            border-color: #337ab7;
        }
        /* Image Styles */
        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
        }

        .image-container .post {
            width: calc(33.33% - 20px);
            margin: 10px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative; /* Add position relative for absolute positioning */
        }

        .image-container .post img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensure the image covers the entire container */
            border-radius: 10px;
        }

        .image-container .post-content {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.8); /* Add a semi-transparent background */
            padding: 10px;
        }

        .image-container .delete-form {
            display: inline-block;
        }

        .image-container .delete-btn {
            padding: 5px 10px;
            background-color: #dc3545; /* Change the background color to red */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .image-container .delete-btn:hover {
            background-color: #c82333; /* Darken the background color on hover */
        }

        /* Post Styles */
        .post-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
        }
        .post {
            width: calc(33.33% - 20px);
            margin: 10px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .post img {
            width: 100%;
            height: auto;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .post-content {
            padding: 10px;
        }
        .notification-badge {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 12px;
            position: absolute;
            top: -10px;
            right: -10px;
        }

        .message-button-container {
            position: relative;
            display: inline-block;
        }

        /* Follower Styles */
        .follower-container {
            margin-top: 20px;
        }
        .follower {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .follower img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
</head>
<body class="<?php echo getColorModeClass(); ?>">

<div id="box">
        <h1>Welcome, <?php echo isset($user['username']) ? $user['username'] : 'User'; ?></h1>
        <div class="btn-container">
            <a href="posts.php" class="minimal-btn">Add Post</a>
            <div class="message-button-container">
                <a href="messages.php" class="message-btn">Messages <?php if ($unread_count > 0) { echo '<span class="notification-badge">' . $unread_count . '</span>'; } ?></a>
            </div>
            <a href="settings.php" class="minimal-btn">Settings</a>

            <a href="../pages/follow_users.php" class="minimal-btn">Follow</a>
            <?php
           
            // Check if the displayed user is not the logged-in user
            if ($follower_result && $follower_result->num_rows > 0) {
                while ($follower = $follower_result->fetch_assoc()) {
                    // Check if the logged-in user is following the displayed profile
                    $is_following_query = "SELECT * FROM Follows WHERE follower_id = ? AND followed_id = ?";
                    $stmt_is_following = $conn->prepare($is_following_query);
                    $stmt_is_following->bind_param("ii", $user_id, $follower['user_id']);
                    $stmt_is_following->execute();
                    $is_following_result = $stmt_is_following->get_result();
                    $is_following = $is_following_result->num_rows > 0;
            
                    // Display follow/unfollow button based on the follow status
                    if ($is_following) {
                        echo '<a href="../pages/follow_handler.php?action=unfollow&target_user_id=' . $follower['user_id'] . '" class="minimal-btn">Unfollow</a>';
                    } else {
                        echo '<a href="../pages/follow_handler.php?action=follow&target_user_id=' . $follower['user_id'] . '" class="minimal-btn">Follow</a>';
                    }
                }
            } else {
                echo "<p>No followers yet.</p>";
            }
            
           

            if ($follower_result && $follower_result->num_rows > 0) {
                while ($follower = $follower_result->fetch_assoc()) {
                    // Check if the logged-in user is following the displayed profile
                    $is_following_query = "SELECT * FROM Follows WHERE follower_id = ? AND followed_id = ?";
                    $stmt_is_following = $conn->prepare($is_following_query);
                    $stmt_is_following->bind_param("ii", $user_id, $follower['user_id']);
                    $stmt_is_following->execute();
                    $is_following_result = $stmt_is_following->get_result();
                    $is_following = $is_following_result->num_rows > 0;
            
                    // Display follow/unfollow button based on the follow status
                    if ($is_following) {
                        echo '<a href="../pages/follow_handler.php?action=unfollow&target_user_id=' . $follower['user_id'] . '" class="minimal-btn">Unfollow</a>';
                    } else {
                        echo '<a href="../pages/follow_handler.php?action=follow&target_user_id=' . $follower['user_id'] . '" class="minimal-btn">Follow</a>';
                    }
                }
            }
            
            ?>


        </div>
        <br><br>

        <!-- Display follow/unfollow buttons -->
        <div class="follower-container">
            <?php if ($follower_result && $follower_result->num_rows > 0): ?>
                <h2>Your Followers:</h2>
                <?php while($follower = $follower_result->fetch_assoc()): ?>
                    <div class="follower">
                        <img src="../assets/<?php echo $follower['profile_pic']; ?>" alt="Follower Profile Pic">
                        <p><?php echo $follower['username']; ?></p>
                        <?php
                        // Check if the user is already following this follower
                        $follow_check_query = "SELECT * FROM Follows WHERE follower_id = ? AND followed_id = ?";
                        $stmt_follow_check = $conn->prepare($follow_check_query);
                        $stmt_follow_check->bind_param("ii", $user_id, $follower['user_id']);
                        $stmt_follow_check->execute();
                        $follow_check_result = $stmt_follow_check->get_result();
                        $is_following = $follow_check_result->num_rows > 0;
                        ?>
                        <?php if ($is_following): ?>
                            <!-- Display unfollow button -->
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="action" value="unfollow">
                                <input type="hidden" name="target_user_id" value="<?php echo $follower['user_id']; ?>">
                                <button type="submit" class="minimal-btn">Unfollow</button>
                            </form>
                        <?php else: ?>
                            <!-- Display follow button -->
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="action" value="follow">
                                <input type="hidden" name="target_user_id" value="<?php echo $follower['user_id']; ?>">
                                <button type="submit" class="minimal-btn">Follow</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <!-- Display follow/unfollow buttons -->
<div class="follower-container">
    <?php if ($follower_result && $follower_result->num_rows > 0): ?>
        <h2>Your Followers:</h2>
        <?php while($follower = $follower_result->fetch_assoc()): ?>
            <div class="follower">
                <img src="../assets/<?php echo $follower['profile_pic']; ?>" alt="Follower Profile Pic">
                <p><?php echo $follower['username']; ?></p>
                
                <?php
                // Check if the user is already following this follower
                $follow_check_query = "SELECT * FROM Follows WHERE follower_id = ? AND followed_id = ?";
                $stmt_follow_check = $conn->prepare($follow_check_query);
                $stmt_follow_check->bind_param("ii", $user_id, $follower['user_id']);
                $stmt_follow_check->execute();
                $follow_check_result = $stmt_follow_check->get_result();
                $is_following = $follow_check_result->num_rows > 0;
                ?>
                
                <!-- Display appropriate follow/unfollow button based on the follow status -->
                <?php if ($is_following): ?>
                    <!-- Display unfollow button -->
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="action" value="unfollow">
                        <input type="hidden" name="target_user_id" value="<?php echo $follower['user_id']; ?>">
                        <button type="submit" class="minimal-btn">Unfollow</button>
                    </form>
                <?php else: ?>
                    <!-- Display follow button -->
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="action" value="follow">
                        <input type="hidden" name="target_user_id" value="<?php echo $follower['user_id']; ?>">
                        <button type="submit" class="minimal-btn">Follow</button>
                    </form>
                <?php endif; ?>
                
                </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <?php
            // Handle follow/unfollow actions
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $action = $_POST['action'];
                $target_user_id = $_POST['target_user_id'];

                if ($action == 'follow') {
                    $follow_query = "INSERT INTO Follows (follower_id, followed_id) VALUES (?, ?)";
                    $stmt_follow = $conn->prepare($follow_query);
                    $stmt_follow->bind_param("ii", $user_id, $target_user_id);
                    $stmt_follow->execute();
                } elseif ($action == 'unfollow') {
                    $unfollow_query = "DELETE FROM Follows WHERE follower_id = ? AND followed_id = ?";
                    $stmt_unfollow = $conn->prepare($unfollow_query);
                    $stmt_unfollow->bind_param("ii", $user_id, $target_user_id);
                    $stmt_unfollow->execute();
                }

                // Redirect to avoid form resubmission
                header("Location: profile.php");
                exit;
            }
            ?>

                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <?php
        // Handle post deletion
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) {
            $post_id = $_POST['post_id'];

            // Delete the post and associated content from the database
            $query_delete_post = "DELETE FROM posts WHERE post_id = ?";
            $stmt_delete_post = $conn->prepare($query_delete_post);
            $stmt_delete_post->bind_param("i", $post_id);
            $stmt_delete_post->execute();

            // Redirect back to the profile page after deletion
            header("Location: ../pages/profile.php");
            exit;
        }
        ?>
    </div>
</body>
</html>

<?php
ob_end_flush(); // End output buffering and flush buffer contents
?>


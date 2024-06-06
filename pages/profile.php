<?php
ob_start();
session_start();
include("../includes/connection.php"); 
include("../includes/functions.php");
include("../includes/header.php");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION['user_id'])){
    header("Location: ../pages/login.php");
    die;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if($user_result->num_rows == 1) {
    $user = $user_result->fetch_assoc();
}

$unread_count_query = "SELECT COUNT(*) AS unread_count FROM Messages WHERE receiver_id = ? AND is_read = 0";
$stmt_unread_count = $conn->prepare($unread_count_query);
$stmt_unread_count->bind_param("i", $user_id);
$stmt_unread_count->execute();
$unread_result = $stmt_unread_count->get_result();
$unread_count = 0;
if ($unread_result && $unread_row = $unread_result->fetch_assoc()) {
    $unread_count = $unread_row['unread_count'];
}

$count_followers_query = "SELECT COUNT(*) AS follower_count FROM Follows WHERE followed_id = ?";
$stmt_followers_count = $conn->prepare($count_followers_query);
$stmt_followers_count->bind_param("i", $user_id);
$stmt_followers_count->execute();
$followers_count_result = $stmt_followers_count->get_result();
$followers_count = 0;
if ($followers_count_result && $followers_count_row = $followers_count_result->fetch_assoc()) {
    $followers_count = $followers_count_row['follower_count'];
}

$count_following_query = "SELECT COUNT(*) AS following_count FROM Follows WHERE follower_id = ?";
$stmt_following_count = $conn->prepare($count_following_query);
$stmt_following_count->bind_param("i", $user_id);
$stmt_following_count->execute();
$following_count_result = $stmt_following_count->get_result();
$following_count = 0;
if ($following_count_result && $following_count_row = $following_count_result->fetch_assoc()) {
    $following_count = $following_count_row['following_count'];
}

if (!isset($_SESSION['color_mode'])) {
    $_SESSION['color_mode'] = 'light';
}

function getColorModeClass() {
    return $_SESSION['color_mode'] === 'light' ? 'light-mode' : 'dark-mode';
}

// Handle post deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $image = $_POST['image'];

    // Delete the post from the database
    $query_delete_post = "DELETE FROM posts WHERE post_id = ?";
    $stmt_delete_post = $conn->prepare($query_delete_post);
    $stmt_delete_post->bind_param("i", $post_id);
    $stmt_delete_post->execute();

    // Check for errors
    if ($stmt_delete_post->error) {
        echo "Error deleting post: " . $stmt_delete_post->error;
        // You might want to handle this error gracefully, depending on your application's requirements
    }

    // Delete the image file from the server
    $imagePath = "../assets/" . $image;
    if (file_exists($imagePath)) {
        if (!unlink($imagePath)) {
            echo "Error deleting image file: " . $imagePath;
            // You might want to handle this error gracefully, depending on your application's requirements
        }
    }

    // Redirect back to the profile page after deletion
    header("Location: ../pages/profile.php");
    exit;
}


    $query_posts = "SELECT * FROM posts WHERE user_id = ? ORDER BY post_id DESC";
    $stmt_posts = $conn->prepare($query_posts);
    $stmt_posts->bind_param("i", $user_id);
    $stmt_posts->execute();
    $posts_result = $stmt_posts->get_result();

    // Function to get all image files from a directory
    function getImageFiles($directory) {
        $files = glob($directory . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        return $files;
    }

    $imageDirectory = "../assets";
    $imageFiles = getImageFiles($imageDirectory);

    // Handle post deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $image = $_POST['image'];

    // Delete the post from the database
    $query_delete_post = "DELETE FROM posts WHERE post_id = ?";
    $stmt_delete_post = $conn->prepare($query_delete_post);
    $stmt_delete_post->bind_param("i", $post_id);
    $stmt_delete_post->execute();

    // Delete the image file from the server
    $imagePath = "../assets/" . $image;
    if (file_exists($imagePath)) {
        unlink($imagePath); 
    }

    // Redirect back to the profile page after deletion
    header("Location: ../pages/profile.php");
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
            background-color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#333' : '#f8f9fa'; ?>;
            color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#f8f9fa' : '#333'; ?>;
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
            background-color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '000' : '#d7d9db'; ?>;
            color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#f8f9fa' : '#333'; ?>;
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

        /* Post Styles */
        .post {
            width: calc(33.33% - 20px);
            margin: 10px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative; 
        }

        .delete-form {
            position: absolute;
            bottom: 0PX; 
            left: 50%; 
            transform: translateX(-50%);
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


        .delete-form {
            display: flex;
            justify-content: center;
        }
        .delete-btn {
            margin-top: 10px;
        }
        .notification-badge {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 12
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
            margin-bottom: 10px;
        }

        .count-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
    </style>
</head>

        <div id="box">
        <h1>Welcome, <?php echo isset($user['username']) ? $user['username'] : 'User'; ?></h1>
        <!--Display follower and following counts regardless of whether there are followers or not-->
            <!-- Container for follower and following counts -->
            <div class="count-container">
                <p>Followers: <?php echo $followers_count; ?></p>
                <p>Following: <?php echo $following_count; ?></p>
            </div>
        <div class="btn-container">
            <a href="posts.php" class="minimal-btn">Add Post</a>
            <a href="messages.php" class="message-btn">Messages <?php if ($unread_count > 0) { echo '<span class="notification-badge">' . $unread_count . '</span>'; } ?></a>
            <a href="settings.php" class="minimal-btn">Settings</a>
            <a href="../pages/follow_users.php" class="minimal-btn">Follow</a>
        </div>
        <br><br>
       <!-- Display follow/unfollow buttons -->
<div class="follower-container">
    <?php if (isset($follower_result) && $follower_result && $follower_result->num_rows > 0): ?>
        <h2>Your Followers:</h2>
        <?php while($follower = $follower_result->fetch_assoc()): ?>
            <style>
                body{
                    background-color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#333' : '#f8f9fa'; ?>;
                    color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#f8f9fa' : '#333'; ?>;
                }
            </style>
            <div class="follower">
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
        <?php endwhile; ?>
    <?php endif; ?>
</div>


        <?php
        // Retrieve user's posts from the database
        $query_posts = "SELECT * FROM posts WHERE user_id = ?";
        $stmt_posts = $conn->prepare($query_posts);
        $stmt_posts->bind_param("i", $user_id);
        $stmt_posts->execute();
        $result = $stmt_posts->get_result();
        ?>
        <div class="post-container">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $count = 0; ?>
                <?php while($post = $result->fetch_assoc()): ?>
                    <?php if ($count % 3 == 0): ?>
                        <div class="row">
                    <?php endif; ?>
                    <div class="post">
                        <a href="post_handler.php?post_id=<?php echo $post['post_id']; ?>">
                            <img src="../assets/<?php echo $post['image']; ?>" alt="Post Image">
                        </a>
                        <form method="post" class="delete-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                            <input type="hidden" name="image" value="<?php echo $post['image']; ?>">
                            <button type="submit" class="minimal-btn delete-btn">Delete</button>
                        </form>

                    </div>
                    <?php $count++; ?>
                    <?php if ($count % 3 == 0 || $count == $result->num_rows): ?>
                        </div> <!-- Close row -->
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No posts found.</p>
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

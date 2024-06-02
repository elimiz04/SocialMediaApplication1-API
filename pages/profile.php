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
$user_result = $stmt->get_result();

if($user_result->num_rows == 1) {
    $user = $user_result->fetch_assoc();
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <style>
        
        /* Global Styles */
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
        /* Image Styles */
        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
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
    </style>
</head>
<body>
    <?php include("../includes/functions.php");?>
    <?php include("../includes/connection.php");?>

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
    

    <?php
    // Retrieve user's posts from the database
    $query_posts = "SELECT * FROM posts WHERE user_id = ?";
    $stmt_posts = $conn->prepare($query_posts);
    $stmt_posts->bind_param("i", $user_id);
    $stmt_posts->execute();
    $result = $stmt_posts->get_result();
    ?>

    <div class="image-container">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($post = $result->fetch_assoc()): ?>
                    <a href="post_handler.php?post_id=<?php echo $post['post_id']; ?>">
                        <img src="../assets/<?php echo $post['image']; ?>" alt="Post Image">
                    </a>
                <?php endwhile; ?>
                <?php else: ?>
                <p>No posts found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
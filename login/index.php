<?php
session_start();

include("connection.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    die;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";
$result = mysqli_query($con, $query);
$user_data = mysqli_fetch_assoc($result);

// Fetch posts
$posts_query = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id ORDER BY posts.created_at DESC";
$posts_result = mysqli_query($con, $posts_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <style type="text/css">
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
        }
        #text {
            height: 25px;
            border-radius: 5px;
            padding: 4px;
            border: solid thin #aaa;
            width: 100%;
        }
        #button {
            padding: 12px;
            width: 120px;
            font-size: 16px;
            color: white;
            background-color: #337ab7;
            border: none;
            transition: background-color 0.3s ease;
        }
        #button:hover {
            background-color: #286090;
        }
        #box {
            background-color: grey;
            width: 400px;
            padding: 40px;
            text-align: center;
        }
        #signup-link {
            font-size: 16px;
            color: white;
            text-align: center;
            display: block;
            margin-top: 20px;
        }
        .form-label {
            font-size: 16px;
            text-align: left;
            font-weight: bold;
            margin-bottom: 10px;
            color: white;
            display: block;
        }
        .post {
            background-color: white;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div id="box">
        <a href="logout.php">Logout</a>
        <h1>Home</h1>
        <br>
        Hello, <?php echo $user_data['username']; ?>

        <form method="post" action="create_post.php">
            <textarea name="content" placeholder="What's on your mind?" style="width: 100%; height: 100px; border-radius: 5px; padding: 10px; margin: 10px 0;"></textarea>
            <input id="button" type="submit" value="Post">
        </form>
        <h2>Posts</h2>
        <?php
        if(mysqli_num_rows($posts_result) > 0){
            while ($post = mysqli_fetch_assoc($posts_result)){
                echo "<div class='post'><b>". $post['user_name']. ":</b><br>". $post['content']. "<br><small>".$post['created_at']. "</small></div>";
            }
        } else {
            echo "<div class='post'>No posts available</div>";
        }
        ?>
    </div>
</body>
</html>

<?php
session_start();

include("connection.php");
include("functions.php");

include "header.php";


if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    die;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";
$result = mysqli_query($con, $query);
$user_data = mysqli_fetch_assoc($result);

// Fetch posts
$query = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id ORDER BY posts.created_at DESC";
$posts_result = mysqli_query($con, $query); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <div id="box">
        <h1>Home</h1>
        <br>
        Hello, <?php echo $user_data['username']; ?>

        <form method="post" action="../post/create_post.php">
            <textarea name="content" placeholder="What's on your mind?" style="width: 100%; height: 100px; border-radius: 5px; padding: 10px; margin: 10px 0;"></textarea>
            <input id="button" type="submit" value="Post">
        </form>
        <h2>Posts</h2>
        <?php
            while ($post = mysqli_fetch_assoc($posts_result)) {
                echo "<div class='post'><b>" . $post['username'] . ":</b><br>" . $post['content'] . "<br><small>" . $post['created_at'] . "</small></div>";
                //comment form
                echo"
                <form method ='post' action= 'create_comment.php'>
                    <textarea name 'content' placeholder= 'Add a comment...' style= 'width:100%; height: 50px; border-radius:5px; padding:10px; margin: 10px 0 ; ' ></textarea>
                    <input type='hidden' name ='post_id' value='" . $post['post_id']."'>
                    <input id ='button' type= 'submit' value='Comment'>
                </form>";

                //Display comments
                $comments_query = "SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.user_id WHERE comments.post_id = '".$post['post_id']."' ORDER BY comments.created_at ASC";
                $comments_result = mysqli_query($con, $comments_query);
                if(mysqli_num_rows($comments_result) > 0){
                    while ($comment = mysqli_fetch_assoc($comment_result)){
                        echo "<div class = 'comment'><b>". $comment['username'] . ":</b><br>" . $comment['content']. "<br> <small>". $comment['created_at']. "</small></div>";
                    }
                }
            }
      
        ?>
    </div>
</body>
</html>

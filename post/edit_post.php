<?php

session_start();
include ("../login/connection.php");

if(!isset($_SESSION['user_id'])){
    header("Location:../login/connection.php");
    die;
}

$post_id = $_GET ['post_id'];
$query = "SELCT * FROM posts WHERE post_id = '$post_id' AND user_id ='".$_SESSION['user_id']. "' LIMIT 1";
$result = mysqli_query($con, $query);

if(mysqli_num_rows($result)== 1){
    $post_data= mysqli_fetch_assoc($result);
}else{
    header("Location: ../login/index.php");
    die;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $content = $_POST['content'];

    if (!empty($content)) {
        $query = " UPDATE posts SET content= '$content', updated_at = NOW() WHERE post_id='$post_id'";
        if (mysqli_query($con, $query)) {
            header("Location: ../login/index.php");
            die;
        } else {
            $error_message = "Database query failed!";
        }
    } else {
        $error_message = "Please enter some content!";
    }
}

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link rel= "stylesheet" type="text/css" href=" ../style.css">
</head>
<body>

    <div id="box">
        <form method="post">
            <textarea name="content" style= "width:100%; hegiht:100px; border-radius:5px; pading:10px; margin:10px 0;"><?php echo $post_data['content'];</textarea>
            <input id="button" type="submit" value= "Update Post">
        </form>
    </div>

    <?php
    if(!empty($error_message)){
        echo"<script type='text/javascript'> alert('$error_message');</script>";
    }
    ?>

</body>
</html>
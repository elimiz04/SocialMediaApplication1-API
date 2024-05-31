<?php

session_start();
include ("../login/connection.php");

if(!isset($_SESSION['user_id'])){
    header("Location:../login/login.php");
    die;
}

$comment_id = $_GET ['comment_id'];
$query = "SELCT * FROM posts WHERE comment_id = '$comment_id' AND user_id ='".$_SESSION['user_id']. "' LIMIT 1";
$result = mysqli_query($con, $query);

if(mysqli_num_rows($result)== 1){
    $comment_data= mysqli_fetch_assoc($result);
}else{
    header("Location: ../login/index.php");
    die;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $content = $_POST['content'];

    if (!empty($content)) {
        $query = " UPDATE comments SET content= '$content', updated_at = NOW() WHERE comment_id='$comment_id'";
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
    <title>Edit comment</title>
    <link rel= "stylesheet" type="text/css" href=" ../style.css">
</head>
<body>

    <div id="box">
        <form method="post">
            <textarea name="content" style= "width:100%; height:100px; border-radius:5px; padding:10px; margin:10px 0;"><?php echo $comment_data['content'];?</textarea>
            <input id="button" type="submit" value= "Update comment">
        </form>
    </div>

    <?php
    if(!empty($error_message)){
        echo"<script type='text/javascript'> alert('$error_message');</script>";
    }
    ?>

</body>
</html>
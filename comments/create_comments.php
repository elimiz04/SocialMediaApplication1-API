<?php
session_start();
include ("../login/connection.php");

if(!isset($_SESSION['user_id'])){
    header("Location:../login/login.php");
    die;
}

$user_id = $_SESSION['user_id'];
$post_id = $_SESSION['post_id'];
$content = $_SESSION['content'];



    if (!empty($content)) {
        $query = "INSERT INTO comments (post_id, user_id, content) VALUES ('$post_id', $user_id', '$content')";
        if (mysqli_query($con, $query)) {
            header("Location: ../login/index.php");
            die;
        } else {
            $error_message = "Database query failed!";
        }
    } else {
        $error_message = "Please enter some content!";
    }
?> 
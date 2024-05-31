<?php
session_start();
include("connection.php"); 

if(!isset($_SESSION['user_id'])){
    header("Location:../login/connection.php");
    die;
}

$post_id = $_GET['post_id'];
$query = "DELETE FROM posts WHERE post_id = '$post_id' AND user_id = '".$SESSIO['user_id']."'";


        if (mysqli_query($con, $query)) {
            header("Location: ../login/index.php");
            die;
        } else {
            $echo = "Error deleting post";
        }

?> 
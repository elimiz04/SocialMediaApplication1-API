<?php
session_start();
include("connection.php"); 

if(!isset($_SESSION['user_id'])){
    header("Location:../login/login.php");
    die;
}

$comment_id = $_GET['comment_id'];
$query = "DELETE FROM comments WHERE comment_id = '$comment_id' AND user_id = '".$SESSIO['user_id']."'";


        if (mysqli_query($con, $query)) {
            header("Location: ../login/index.php");
            die;
        } else {
            $echo = "Error deleting comment";
        }

?> 
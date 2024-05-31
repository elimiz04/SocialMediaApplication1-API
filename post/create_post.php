<?php

session_start();
include ("../login/connection.php");

if(!isset($_SESSION['user_id'])){
    header("Location:../login/connection.php");
    die;
}

$user_id = $_SESSION['user_id'];
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $content = $_POST['content'];
    if (!empty($content)) {
        $query = "INSERT INTO posts (user_id, content) VALUES ('$user_id', '$content')";
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

if (!empty($error_message)) {
    echo "<script type='text/javascript'>alert('$error_message');</script>";
}
?> 
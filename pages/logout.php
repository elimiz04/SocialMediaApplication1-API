<?php
session_start();

// Destroy session if the user is logged in
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);  
}

// Destroy the session completely
session_destroy();

// Redirect to the login page
header("Location:login.php");
exit;
?>

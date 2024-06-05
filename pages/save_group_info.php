<?php
session_start();
include("../includes/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['group_id'])) {
    // Retrieve group information from the POST data
    $group_id = $_POST['group_id'];
    $group_name = $_POST['group_name'];
    $group_description = $_POST['group_description'];

    // Save the entered text from the input field
    if (isset($_POST['something'])) {
        $something = $_POST['something'];
    }

    header("Location: add_members_process.php?group_id=" . $group_id);
    exit();
} else {
    echo "Invalid request.";
}
?>

<?php

// Include necessary files
include '../includes/connection.php';

// Function to retrieve user ID from the database
function getUserID($username) {
    global $conn; 

    // Query to retrieve user ID based on username
    $query = "SELECT user_id FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    // Check if query was successful
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['user_id'];
    } else {
        return null; 
    }
}

// Function to validate user ID
function validateUserID($userID) {
    return is_numeric($userID) && $userID > 0;
}

?>

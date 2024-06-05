<?php

// Include necessary files
include '../includes/connection.php';

// Function to retrieve user ID from the database
function getUserID($username) {
    global $conn; 

    // Prepare the SQL statement
    $query = "SELECT user_id FROM users WHERE username = ?";
    $statement = mysqli_prepare($conn, $query);

    // Bind the parameter and execute the statement
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);

    // Get the result
    $result = mysqli_stmt_get_result($statement);

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

// Check if the user has set a color mode preference
if (!isset($_SESSION['color_mode'])) {
    // If not, set a default color mode (e.g., light mode)
    $_SESSION['color_mode'] = 'light';
}

// Function to apply the appropriate CSS class based on the color mode
function getColorModeClass() {
    return $_SESSION['color_mode'] === 'light' ? 'light-mode' : 'dark-mode';
}

?>

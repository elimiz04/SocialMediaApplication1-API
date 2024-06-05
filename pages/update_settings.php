<?php
session_start(); // Start or resume the session

include("../includes/connection.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('success' => false, 'error' => 'User not logged in.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $color_scheme = isset($_POST['color_scheme']) ? $_POST['color_scheme'] : 'light'; // Default to light mode if not set

    // Update user's color scheme preference in the database
    $query = "UPDATE user_settings SET color_scheme = ? WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $color_scheme, $user_id);

    if ($stmt->execute()) {
        // Color scheme preference updated successfully
        $_SESSION['color_scheme'] = $color_scheme; // Update color scheme in session
        echo $color_scheme; // Return the updated color scheme
    } else {
        // Log the error
        error_log('Error updating color scheme: ' . $stmt->error);
        // Return error response
        echo json_encode(array('success' => false, 'error' => 'Error updating color scheme'));
    }

    $stmt->close();
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

$conn->close();
?>

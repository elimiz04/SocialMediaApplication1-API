<?php
session_start();
include("../includes/connection.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('success' => false, 'error' => 'User not logged in.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $color_scheme = isset($_POST['color_scheme']) ? $_POST['color_scheme'] : 'light'; // Default to light mode if not set
    $notifications_enabled = isset($_POST['receive_notifications']) ? 1 : 0; // 1 if checked, 0 if not checked

    // Update user's settings in the user_settings table
    $query = "INSERT INTO user_settings (user_id, color_scheme, receive_notifications) VALUES (?, ?, ?) 
              ON DUPLICATE KEY UPDATE color_scheme = VALUES(color_scheme), receive_notifications = VALUES(receive_notifications)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $color_scheme, $notifications_enabled);

    if ($stmt->execute()) {
        // Settings updated successfully in user_settings table

        // Now update or insert user-specific settings in the settings table
        $privacy = ''; // You may set the default privacy value here

        $query = "INSERT INTO settings (user_id, color_scheme, notifications_enabled) 
                  VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE color_scheme = VALUES(color_scheme), notifications_enabled = VALUES(notifications_enabled)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $user_id, $color_scheme, $notifications_enabled);
        $stmt->execute();

        $_SESSION['color_scheme'] = $color_scheme; // Update color scheme in session
        $_SESSION['receive_notifications'] = $notifications_enabled; // Update notification preference in session
        echo json_encode(array('success' => true));
    } else {
        // Log the error
        error_log('Error updating settings: ' . $stmt->error);
        // Return error response
        echo json_encode(array('success' => false, 'error' => 'Error updating settings'));
    }

    $stmt->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Settings</title>
    <link rel="stylesheet" href="styles.css">
    
    <!-- Add your CSS styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            <?php if ($color_scheme === 'dark'): ?>
                background-color: #333;
                color: #f8f9fa;
            <?php else: ?>
                background-color: #f8f9fa;
                color: #333;
            <?php endif; ?>
            margin: 0;
            padding: 0;
        }
        #box {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            text-align: justify;
        }
        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .image-container a {
            width: calc(33.33% - 20px); /* 20px padding between images */
            margin: 10px;
            text-align: center;
            text-decoration: none; 
            color: inherit; 
            height: 200px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            overflow: hidden; 
        }
        .image-container img {
            max-width: 100%; /* Make images fill their containers */
            max-height: 100%; /* Make images fill their containers */
            height: auto; /* Ensure images maintain their aspect ratio */
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Add a subtle shadow effect */
        }
        /* Light Mode Styles */
        .light-mode {
            --text-color: #333;
            --bg-color: #f8f9fa;
        }

        /* Dark Mode Styles */
        .dark-mode {
            --text-color: #f8f9fa;
            --bg-color: #333;
        }

        /* Apply color variables to body */
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        /* Form styles */
        #settings-form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 10px;
            font-size: 18px;
        }

        input[type="radio"],
        input[type="checkbox"] {
            margin-right: 10px;
        }

        button[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body class="<?php echo $_SESSION['color_scheme']; ?>">

    <div id="box">
        <h1>User Settings</h1>
        <form id="settings-form" action="update_settings.php" method="POST">
            <!-- Color Scheme Section -->
            <h2>Color Scheme</h2>
            <label for="light-mode">Light Mode</label>
            <input type="radio" name="color_scheme" value="light" id="light-mode" <?php if ($_SESSION['color_scheme'] === 'light') echo 'checked'; ?>>
            <label for="dark-mode">Dark Mode</label>
            <input type="radio" name="color_scheme" value="dark" id="dark-mode" <?php if ($_SESSION['color_scheme'] === 'dark') echo 'checked'; ?>>

            <!-- Notification Preferences Section -->
            <h2>Notification Preferences</h2>
            <input type="checkbox" name="receive_notifications" id="receive_notifications" 
            <?php if ($_SESSION['receive_notifications'] == 1) echo 'checked'; ?>
            <label for="receive_notifications">Receive Notifications</label>

            <!-- Submit Button -->
            <button type="submit">Save Settings</button>
        
        </form>
    </div>
</body>
</html>

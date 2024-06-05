<?php
session_start();
include("../includes/connection.php"); 
include("../includes/header.php");

// Check if the user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: ../pages/login.php");
    exit; // Stop further execution
}

// Retrieve user settings from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT color_scheme, receive_notifications FROM user_settings WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

// Check if user settings exist
if ($stmt->num_rows > 0) {
    $stmt->bind_result($color_scheme, $receive_notifications);
    $stmt->fetch();
    // Store user settings in session for easy access
    $_SESSION['color_scheme'] = $color_scheme;
    $_SESSION['receive_notifications'] = $receive_notifications;
} else {
    // Default settings if not found in the database
    $_SESSION['color_scheme'] = 'light';
    $_SESSION['receive_notifications'] = 0; // Assuming default is not receiving notifications
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Settings</title>
    <link rel="stylesheet" href="styles.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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

        #box {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1, h2 {
            text-align: center;
        }

        .btn-container {
            margin-top: 20px;
            text-align: center;
        }

        .minimal-btn, .message-btn {
            padding: 10px 20px;
            background-color: transparent;
            color: #337ab7;
            border: 1px solid #337ab7;
            border-radius: 5px;
            text-decoration: none;
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }

        .minimal-btn:hover, .message-btn:hover {
            background-color: #337ab7;
            color: white;
            border-color: #337ab7;
        }

        .settings-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-color);
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        .settings-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            margin-left: 8px;
        }
    </style>
</head>
<body class="light-mode"> 
    <div id="box">
        <h1>User Settings</h1>
        <main>
            <form id="settings-form" action="update_settings.php" method="POST">
                <h2>Color Scheme</h2>
                <label for="light-mode">Light Mode</label>
                <input type="radio" name="color_scheme" value="light" id="light-mode" checked>
                <label for="dark-mode">Dark Mode</label>
                <input type="radio" name="color_scheme" value="dark" id="dark-mode">
                <script>
                    const darkModeRadio = document.getElementById('dark-mode');
                    const body = document.body;

                    // Event listener for dark mode radio button
                    darkModeRadio.addEventListener('change', () => {
                        if (darkModeRadio.checked) {
                            body.classList.add('dark-mode');
                        } else {
                            body.classList.remove('dark-mode');
                        }
                    });
                </script>

                <div class="form-group">
                    <input type="checkbox" name="receive_notifications" id="receive_notifications">
                    <label for="receive_notifications">Receive Notifications</label>
                </div>

                <button type="submit" class="minimal-btn">Save Settings</button>
            </form>
        </main>
    </div>

    <script>
        const form = document.getElementById('settings-form');
        const darkModeRadio = document.getElementById('dark-mode');
        const lightModeRadio = document.getElementById('light-mode');
        const body = document.body;

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            
            fetch('update_settings.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to profile.php
                    window.location.href = 'profile.php';
                } else {
                    console.error('Error saving settings:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });

           // Event listener for dark mode radio button
            darkModeRadio.addEventListener('change', () => {
                if (darkModeRadio.checked) {
                    body.classList.add('dark-mode');
                    body.classList.remove('light-mode');
                }
            });

            // Event listener for light mode radio button
            lightModeRadio.addEventListener('change', () => {
                if (lightModeRadio.checked) {
                    body.classList.add('light-mode');
                    body.classList.remove('dark-mode');
                }
            });
                });
    </script>
</body>
</html>

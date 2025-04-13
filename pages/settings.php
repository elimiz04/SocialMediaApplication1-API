<?php
session_start();
include("../includes/connection.php");
include("../includes/header.php");

// Ensure color scheme is set from session
if (!isset($_SESSION['color_scheme'])) {
    $_SESSION['color_scheme'] = 'light'; // Default to light mode
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Settings</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Light Mode Styling */
body.light {
    background-color: #f8f9fa;  /* Light background */
    color: #333;  /* Dark text for light mode */
}

/* Dark Mode Styling */
body.dark {
    background-color: #333;  /* Dark background for dark mode */
    color: #fff;  /* White text for dark mode */
}

#box {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#222' : '#d7d9db'; ?>;
    color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#fff' : '#333'; ?>;
}

/* Ensure the text is white in dark mode */
body.dark {
    color: #fff;  /* Global text color for dark mode */
}

/* Target specific elements for color adjustments */
h1, h2, p, label {
    color: inherit; /* Inherit color from parent (light or dark) */
}

/* Additional rule for like count text */
#like-count {
    color: inherit; /* Ensure like count text uses the same color */
}

/* Form Styles */
#settings-form {
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


        /* Form Styles */
        #settings-form {
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
            <?php if ($_SESSION['receive_notifications'] == 1) echo 'checked'; ?>>
            <label for="receive_notifications">Receive Notifications</label>

            <!-- Submit Button -->
            <button type="submit">Save Settings</button>
        </form>
    </div>

    <script>
        document.getElementById('settings-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent normal form submission

            // Get form data
            var formData = new FormData(this);
            
            // Send the data to the server via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_settings.php', true);
            xhr.onload = function () {
                var response = JSON.parse(xhr.responseText);

                if (response.success) {
                    // Update UI (for example, change the page color scheme immediately)
                    document.body.className = response.color_scheme; // Apply the updated color scheme

                    // Show success message to the user
                    alert('Settings updated successfully!');

                    // Redirect to home screen after settings update
                    window.location.href = '../pages/home.php';  
                } else {
                    // Show error message
                    alert('Error updating settings: ' + response.error);
                }
            };
            xhr.send(formData);
        });
    </script>

</body>
</html>

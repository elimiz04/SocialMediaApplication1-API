<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];

    // Mark all unread notifications as read for the logged-in user
    $query = "UPDATE Notifications SET is_read = 1 WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    echo "Notifications updated";
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Define your CSS styles for the notification popup here */
        .notification-popup {
            position: fixed;
            top: 50px;
            right: 20px;
            background-color: #fff;
            padding: 10px 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none; /* Initially hide the popup */
        }
    </style>
</head>
<body>
    <div class="notification-popup" id="notificationPopup">
        You have unread messages!
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function updateNotificationIndicator() {
                $.ajax({
                    url: 'get_unread_messages_count.php',
                    type: 'GET',
                    success: function(count) {
                        $('#notificationIndicator').text(count);
                        // Check if there are unread messages
                        if (parseInt(count) > 0) {
                            // Show the notification popup
                            $('#notificationPopup').show();
                        } else {
                            // Hide the notification popup if there are no unread messages
                            $('#notificationPopup').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching unread messages count: ' + error);
                    }
                });
            }

            // Initial call to update notification indicator
            updateNotificationIndicator();

            // Periodically update notification indicator
            setInterval(updateNotificationIndicator, 5000); // Update every 5 seconds (adjust as needed)
        });
    </script>
</body>
</html>

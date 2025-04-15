<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");
include("../includes/header.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all users except the logged-in user
$user_query = "SELECT user_id, username FROM users WHERE user_id != ?";
$stmt_users = $conn->prepare($user_query);
$stmt_users->bind_param("i", $user_id);
$stmt_users->execute();
$users_result = $stmt_users->get_result();

// Set default receiver_id
$receiver_id = isset($_POST['receiver_id']) ? $_POST['receiver_id'] : ($user_id == 1 ? 2 : 1);

// Fetch chat history for the selected receiver
$query = "
    SELECT 
        m.*, 
        u.username AS sender_username 
    FROM Messages AS m 
    INNER JOIN users AS u ON m.sender_id = u.user_id 
    WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?) 
    ORDER BY m.created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $user_id, $receiver_id, $receiver_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Mark all unread messages as read
$update_read_query = "UPDATE Messages SET is_read = 1 WHERE receiver_id = ? AND sender_id = ?";
$stmt_update_read = $conn->prepare($update_read_query);
$stmt_update_read->bind_param("ii", $user_id, $receiver_id);
$stmt_update_read->execute();

// Fetch unread notifications count for the logged-in user
$notification_count_query = "SELECT COUNT(*) as unread_count FROM Notifications WHERE user_id = ? AND is_read = 0";
$stmt_notifications = $conn->prepare($notification_count_query);
$stmt_notifications->bind_param("i", $user_id);
$stmt_notifications->execute();
$notification_result = $stmt_notifications->get_result();
$notification_count = $notification_result->fetch_assoc()['unread_count'];
$stmt_notifications->close();


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
    <title>Messages</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // When the user clicks the "Messages" button
            $('#message-button').click(function() {
                // Make an AJAX request to update the notification count
                $.ajax({
                    type: 'POST',
                    url: 'update_notification.php',
                    success: function(response) {
                        // Update the notification badge to 0
                        $('.notification-badge').text('0');
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
        <link rel="stylesheet" href="../assets/style.css">

    
</head>
<body class="<?php echo getColorModeClass(); ?>">
<div id="box">
        <h1>Messages</h1>

        <!-- List of users -->
        <h2>Select User to Message</h2>
        <form method="post" action="">
            <select name="receiver_id" onchange="this.form.submit()">
                <?php while ($user_row = $users_result->fetch_assoc()): ?>
                    <option value="<?php echo $user_row['user_id']; ?>" <?php echo $receiver_id == $user_row['user_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user_row['username']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <div class="message-container">
            

            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="message <?php echo $row['sender_id'] == $user_id ? 'sent' : 'received'; ?>">
    <div class="message-header">
            <p class="message-content">
                <strong><?php echo htmlspecialchars($row['sender_username']); ?>:</strong> 
                <?php echo htmlspecialchars($row['content']); ?>
            </p>
            <?php if ($row['sender_id'] == $user_id): ?>
                <div class="message-actions">
                    <button onclick="showEditForm(<?php echo $row['message_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['content'])); ?>')">Edit</button>
                    <form method="post" action="delete_message.php" style="display: inline;">
                        <input type="hidden" name="message_id" value="<?php echo $row['message_id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
        <span class="message-time"><?php echo $row['created_at']; ?></span>
    </div>

            <?php endwhile; ?>




        </div>
        <form method="post" action="send_message.php" class="chat-form">
    <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
    <input type="hidden" name="sender_id" value="<?php echo $user_id; ?>">

    <div class="chat-input-wrapper">
        <textarea name="content" class="chat-textarea" placeholder="Type your message..." required></textarea>
        <button type="submit" class="send-btn">➤</button>
    </div>
</form>



    </div>

    <!-- Edit Message Modal -->
    <div id="editModal" style="display:none;">
        <form method="post" action="edit_message.php">
            <textarea name="content" id="editContent" class="chat-input" placeholder="Edit your message..." required></textarea>
    <input type="hidden" name="message_id" id="editMessageId">
    <button type="submit" class="minimal-btn">Update</button>
    <button type="button" onclick="hideEditForm()" class="minimal-btn">Cancel</button>
    </form>
    </div>
    <script>
        function showEditForm(messageId, content) {
            document.getElementById('editMessageId').value = messageId;
            document.getElementById('editContent').value = content;
            document.getElementById('editModal').style.display = 'block';
        }

        function hideEditForm() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
    </body>
    </html>
    <?php
    $stmt->close();
    $stmt_users->close();
    $stmt_update_read->close();
    $conn->close();
    ?>

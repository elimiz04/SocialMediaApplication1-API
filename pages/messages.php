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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            text-align: justify;
        }
        #box {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .btn-container {
            margin-top: 20px;
            text-align: center;
        }
        .minimal-btn {
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
        .minimal-btn:hover {
            background-color: #337ab7;
            color: white;
            border-color: #337ab7;
        }
        .message-container {
            margin: 20px 0;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            position: relative;
        }
        .message.sent {
            background-color: #d1e7dd;
            text-align: right;
        }
        .message.received {
            background-color: #f8d7da;
            text-align: left;
        }
        .chat-input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .message-actions {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .message-actions button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            margin-left: 5px;
        }
    </style>
</head>
<body>
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
                    <p><strong><?php echo htmlspecialchars($row['sender_username']); ?>:</strong> <?php echo htmlspecialchars($row['content']); ?></p>
                    <span><?php echo $row['created_at']; ?></span>
                    <?php if ($row['sender_id'] == $user_id): ?>
                        <div class="message-actions">
                            <!-- Edit button -->
                            <button onclick="showEditForm(<?php echo $row['message_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['content'])); ?>')">Edit</button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
        <form method="post" action="send_message.php">
            <textarea name="content" class="chat-input" placeholder="Type your message..." required></textarea>
            <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
            <input type="hidden" name="sender_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="action" value="send">
            <button type="submit" class="minimal-btn">Send</button>
        </form>

    </div>

    <!-- Edit Message Modal -->
    <div id="editModal" style="display:none;">
        <form method="post" action="edit_message.php">
            <textarea name="content" id="editContent" class="chat-input" placeholder="Edit your message..." required></textarea>
            <input type="hidden" name="message_id" id="editMessageId">
            <input type="hidden" name="action" value="edit">
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

        function openTab(evt, tabId) {
            var i, tabcontent, tablinks;

            // Hide all tab contents
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Remove active class from all tabs
            tablinks = document.getElementsByClassName("tab");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the selected tab and add an "active" class to the tab
            document.getElementById(tabId).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Open the first tab by default
        document.getElementsByClassName('tab')[0].click();
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
</body>
</html>

<?php
$stmt->close();
$stmt_users->close();
$conn->close();
?>

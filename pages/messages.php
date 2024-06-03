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
$receiver_id = ($user_id == 1) ? 2 : 1;

// Fetch chat history
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
    </script>
    

    <script>
    function deleteMessage(messageId) {
        if (confirm("Are you sure you want to delete this message?")) {
            // Get the form by ID
            var form = document.getElementById('deleteForm' + messageId);
            // Submit the form asynchronously
            submitFormAsync(form);
        }
    }

    function submitFormAsync(form) {
        var xhr = new XMLHttpRequest();
        xhr.open(form.method, form.action, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Reload the page after successful deletion
                location.reload();
            } else {
                alert('Error: ' + xhr.responseText);
            }
        };
        xhr.onerror = function () {
            alert('Error: Request failed.');
        };
        xhr.send(new URLSearchParams(new FormData(form)).toString());
    }
</script>


</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

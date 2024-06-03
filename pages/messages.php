<?php
session_start();
// Include necessary files and start session if needed
include("../includes/connection.php");
include("../includes/functions.php");
include("../includes/header.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$mar_id = 2; // User ID for "mar"

function getMessageCount($user_id) {
    global $conn; // Ensure $conn is in global scope

    // Prepare and execute query to count unread messages for the user
    $query = "SELECT COUNT(*) AS message_count FROM Messages WHERE receiver_id = ? AND is_read = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the message count from the result
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $message_count = $row['message_count'];
    } else {
        $message_count = 0; 
    }

    $stmt->close();
    return $message_count;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content'])) {
    $sender_id = $user_id;
    $receiver_id = $mar_id; // Send messages to "mar"
    $content = $_POST['content'];
    $created_at = date('Y-m-d H:i:s');

    $query = "INSERT INTO Messages (sender_id, receiver_id, content, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss", $sender_id, $receiver_id, $content, $created_at);
    $stmt->execute();

    // Redirect to a page after sending message, if needed
    header("Location: messages.php");
    exit();
}

// Fetch all messages between the logged-in user and "mar"
$query = "
    SELECT m.*, 
           u1.username AS sender_username, 
           u2.username AS receiver_username 
    FROM Messages m
    JOIN users u1 ON m.sender_id = u1.user_id
    JOIN users u2 ON m.receiver_id = u2.user_id
    WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $user_id, $mar_id, $mar_id, $user_id);
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
        #box {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        .message {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            border-radius: 10px;
        }
        .message p {
            margin: 5px 0;
        }
        .message .username {
            font-weight: bold;
        }
        .message .content {
            margin: 10px 0;
        }
        .message .timestamp {
            font-size: 12px;
            color: #666;
        }
        .message-form {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }
        .message-form input, .message-form textarea {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .message-form button {
            padding: 10px;
            background-color: #337ab7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .message-form button:hover {
            background-color: #285e8e;
        }
    </style>
</head>
<body>
    <div id="box">
        <h1>Conversation with mar</h1>
        
        <br>
        <br>

        <!-- Display messages -->
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="message">
                <p class="username"><?php echo $row['sender_id'] == $user_id ? "You" : $row['sender_username']; ?>:</p>
                <p class="content"><?php echo $row['content']; ?></p>
                <p class="timestamp">Sent at: <?php echo $row['created_at']; ?></p>
            </div>
        <?php endwhile; ?>

        <!-- Message form -->
        <form class="message-form" method="post" action="messages.php">
            <label for="content">Message:</label>
            <textarea id="content" name="content" required></textarea>
            <br>
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>

<?php
ob_end_flush();
?>

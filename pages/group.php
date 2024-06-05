<?php
session_start();
include("../includes/connection.php");
include("../includes/header.php");

// Check if group_id is provided in the URL
if (isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];
} else {
    // Redirect back to the "Add Members to Group" page with an error message if group_id is not provided
    header("Location: add_members.php?error=1");
    exit;
}

// Retrieve group information from the database
$get_group_query = "SELECT name, description FROM groups WHERE group_id = ?";
$stmt_get_group = $conn->prepare($get_group_query);

if ($stmt_get_group === false) {
    echo "Failed to prepare statement: " . $conn->error;
    exit;
}

$stmt_get_group->bind_param("i", $group_id);

if (!$stmt_get_group->execute()) {
    echo "Error executing query: " . $stmt_get_group->error;
    exit;
}

$group_result = $stmt_get_group->get_result();

// Check if group exists
if ($group_result->num_rows > 0) {
    $group = $group_result->fetch_assoc();
    $group_name = $group['name'];
    $group_description = $group['description'];
} else {
    echo "Group not found.";
    exit;
}

// Retrieve messages associated with the group from the database
$get_messages_query = "SELECT users.username AS sender_username, messages.content, messages.created_at
                       FROM messages
                       JOIN users ON messages.sender_id = users.user_id
                       WHERE messages.group_id = ?
                       ORDER BY messages.created_at ASC";
$stmt_get_messages = $conn->prepare($get_messages_query);

if ($stmt_get_messages === false) {
    echo "Failed to prepare statement: " . $conn->error;
    exit;
}

$stmt_get_messages->bind_param("i", $group_id);

if (!$stmt_get_messages->execute()) {
    echo "Error executing query: " . $stmt_get_messages->error;
    exit;
}

$messages_result = $stmt_get_messages->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group Chat: <?php echo htmlspecialchars($group_name); ?></title>
    <link rel="stylesheet" href="../styles/profile_style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .chat-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .chat {
            width: 100%;
            height: 400px;
            background-color: #f2f2f2;
            overflow-y: scroll; /* Enable vertical scrollbar */
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .chat-message {
            margin-bottom: 10px;
        }
        .chat-message span {
            font-weight: bold;
        }
        .input-message {
            width: 100%;
            padding: 10px;
            box-sizing: border-box; /* Include padding and border in element's total width and height */
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .send-button {
            padding: 10px 20px;
            background-color: #337ab7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h1>Group Chat: <?php echo htmlspecialchars($group_name); ?></h1>
        <div class="chat">
            <?php
            // Display messages associated with the group
            while ($message = $messages_result->fetch_assoc()) {
                echo "<div class='chat-message'>";
                echo "<span>" . htmlspecialchars($message['sender_username']) . "</span>: " . htmlspecialchars($message['content']);
                echo "<br>";
                echo "<span>" . htmlspecialchars($message['created_at']) . "</span>";
                echo "</div>";
            }
            ?>
        </div>
        <form action="send_message.php" method="post">
            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
            <textarea class="input-message" name="message" placeholder="Type your message..." required></textarea>
            <br>
            <button type="submit" class="send-button">Send</button>
        </form>
        <div class="btn-container">
            <a href="add_members_form.php?group_id=<?php echo $group_id; ?>">Add more members</a>
        </div>
    </div>
</body>
</html>

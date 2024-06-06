<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;

$query = "SELECT Messages.*, Users.username FROM Messages 
          JOIN Users ON Messages.sender_id = Users.id 
          WHERE Messages.receiver_id = ? 
          ORDER BY Messages.created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            background-color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#333' : '#f8f9fa'; ?>;
            color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#f8f9fa' : '#333'; ?>;
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
            overflow-y: scroll;
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
            width: calc(100% - 85px);
            padding: 10px;
            box-sizing: border-box;
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
            width: 75px;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h1>Group Chat</h1>
        <div class="chat">
            <!-- Display chat messages here -->
            <?php foreach ($messages as $message): ?>
                <div class="chat-message">
                    <span><?php echo htmlspecialchars($message['username']); ?>:</span> 
                    <?php echo htmlspecialchars($message['content']); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <form id="messageForm" action="send_group_message.php" method="post">
            <input type="hidden" name="receiver_id" value="<?php echo $group_id; ?>">
            <textarea name="content" placeholder="Type your message..." required></textarea>
            <button type="submit">Send</button>
        </form>
        <div class="btn-container">
            <a href="group.php?group_id=<?php echo $group_id; ?>">View Group</a>
        </div>
    </div>
  


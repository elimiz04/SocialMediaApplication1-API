<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;

$query = "SELECT Messages.*, Users.username 
          FROM Messages 
          JOIN Users ON Messages.sender_id = Users.user_id 
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

foreach ($messages as $message) {
    echo '<div class="chat-message">';
    echo '<span>' . htmlspecialchars($message['username']) . ':</span> ';
    echo htmlspecialchars($message['content']);
    echo '</div>';
}
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
        
        
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        function loadMessages() {
            $.ajax({
                url: 'load_messages.php?group_id=<?php echo $group_id; ?>',
                method: 'GET',
                success: function(data) {
                    $('.chat').html(data);
                }
            });
        }


        $('#messageForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'send_group_message.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function() {
                    loadMessages();
                    $('textarea[name="content"]').val('');
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr.responseText);
                }
            });
        });
    });
    </script>
    
</body>
</html>

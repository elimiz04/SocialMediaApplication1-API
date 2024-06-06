<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");
include("../includes/header.php");

if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;

// Verify that the column names and table names are correct
$query = "
    SELECT Messages.*, Users.username 
    FROM Messages 
    JOIN Users ON Messages.sender_id = Users.id 
    WHERE Messages.receiver_id = ? 
    ORDER BY Messages.created_at ASC
";

// If `Users.id` is incorrect, replace it with the correct column name, for example `Users.user_id`
$query = "
    SELECT Messages.*, Users.username 
    FROM Messages 
    JOIN Users ON Messages.sender_id = Users.user_id 
    WHERE Messages.receiver_id = ? 
    ORDER BY Messages.created_at ASC
";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
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
        textarea {
            width: calc(100% - 85px);
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }
        button {
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
        <div class="chat"></div>
        <form id="messageForm">
            <input type="hidden" name="receiver_id" value="<?php echo $group_id; ?>">
            <textarea name="content" placeholder="Type your message..." required></textarea>
            <button type="submit">Send</button>
        </form>
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
        
        // Disable submit button to prevent multiple submissions
        $('#messageForm button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: 'send_group_message.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function() {
                loadMessages(); // Reload messages after sending a message
                $('textarea[name="content"]').val('');
            },
            error: function(xhr, status, error) {
                console.log('Error:', xhr.responseText);
            },
            complete: function() {
                // Re-enable submit button after completion
                $('#messageForm button[type="submit"]').prop('disabled', false);
            }
        });
    });

    loadMessages(); // Initial load of messages
});
</script>

</body>
</html>

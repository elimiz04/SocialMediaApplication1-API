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

// Retrieve members currently in the chat
$get_members_query = "SELECT users.username, group_members.selected
                      FROM users
                      JOIN group_members ON users.user_id = group_members.user_id
                      WHERE group_members.group_id = ? AND group_members.selected = 1";
$stmt_get_members = $conn->prepare($get_members_query);

if ($stmt_get_members === false) {
    echo "Failed to prepare statement: " . $conn->error;
    exit;
}

$stmt_get_members->bind_param("i", $group_id);

if (!$stmt_get_members->execute()) {
    echo "Error executing query: " . $stmt_get_members->error;
    exit;
}

$members_result = $stmt_get_members->get_result();
echo '<p>Group members added successfully. <a href="add_members_form.php?group_id=' . $group_id . '">Add more members</a></p>';
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
        <form action="send_message.php" method="post">
            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
            <textarea class="input-message" name="message" placeholder="Type your message..." required></textarea>
            <br>
            <button type="submit" class="send-button">Send</button>
        </form>
        <div class="btn-container">
            <a href="group.php?group_id=<?php echo $group_id; ?>">View Group</a>
        </div>
    </div>
</body>
</html>

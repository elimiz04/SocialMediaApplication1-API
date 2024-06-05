<?php
session_start();
include("../includes/connection.php");
include("../includes/header.php");

// Initialize variables
$group_name = ""; 
$group_description = "";
$member_names = []; 
$user_ids = []; 

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if group_id, sender_id, content, group_name, and group_description are provided
    if (isset($_POST['group_id'], $_POST['user_ids'], $_POST['sender_id'], $_POST['content'], $_POST['group_name'], $_POST['group_description'])) {
        $group_id = $_POST['group_id'];
        $user_ids = $_POST['user_ids'];
        $sender_id = $_POST['sender_id'];
        $content = $_POST['content'];
        $group_name = $_POST['group_name'];
        $group_description = $_POST['group_description'];

        // Prepare and execute a query to insert each user into the group_members table
        $insert_query = "INSERT INTO group_members (group_id, user_id, joined_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);

        if ($stmt === false) {
            echo "Failed to prepare statement: " . $conn->error;
            exit;
        }

        foreach ($user_ids as $user_id) {
            $stmt->bind_param("ii", $group_id, $user_id);
            if (!$stmt->execute()) {
                echo "Error inserting user: " . $stmt->error;
                exit;
            }
            // Retrieve the name of the member added
            $get_member_name_query = "SELECT username FROM users WHERE user_id = ?";
            $stmt_get_member_name = $conn->prepare($get_member_name_query);
            $stmt_get_member_name->bind_param("i", $user_id);
            $stmt_get_member_name->execute();
            $member_result = $stmt_get_member_name->get_result();
            $member = $member_result->fetch_assoc();
            $member_names[] = $member['username'];
        }

        // Insert the message into the group_messages table
        $message_query = "INSERT INTO group_messages (group_id, sender_id, content, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
        $stmt_message = $conn->prepare($message_query);
        $stmt_message->bind_param("iis", $group_id, $sender_id, $content);
        if (!$stmt_message->execute()) {
            echo "Error inserting message: " . $stmt_message->error;
            exit;
        }

        echo "<div class='group-container'>";
        echo "<h2>Group: " . htmlspecialchars($group_name) . "</h2>";
        echo "<p>Description: " . htmlspecialchars($group_description) . "</p>";
        echo "<p>Members Added:</p>";
        foreach ($member_names as $member_name) {
            echo "<input type='text' value='" . htmlspecialchars($member_name) . "' readonly><br>";
        }

        // Display a confirmation message
        echo "<p>Message sent: " . htmlspecialchars($content) . "</p>";

        echo "</div>";
    } else {
        echo "Group ID, user IDs, sender ID, group name, group description, or content not provided.";
    }
} else {
    echo "Invalid request.";
}
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Members Result</title>
    <link rel="stylesheet" href="../styles/profile_style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding-top: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .group-container {
            width: 300px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        input[type="text"], textarea {
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .minimal-btn {
            background-color: #337ab7;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .minimal-btn:hover {
            background-color: #286090;
        }
    </style>
</head>
<body>
<div class="group-container">
        <h2>Group: <?php echo htmlspecialchars($group_name); ?></h2>
        <p>Description: <?php echo htmlspecialchars($group_description); ?></p>
        <p>Members Added:</p>
        <?php foreach ($member_names as $member_name): ?>
            <input type="text" value="<?php echo htmlspecialchars($member_name); ?>" readonly><br>
        <?php endforeach; ?>

        <!-- Add a text box for additional input -->
        <form method="post" action="add_members_process.php">
            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
            <input type="hidden" name="sender_id" value="<?php echo $_SESSION['user_id']; ?>">
            <?php foreach ($user_ids as $user_id): ?>
                <input type="hidden" name="user_ids[]" value="<?php echo $user_id; ?>">
            <?php endforeach; ?>
            <input type="hidden" name="group_name" value="<?php echo htmlspecialchars($group_name); ?>">
            <input type="hidden" name="group_description" value="<?php echo htmlspecialchars($group_description); ?>">
            <textarea placeholder="Enter your message..." name="content" required></textarea><br>
            <button type="submit" class="minimal-btn">Save</button>
            <?php if (isset($content)): ?>
                <!-- Display the message if $content is set -->
                <p>Message sent: <?php echo htmlspecialchars($content); ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>

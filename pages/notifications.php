<?php

// Function to fetch message count from the database
function fetchMessageCount($user_id) {
    // Establish database connection
    $conn = getConnection();

    // Prepare SQL query to count unread messages
    $query = "SELECT COUNT(*) AS unread_count FROM messages WHERE recipient_id = ? AND is_read = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the unread count from the result set
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $unread_count = $row['unread_count'];
    } else {
        $unread_count = 0; // Default to 0 if no unread messages found
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Return the count of unread messages
    return $unread_count;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages</title>
    <!-- Include CSS styles -->
</head>
<body>
    <div class="btn-container">
        <a href="messages.php" class="minimal-btn">Messages <?php echo getMessageCount($user_id); ?></a> <!-- Display message count -->
    </div>
</body>
</html>

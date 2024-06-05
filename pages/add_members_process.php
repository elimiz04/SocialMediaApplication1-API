<?php
session_start();
include("../includes/connection.php");

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if group_id and user_ids are provided
    if (isset($_POST['group_id']) && isset($_POST['user_ids'])) {
        $group_id = $_POST['group_id'];
        $user_ids = $_POST['user_ids'];

        // Prepare and execute a query to insert each user into the group_members table
        $insert_query = "INSERT INTO group_members (group_id, user_id, joined_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);

        if ($stmt === false) {
            echo "Failed to prepare statement: " . $conn->error;
            exit;
        }

        // Initialize variables to store group information and member names
        $group_name = "";
        $group_description = "";
        $member_names = [];

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

        // Retrieve group information
        $get_group_query = "SELECT name, description FROM groups WHERE group_id = ?";
        $stmt_get_group = $conn->prepare($get_group_query);
        $stmt_get_group->bind_param("i", $group_id);
        $stmt_get_group->execute();
        $group_result = $stmt_get_group->get_result();
        $group = $group_result->fetch_assoc();
        $group_name = $group['name'];
        $group_description = $group['description'];

        // Display group information and member names
        echo "<h2>Group: " . htmlspecialchars($group_name) . "</h2>";
        echo "<p>Description: " . htmlspecialchars($group_description) . "</p>";
        echo "<p>Members Added:</p>";
        foreach ($member_names as $member_name) {
            echo "<input type='text' value='" . htmlspecialchars($member_name) . "'><br>";
        }
    } else {
        echo "Group ID or user IDs not provided.";
    }
} else {
    echo "Invalid request.";
}
?>

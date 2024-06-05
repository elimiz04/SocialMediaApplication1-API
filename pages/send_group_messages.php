<?php
session_start();
include("../includes/connection.php"); // Include your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['group_id'], $_POST['sender_id'], $_POST['message'])) {
        // Assign values from the form
        $group_id = $_POST['group_id'];
        $sender_id = $_POST['sender_id'];
        $content = $_POST['message'];

        // Prepare the SQL INSERT statement
        $insert_message_query = "INSERT INTO messages (group_id, sender_id, content) VALUES (?, ?, ?)";
        $stmt_insert_message = $conn->prepare($insert_message_query);

        if ($stmt_insert_message === false) {
            echo "Failed to prepare statement: " . $conn->error;
            exit;
        }

        // Bind parameters and execute the statement
        $stmt_insert_message->bind_param("iis", $group_id, $sender_id, $content);
        if (!$stmt_insert_message->execute()) {
            echo "Error executing query: " . $stmt_insert_message->error;
            exit;
        }

        // Close the statement
        $stmt_insert_message->close();

        // Redirect back to the page after successful insertion
        header("Location: group.php?group_id=$group_id");
        exit;
    } else {
        // Handle case where required fields are not set
        echo "All required fields are not set.";
        exit;
    }
} else {
    // Handle case where form is not submitted
    echo "Form not submitted.";
    exit;
}
?>

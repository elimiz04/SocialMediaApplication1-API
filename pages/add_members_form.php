<?php
session_start();
include("../includes/connection.php");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if group_id is provided in the URL
if (isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];

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
} else {
    echo "Group ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group: <?php echo htmlspecialchars($group_name); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($group_name); ?></h1>
    <p><?php echo htmlspecialchars($group_description); ?></p>

    <h2>Add Members</h2>
    <p><a href="add_members.php?group_id=<?php echo $group_id; ?>">Add Members to Group</a></p>
</body>
</html>

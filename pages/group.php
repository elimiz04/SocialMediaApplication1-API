<?php
session_start();
include("../includes/connection.php");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Retrieve groups information from the database
$get_groups_query = "SELECT group_id, name, description FROM groups";
$stmt_get_groups = $conn->prepare($get_groups_query);

if ($stmt_get_groups === false) {
    echo "Failed to prepare statement: " . $conn->error;
    exit;
}

if (!$stmt_get_groups->execute()) {
    echo "Error executing query: " . $stmt_get_groups->error;
    exit;
}

$groups_result = $stmt_get_groups->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Groups</title>
</head>
<body>
    <h1>Groups</h1>
    <ul>
        <?php
        // Check if groups exist
        if ($groups_result->num_rows > 0) {
            // Fetch each group and display its information
            while ($group = $groups_result->fetch_assoc()) {
                $group_id = $group['group_id'];
                $group_name = $group['name'];
                $group_description = $group['description'];
                ?>
                <li>
                    <a href="group.php?group_id=<?php echo $group_id; ?>"><?php echo htmlspecialchars($group_name); ?></a> - <?php echo htmlspecialchars($group_description); ?>
                </li>
                <?php
            }
        } else {
            echo "No groups found.";
        }
        ?>
    </ul>
</body>
</html>

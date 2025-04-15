<?php
session_start();
include("../includes/connection.php");
include("../includes/header.php");

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
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div id="box">
        <h1>Welcome to Groups</h1>
        <div class="btn-container">
            <a href="create_group_form.php" class="minimal-btn">Create New Group</a>
        </div>
        <ul class="group-container">
            <?php
            // Check if groups exist
            if ($groups_result->num_rows > 0) {
                // Fetch each group and display its information
                while ($group = $groups_result->fetch_assoc()) {
                    $group_id = $group['group_id'];
                    $group_name = $group['name'];
                    $group_description = $group['description'];
                    ?>
                    <li class="group">
                        <h3><?php echo htmlspecialchars($group_name); ?></h3>
                        <p><?php echo htmlspecialchars($group_description); ?></p>
                        <a href="add_members_process.php?group_id=<?php echo $group_id; ?>" class="minimal-btn">Join Group</a>                    
                    </li>
                    <?php
                }
            } else {
                echo "<p>No groups found.</p>";
            }
            ?>
        </ul>
    </div>
</body>
</html>

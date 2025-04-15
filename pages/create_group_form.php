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
    <title>Create Group</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div id="box">
        <h1>Create a New Group</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="name">Group Name:</label><br>
            <input type="text" id="name" name="name" required><br>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" required></textarea><br>
            <input type="submit" value="Create Group">
        </form>
    </div>

    <?php
    include("../includes/connection.php");

    // Enable error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Handle Group Creation Form Submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $description = $_POST['description'];

        if (empty($name) || empty($description)) {
            echo "Name or description cannot be empty";
            exit;
        }

        $insert_group_query = "INSERT INTO groups (name, description, created_at) VALUES (?, ?, NOW())";
        $stmt_insert_group = $conn->prepare($insert_group_query);
        if ($stmt_insert_group === false) {
            echo "Failed to prepare statement: " . $conn->error;
            exit;
        }
        $stmt_insert_group->bind_param("ss", $name, $description);

        if ($stmt_insert_group->execute()) {
            echo "Group created successfully!";
        } else {
            echo "Error executing query: " . $stmt_insert_group->error;
        }
    }
    ?>
</body>
</html>

<?php
session_start();
include("../includes/connection.php");
include("../includes/header.php");

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
    <link rel="stylesheet" href="../styles/profile_style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding-top: 50px; 
            background-color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#333' : '#f8f9fa'; ?>;
            color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#f8f9fa' : '#333'; ?>;
        }

        h1, h2 {
            color: #333;
            text-align: center;
        }

        p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            text-align: justify;
        }

        /* Apply the same group styling */
        .group {
            width: 300px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center align content */
        }

        .group p {
            margin-bottom: 10px;
        }

        .btn-container {
            margin-top: 20px;
            text-align: center;
            align-items: center; 
        }
        .minimal-btn {
            padding: 10px 20px;
            background-color: transparent;
            color: #337ab7;
            border: 1px solid #337ab7;
            border-radius: 5px;
            text-decoration: none;
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }
        .minimal-btn:hover {
            background-color: #337ab7;
            color: white;
            border-color: #337ab7;
        }
    </style>
</head>
<body>
    <div class="group">
        <h1><?php echo htmlspecialchars($group_name); ?></h1>
        <p><?php echo htmlspecialchars($group_description); ?></p>

        <h2>Add Members</h2>
        <p><a href="add_members.php?group_id=<?php echo $group_id; ?>" class="minimal-btn">Add Members to Group</a></p>
    </div>
</body>
</html>

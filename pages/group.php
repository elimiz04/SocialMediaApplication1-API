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
    <link rel="stylesheet" href="../styles/profile_style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
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
        /* Box Styles */
        #box {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            background-color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '000' : '#d7d9db'; ?>;
            color: <?php echo $_SESSION['color_scheme'] === 'dark' ? '#f8f9fa' : '#333'; ?>;
        }

        
        /* Button Styles */
        .btn-container {
            margin-top: 20px;
            text-align: center;
        }
        .minimal-btn, .message-btn {
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
        .minimal-btn:hover, .message-btn:hover {
            background-color: #337ab7;
            color: white;
            border-color: #337ab7;
        }

        .group-container {
            list-style: none; /* Remove bullet points */
            padding: 0;
            display: flex;
            flex-wrap: wrap; /* Allow groups to wrap to the next line */
            justify-content: center; /* Center align groups */
        }

        .group {
            width: 300px;
            margin: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left; /* Align text to the left */
        }

        .group h3 {
            margin-top: 0;
        }

        .group p {
            margin-bottom: 10px; /* Increase margin bottom for spacing */
        }

        .btn-container {
            margin-top: 20px;
            text-align: center;
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

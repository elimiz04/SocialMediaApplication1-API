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

$get_followers_query = "SELECT users.user_id, users.username
                        FROM users
                        JOIN follows ON users.user_id = follows.follower_id
                        WHERE follows.followed_id = ?";

$stmt_get_followers = $conn->prepare($get_followers_query);
if ($stmt_get_followers === false) {
    echo "Failed to prepare statement: " . $conn->error;
    exit;
}

$user_id = $_SESSION['user_id']; 
$stmt_get_followers->bind_param("i", $user_id);

if (!$stmt_get_followers->execute()) {
    echo "Error executing query: " . $stmt_get_followers->error;
    exit;
}

$followers_result = $stmt_get_followers->get_result();

$stmt_get_followers->close();


$user_id = $_SESSION['user_id']; 
$get_followers_query = "SELECT user_id, username FROM users WHERE user_id IN (SELECT followed_id FROM follows WHERE follower_id = ?)";
$stmt_get_followers = $conn->prepare($get_followers_query);

if ($stmt_get_followers === false) {
    echo "Failed to prepare statement: " . $conn->error;
    exit;
}

$stmt_get_followers->bind_param("i", $user_id);

if (!$stmt_get_followers->execute()) {
    echo "Error executing query: " . $stmt_get_followers->error;
    exit;
}

$followers_result = $stmt_get_followers->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Members to Group: <?php echo htmlspecialchars($group_name); ?></title>
    <link rel="stylesheet" href="../styles/profile_style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center; /* Center align everything horizontally */
            align-items: center; /* Center align everything vertically */
        }

        /* Container for group content */
        .group-container {
            width: 300px;
            padding: 10px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center-align text */
            margin-top: 50px; /* Add margin to push down from header */
        }

        h2 {
            color: #333;
            margin-bottom: 10px; /* Add margin bottom for spacing */
        }

        label, select, .submit-button {
            display: block;
            margin: 0 auto 10px auto; /* Center-align elements */
        }
        .minimal-btn:hover {
            background-color: #337ab7;
            color: white;
            border-color: #337ab7;
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
    </style>
</head>
<body>
    <div class="group-container">
        <h2>Add Members to Group: <?php echo htmlspecialchars($group_name); ?></h2>
        <form action="add_members_process.php" method="post">
            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
            <label for="user_ids">Select Followers to Add to Group (hold Ctrl/Cmd to select multiple):</label>
            <select id="user_ids" name="user_ids[]" multiple required>
                <?php
                // Display followers as options in the multi-select dropdown
                while ($follower = $followers_result->fetch_assoc()) {
                    $follower_id = $follower['user_id'];
                    $follower_username = $follower['username'];
                    echo "<option value='$follower_id'>$follower_username</option>";
                }
                ?>
            </select>
            <input type="submit" value="Add Selected Members" class="minimal-btn">
        </form>
    </div>
</body>
</html>



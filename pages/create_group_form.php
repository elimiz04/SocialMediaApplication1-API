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

        /* Form Styles */
        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #337ab7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #135688;
        }
    </style>
</head>
<body>
    <div id="box">
        <h2>Create a New Group</h2>
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

<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            margin: 0;
            padding-top: 60px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .dashboard-links {
            display: flex;
            gap: 20px;
            flex-direction: column;
            align-items: center;
        }

        .dashboard-links a {
            padding: 12px 20px;
            text-decoration: none;
            background-color: #337ab7;
            color: white;
            border-radius: 6px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .dashboard-links a:hover {
            background-color: #286090;
        }

        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 16px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <!-- Logout Button -->
    <a href="?logout=true"><button class="logout-button">Logout</button></a>

    <h1>Welcome to the Admin Dashboard</h1>
    <p>You are logged in as an admin.</p>

    <div class="dashboard-links">
        <a href="admin_manage_users.php">Manage Users</a>
        <a href="admin_manage_posts.php">Manage Posts</a>
    </div>
</body>
</html>

<?php
// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: admin_login.php');
    exit();
}
?>

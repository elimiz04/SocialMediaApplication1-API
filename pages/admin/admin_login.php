<?php
// admin_login.php
session_start();

// Correct relative path to the connection file
include('../../includes/connection.php'); // adjust if needed based on your actual folder structure

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded credentials for the admin user (you can replace this with a DB check if needed)
    $correct_username = 'asd';
    $correct_password = '123';

    if ($username === $correct_username && $password === $correct_password) {
        session_regenerate_id(); // Security measure
        $_SESSION['admin'] = true;
        header('Location: admin_dashboard.php');
        exit();
    } else {
        $error = 'Invalid username or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* General Page Layout */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0;
            height: 100vh;
        }

        /* Login Box Styling */
        .login-box {
            background-color: #444;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 320px;
            color: #fff;
        }

        h1 {
            margin-bottom: 24px;
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            background-color: #337ab7;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #286090;
        }

        .error {
            color: #ff4c4c;
            margin-top: 10px;
            text-align: center;
        }

        /* Logout Button Styling (if you want to add logout functionality here too) */
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
    <!-- Logout Button (if needed, otherwise remove this block) -->
    <!-- <a href="?logout=true"><button class="logout-button">Logout</button></a> -->

    <div class="login-box">
        <h1>Admin Login</h1>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" name="login">Login</button>
        </form>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
session_start();
include('../../includes/connection.php');

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: admin_login.php');
    exit();
}

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// Handle block/unblock/delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $target_id = $_POST['user_id'];

    if ($action === 'block') {
        $stmt = $conn->prepare("UPDATE users SET status = 'blocked' WHERE user_id = ?");
    } elseif ($action === 'unblock') {
        $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE user_id = ?");
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    } elseif ($action === 'make_admin') {
        $stmt = $conn->prepare("UPDATE users SET is_admin = 1 WHERE user_id = ?");
    } elseif ($action === 'remove_admin') {
        $stmt = $conn->prepare("UPDATE users SET is_admin = 0 WHERE user_id = ?");
    }

    if (isset($stmt)) {
        $stmt->bind_param("i", $target_id);
        $stmt->execute();
    }
}

// Fetch all users including is_admin column
$users = $conn->query("SELECT user_id, username, status, is_admin FROM users ORDER BY username ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <a href="admin_dashboard.php" class="back-arrow">&#8592; Back to Dashboard</a>

    <style>
        /* General Page Layout */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            margin: 0;
            padding-top: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Table Styling */
        table {
            width: 80%;
            max-width: 1200px;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #337ab7;
            color: white;
            font-size: 16px;
        }

        td {
            font-size: 14px;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        /* Button Styling */
        button {
            padding: 10px 16px;
            border-radius: 4px;
            background-color: #337ab7;
            color: white;
            font-size: 14px;
            cursor: pointer;
            margin: 5px;
            text-transform: capitalize;
        }

        button:hover {
            background-color: #286090;
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Action Forms */
        form {
            display: inline-block;
            margin: 0 5px;
        }

        /* User Status & Role Styling */
        td em {
            font-style: italic;
            color: #888;
        }

        td .status-active {
            color: green;
        }

        td .status-blocked {
            color: red;
        }

        td .role-admin {
            color: #f39c12;
        }

        td .role-user {
            color: #3498db;
        }

        /* Additional Styling for Messages or Errors */
        .error {
            color: #ff4c4c;
            margin-top: 10px;
            text-align: center;
            font-size: 16px;
        }

        /* Logout Button Styling */
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
        .back-arrow {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 16px;
            color: #337ab7;
            text-decoration: none;
            font-weight: bold;
            background-color: #fff;
            padding: 8px 12px;
            border: 1px solid #337ab7;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .back-arrow:hover {
            background-color: #337ab7;
            color: white;
        }

    </style>
</head>
<body>
    <!-- Logout Button -->
    <a href="?logout=true"><button class="logout-button">Logout</button></a>

    <h1>Manage Users</h1>
    <table>
        <tr>
            <th>Username</th>
            <th>Status</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>

        <?php while ($user = $users->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td class="<?= $user['status'] === 'active' ? 'status-active' : 'status-blocked' ?>"><?= $user['status'] ?></td>
                <td class="<?= $user['is_admin'] ? 'role-admin' : 'role-user' ?>"><?= $user['is_admin'] ? 'Admin' : 'User' ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                        <?php if ($user['status'] === 'active') { ?>
                            <button type="submit" name="action" value="block">Block</button>
                        <?php } else { ?>
                            <button type="submit" name="action" value="unblock">Unblock</button>
                        <?php } ?>
                    </form>

                    <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                        <button type="submit" name="action" value="delete">Delete</button>
                    </form>

                    <?php if (!$user['is_admin']) { ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <button type="submit" name="action" value="make_admin">Make Admin</button>
                        </form>
                    <?php } else { ?>
                        <form method="post" style="display:inline;" onsubmit="return confirm('Remove admin rights from this user?');">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <button type="submit" name="action" value="remove_admin">Remove Admin</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

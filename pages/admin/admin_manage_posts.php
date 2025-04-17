<?php
session_start();
include("../../includes/connection.php");

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle delete post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post_id'])) {
    $post_id = $_POST['delete_post_id'];
    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
}

// Fetch all posts
$result = $conn->query("SELECT posts.post_id, posts.content, posts.created_at, users.username 
                        FROM posts 
                        JOIN users ON posts.user_id = users.user_id 
                        ORDER BY posts.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Posts</title>
    <a href="admin_dashboard.php" class="back-arrow">&#8592; Back to Dashboard</a>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            margin: 0;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 90%;
            max-width: 1000px;
            border-collapse: collapse;
            background-color: #fff;
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

        tr:hover {
            background-color: #f9f9f9;
        }

        button {
            padding: 8px 12px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c0392b;
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

<a href="?logout=true"><button class="logout-button">Logout</button></a>
<h1>Manage Posts</h1>

<table>
    <tr>
        <th>Post ID</th>
        <th>Username</th>
        <th>Content</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    <?php while ($post = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($post['post_id']) ?></td>
            <td><?= htmlspecialchars($post['username']) ?></td>
            <td><?= htmlspecialchars($post['content']) ?></td>
            <td><?= htmlspecialchars($post['created_at']) ?></td>
            <td>
                <form method="post" onsubmit="return confirm('Are you sure you want to delete this post?');">
                    <input type="hidden" name="delete_post_id" value="<?= $post['post_id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>

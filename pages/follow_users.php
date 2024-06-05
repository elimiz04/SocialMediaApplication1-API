<?php
session_start();
include("../includes/connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit;
}

// Fetch a list of users except the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT user_id, username FROM users WHERE user_id <> ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$users_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Follow Users</title>
    <style>
        /* Your CSS styles here */
    </style>
</head>
<body>

<h2>Follow Users</h2>

<?php
if ($users_result->num_rows > 0) {
    while ($user_row = $users_result->fetch_assoc()) {
        $user_id_to_follow = $user_row['user_id'];
        $username_to_follow = $user_row['username'];

        // Check if the logged-in user is already following this user
        $is_following_query = "SELECT * FROM Follows WHERE follower_id = ? AND followed_id = ?";
        $stmt_is_following = $conn->prepare($is_following_query);
        $stmt_is_following->bind_param("ii", $user_id, $user_id_to_follow);
        $stmt_is_following->execute();
        $is_following_result = $stmt_is_following->get_result();
        $is_following = $is_following_result->num_rows > 0;

        // Display the username and follow button
        echo "<p>$username_to_follow ";
        if ($is_following) {
            echo "(Following)";
        } else {
            echo "<a href='../pages/follow_handler.php?action=follow&target_user_id=$user_id_to_follow'>Follow</a>";
        }
        echo "</p>";
    }
} else {
    echo "<p>No users found.</p>";
}
?>

</body>
</html>

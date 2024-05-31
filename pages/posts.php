<!DOCTYPE html>
<html>
<head>
    <title>User Posts</title>
    <?php include "../includes/header.php"; ?> 

</head>
<body>
    <h1>User Posts</h1>
    <?php
    include 'connection.php';

    // Retrieve user's posts from the database
    $userId = $_SESSION['user_id']; 
    $query = "SELECT * FROM posts WHERE user_id = $userId";
    $result = mysqli_query($conn, $query);

    // Display user's posts
    if (mysqli_num_rows($result) > 0) {
        while ($post = mysqli_fetch_assoc($result)) {
            echo "<div>";
            echo "<h3>" . $post['title'] . "</h3>";
            echo "<p>" . $post['content'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No posts found";
    }
    ?>
</body>
</html>

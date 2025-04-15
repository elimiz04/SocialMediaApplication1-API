<?php
ob_start();
session_start();
include("../includes/connection.php"); 
include("../includes/functions.php");
include("../includes/header.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit;
}

// Set default color mode
if (!isset($_SESSION['color_mode'])) {
    $_SESSION['color_mode'] = 'light';
}
function getColorModeClass() {
    return $_SESSION['color_mode'] === 'light' ? 'light-mode' : 'dark-mode';
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['content'])) {
    $user_id = $_SESSION['user_id'];
    $content = trim($_POST['content']);
    $image = null;

    // Handle image upload
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($image_extension, $allowed_extensions)) {
            echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
            exit;
        }

        $upload_dir = "../assets/";
        $target_file = $upload_dir . uniqid('post_image_') . '.' . $image_extension;

        if (move_uploaded_file($image_tmp, $target_file)) {
            $image = basename($target_file);
        } else {
            echo "Error uploading file.";
            exit;
        }
    }

    // Insert post into database
    $query = "INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $content, $image);
    $stmt->execute();

    header("Location: ../pages/profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="<?php echo getColorModeClass(); ?>">
    <div id="box">
        <h1>Add New Post</h1>
        <div class="post-container">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <textarea name="content" placeholder="What's on your mind?" required></textarea>
                <input type="file" name="image" accept="image/*">
                <button type="submit">Post</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php ob_end_flush(); ?>

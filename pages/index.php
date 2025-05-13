<?php
session_start();

// Default color mode if not set
if (!isset($_SESSION['color_mode'])) {
    $_SESSION['color_mode'] = 'light';
}

function getColorModeClass() {
    return $_SESSION['color_mode'] === 'light' ? 'light-mode' : 'dark-mode';
}

// Fetch a random joke from the Official Joke API
$joke = "Loading joke...";
$curl = curl_init("https://official-joke-api.appspot.com/random_joke");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

if ($response) {
    $joke_data = json_decode($response, true);
    if (isset($joke_data['setup'], $joke_data['punchline'])) {
        $joke = $joke_data['setup'] . " - " . $joke_data['punchline'];
    } else {
        $joke = "No joke available right now.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <?php include("../includes/header.php"); ?>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="<?php echo getColorModeClass(); ?>">
    <div id="box">
        <?php
        include('../includes/connection.php');
        include("../includes/functions.php");

        if (!isset($_SESSION['user_id'])) {
            header("Location: ../pages/login.php");
            die;
        }

        $user_id = $_SESSION['user_id'];
        $user_data = [];

        if (isset($_SESSION['username'])) {
            $user_data['username'] = $_SESSION['username'];
        }

        if (isset($user_data['username'])): ?>
            <h2>Hello, <?php echo htmlspecialchars($user_data['username']); ?></h2>
        <?php endif; ?>

        <h1>Welcome to SocialHive</h1>
        <p>This is a social media platform where you can connect with friends, share your thoughts, and stay updated with what's happening in your community.</p>

        <!-- Display joke from API -->
        <div style="margin-top: 20px; background-color: #eee; padding: 15px; border-radius: 10px;">
            <strong>Here’s a joke for you:</strong><br>
            <?php echo htmlspecialchars($joke); ?>
        </div>
    </div>
</body>
</html>

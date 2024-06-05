<?php
session_start();
$user_id = $_SESSION['user_id']; // Assuming you have a session with user ID

try {
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT theme FROM UserPreferences WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);

    $theme = $stmt->fetchColumn();

    echo json_encode(['status' => 'success', 'theme' => $theme]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

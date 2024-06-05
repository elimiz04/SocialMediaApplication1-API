<?php
session_start();
$user_id = $_SESSION['user_id']; // Assuming you have a session with user ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $theme = $_POST['theme'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO UserPreferences (user_id, theme, updated_at) VALUES (:user_id, :theme, NOW())
                               ON DUPLICATE KEY UPDATE theme = :theme, updated_at = NOW()");
        $stmt->execute(['user_id' => $user_id, 'theme' => $theme]);

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>


### Step 3: Update `update_notification.php` to Mark Notifications as Read

```php
<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];

    // Mark all unread notifications as read for the logged-in user
    $query = "UPDATE Notifications SET is_read = 1 WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    echo "Notifications updated";
}
?>

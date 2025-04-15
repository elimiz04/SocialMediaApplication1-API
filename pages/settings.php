<?php
session_start();
include("../includes/connection.php");
include("../includes/header.php");

// Set default color scheme if not set
if (!isset($_SESSION['color_scheme'])) {
    $_SESSION['color_scheme'] = 'light';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Settings</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="<?php echo $_SESSION['color_scheme']; ?>">

    <div id="box">
        <h1>User Settings</h1>
        <form id="settings-form" action="update_settings.php" method="POST">

            <!-- Color Scheme Section -->
            <section class="section" aria-labelledby="color-scheme">
                <h2 id="color-scheme">Color Scheme</h2>
                <div class="radio-group">
                    <label for="light-mode">
                        <input type="radio" name="color_scheme" value="light" id="light-mode"
                        <?php if ($_SESSION['color_scheme'] === 'light') echo 'checked'; ?>>
                        Light Mode
                    </label>

                    <label for="dark-mode">
                        <input type="radio" name="color_scheme" value="dark" id="dark-mode"
                        <?php if ($_SESSION['color_scheme'] === 'dark') echo 'checked'; ?>>
                        Dark Mode
                    </label>
                </div>
            </section>

            <!-- Notification Preferences Section -->
            <section class="section" aria-labelledby="notifications">
                <h2 id="notifications">Notification Preferences</h2>
                <div class="checkbox-group">
                    <label for="receive_notifications">
                        <input type="checkbox" name="receive_notifications" id="receive_notifications"
                        <?php if (!empty($_SESSION['receive_notifications'])) echo 'checked'; ?>>
                        Receive Notifications
                    </label>
                </div>
            </section>

            <!-- Submit Button -->
            <button type="submit">Save Settings</button>
        </form>
    </div>

    <script>
        document.getElementById('settings-form').addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(this);
            const xhr = new XMLHttpRequest();

            xhr.open('POST', 'update_settings.php', true);
            xhr.onload = function () {
                const response = JSON.parse(xhr.responseText);

                if (response.success) {
                    document.body.className = response.color_scheme;
                    alert('Settings updated successfully!');
                    window.location.href = '../pages/home.php';
                } else {
                    alert('Error updating settings: ' + response.error);
                }
            };

            xhr.send(formData);
        });
    </script>

</body>
</html>

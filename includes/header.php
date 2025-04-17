<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}$color_scheme = isset($_SESSION['color_scheme']) ? $_SESSION['color_scheme'] : 'light';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .fixed-header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        body {
            padding-top: 72px;
        }
    </style>
</head>
<body class="<?php echo $color_scheme; ?>-mode">
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-header">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">SocialHive</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="group.php">Groups</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

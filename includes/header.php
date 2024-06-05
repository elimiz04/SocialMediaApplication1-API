<?php

// Retrieve user settings from the session
$color_scheme = isset($_SESSION['color_scheme']) ? $_SESSION['color_scheme'] : 'light'; // Default to light mode if not set

// Apply color scheme to the body element
echo '<body class="' . $color_scheme . '-mode">';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* Custom CSS to fix the header at the top of the page */
        .fixed-header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000; /* Ensure the header appears above other content */
        }

        /* Add padding to the top of the body to prevent content from being pinned to the header */
        body {
            padding-top: 72px; /* Adjust this value based on the height of your fixed header */
        }
    </style>
</head>
<body>
    <!-- Bootstrap Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-header">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">SocialHive</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto"> 
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <!-- Add a "Groups" button -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"></script>
</body>
</html>

<?php
include("../includes/connection.php"); 
include("../includes/functions.php");
include("../includes/header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Settings</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="light-mode"> 
<body>
    <div id="box">
        <h1>User Settings</h1>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

        /* Light Mode Styles */
    .light-mode {
        background-color: #f8f9fa;
        color: #333;
    }

    /* Dark Mode Styles */
    .dark-mode {
        background-color: #333;
        color: #f8f9fa;
    }

    /* Invert colors for dark mode */
    .dark-mode * {
        filter: invert(100%);
    }

    #box {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h1, h2 {
        text-align: center;
    }

    .btn-container {
        margin-top: 20px;
        text-align: center;
    }

    .minimal-btn, .message-btn {
        padding: 10px 20px;
        background-color: transparent;
        color: #337ab7;
        border: 1px solid #337ab7;
        border-radius: 5px;
        text-decoration: none;
        margin: 0 5px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
    }

    .minimal-btn:hover, .message-btn:hover {
        background-color: #337ab7;
        color: white;
        border-color: #337ab7;
    }

    .settings-form {
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="checkbox"] {
        margin-right: 5px;
    }
    .settings-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .form-group label {
        margin-left: 8px;
    }





</style>

    

    <main>
        <h2>Color Scheme</h2>
        <label for="light-mode">Light Mode</label>
        <input type="radio" name="color-scheme" id="light-mode" checked>
        <label for="dark-mode">Dark Mode</label>
        <input type="radio" name="color-scheme" id="dark-mode">
    </main>

    <script>
        const lightModeRadio = document.getElementById('light-mode');
        const darkModeRadio = document.getElementById('dark-mode');
        const body = document.body;

        lightModeRadio.addEventListener('change', () => {
            body.classList.remove('dark-mode');
            body.classList.add('light-mode');
        });

        darkModeRadio.addEventListener('change', () => {
            body.classList.remove('light-mode');
            body.classList.add('dark-mode');
        });
    </script>


        <div class="form-group">
            <input type="checkbox" name="receive_notifications" id="receive_notifications">
            <label for="receive_notifications">Receive Notifications</label>
        </div>

                <button type="submit" class="minimal-btn">Save Settings</button>
            

    <script src="script.js"></script>
</body>
</html>

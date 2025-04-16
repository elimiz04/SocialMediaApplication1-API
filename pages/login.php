<?php
session_start();

include("../includes/connection.php");

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Use a prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user_data = $result->fetch_assoc();

            // Check if user is blocked
            if (isset($user_data['status']) && $user_data['status'] === 'blocked') {
                $error_message = "Your account has been blocked. Please contact support.";
            } elseif ($user_data['password'] === $password) {
                $_SESSION['user_id'] = $user_data['user_id'];

                // Check if the user is an admin
                if ($user_data['username'] === 'asd' && $user_data['password'] === '123') {
                    header("Location: admin/admin_dashboard.php");
                    die;
                } else {
                    header("Location: ../pages/index.php");
                    die;
                }
            } else {
                $error_message = "Wrong username or password!";
            }
        } else {
            $error_message = "Wrong username or password!";
        }
        $stmt->close();
    } else {
        $error_message = "Please enter some valid information!";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="js/error.js"></script>
    <style type="text/css"> 
        /* CSS styling for form elements */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0; /* Background color for the entire page */
        }
        #text {
            height: 25px;
            border-radius: 5px;
            padding: 4px;
            border: solid thin #aaa;
            width: 100%;
        }
        #button {
            padding: 12px;
            width: 120px;
            font-size: 16px;
            color: white;
            background-color: #337ab7; /* Darker button color */
            border: none;
            transition: background-color 0.3s ease; /* Smooth transition on hover */
        }
        #button:hover {
            background-color: #286090; /* Slightly darker color on hover */
        }
        #box {
            background-color: grey;
            width: 400px;
            padding: 40px;
            text-align: center;
        }
        #signup-link {
            font-size: 16px;
            color: white;
            text-align: center;
            display:block;
            margin-top: 20px;
        }
        .form-label {
            font-size: 16px;
            text-align: left;
            font-weight: bold;
            margin-bottom: 10px;
            color: white;
            display: block;
        }
    </style>
  <script>
    <?php if(!empty($error_message)): ?>
    alert("<?php echo addslashes($error_message); ?>");
    <?php endif; ?>
</script>

</head>
<body>
    <div id="box">
        <form method="post">
            <div style="font-size: 24px; margin: 20px; color: white;text-align: center;">Login</div>
            <label for="username" class="form-label">Username:</label>
            <input id="text" type="text" name="username"> <br><br>
            <label for="password" class="form-label">Password:</label>
            <input id="text" type="password" name="password"><br><br>
            <input id="button" type="submit" value="Login"><br><br>
            <a href="signup.php" style="color: white;">Click to Sign up</a><br><br>
        </form>
    </div>
</body>
</html>

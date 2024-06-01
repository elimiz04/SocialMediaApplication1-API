<?php
    session_start();

        include("../includes/connection.php");

        $error_message ="";

        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
            //something was posted
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if(!empty($username) && !empty($email) && !empty($password))
            {

                //save to database
                $user_id = random_num(20);
                $query = "INSERT INTO users (user_id, username, email, password) VALUES ('$user_id', '$username','$email', '$password')";

               if(mysqli_query($conn, $query)){

               header("Location: login.php");
               die;
        }else{
            $error_message = "Database query failed!";
        }
    }else{
        $error_message = "Please enter some valid information!"; 
    }
}

function random_num($length){
    $text = "";
    if($length <5){
        $length = 5;
    }

    $len = rand(4, $length);

    for($i = 0; $i< $le; $i++){
        $text .= rand (0,9);
    }

    return $text; 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign up</title>
</head>
<body>
    <style type= "text/css"> 
        
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
        function showError(message){
            alert(message);
        }
    </script>

    <div id = "box">
        <form method="post">

            <div style=" font-size: 24px;margin: 20px;color: white; text-align: center;">Sign up</div>
            <label for="username" class="form-label">Username:</label>
            <input id="text" type="text" name="username"> <br><br>
            <label for="email" class="form-label">Email:</label>
            <input id="text" type="email" name="email"> <br><br>
            <label for="password" class="form-label">Password:</label>
            <input id="text" type="password" name="password"><br><br>

            <input id="button" type="submit" value="Signup"><br><br>

            <a href="login.php" style="color: white";>Click to Login</a><br><br>
        </form>
    </div>

    <?php
    if(!empty($error_message)){
        echo "<script type='text/javascript'>showError('$error_message');</script>";
    }
    ?>
</body>
</html>
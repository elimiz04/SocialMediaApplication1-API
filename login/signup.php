<?php
    session_start();

        include("connection.php");
        include("functions.php");

        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
            //something was posted
            $user_name = $_POST['user_name'];
            $password = $_POST['password'];

            if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
            {

                //save to database
                $user_id = random_num(20);
                $query = "insert into users (user_id, user_name, password) values ('$user_id', '$user_name', '$password')";

                mysqli_query($con, $query);

               header("Location: login.php");
               die;
            }
            else
            {
                echo"Please enter some valid information!";
            }
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
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

    <div id = "box">
        <form method="post">

            <div style=" font-size: 24px;margin: 20px;color: white; text-align: center;">Signup</div>
            <label for="user_name" class="form-label">Username:</label>
            <input id="text" type="text" name="user_name"> <br><br>
            <label for="password" class="form-label">Password:</label>
            <input id="text" type="password" name="password"><br><br>

            <input id="button" type="submit" value="Signup"><br><br>

            <a href="login.php" style="color: white";>Click to Login</a><br><br>
        </form>
    </div>
</body>
</html>
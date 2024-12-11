<?php
if (!function_exists('check_login')) {
    function check_login($con)
    {
        if(isset($_SESSION['user_id']))
        {
            $id = $_SESSION['user_id'];
            $query = "SELECT * FROM users WHERE user_id = '$id' LIMIT 1"; 

            $result = mysqli_query($con, $query);
            if($result && mysqli_num_rows($result) > 0)
            {
                $user_data = mysqli_fetch_assoc($result);
                return $user_data; 
            }
        }

        // Redirect to login
        header("Location: login.php");
        die; 
    }
}

if (!function_exists('random_num')) {
    function random_num($length)
    {

        $text="";
        if ($length < 5)
        {
            $length = 5;
        }

        $len = rand(4,$length);

        for($i=0; $i < $len; $i++){

            $text.= rand(0,9); 
        }

        return $text;
    }
}
// Function to establish database connection
function getConnection() {
    // Implement database connection logic
}
// In functions.php or an appropriate file

function get_user_data($conn, $user_id) {
    // Prepare the query to fetch user data
    $query = "SELECT username, email FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id); // Bind the user_id parameter to the query
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Fetch the user data
        return $result->fetch_assoc();  // Return the user data as an associative array
    } else {
        return null;  
    }
    
    $stmt->close(); 
}

?>
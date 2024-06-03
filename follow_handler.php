<?php
session_start();
include("../includes/connection.php");

// Check if the user is logged in
if(isset($_SESSION['user_id'])){
    // Get the current user's ID
    $follower_id = $_SESSION['user_id'];

    // Get the ID of the user to follow
    if(isset($_POST['user_id'])){
        $following_id = $_POST['user_id'];

        // Check if the user is not already following
        $query_check = "SELECT * FROM follows WHERE follower_id = ? AND following_id = ?";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bind_param("ii", $follower_id, $following_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if($result_check->num_rows == 0){
            // Insert a new follow record into the database
            $query_insert = "INSERT INTO follows (follower_id, following_id) VALUES (?, ?)";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bind_param("ii", $follower_id, $following_id);
            if($stmt_insert->execute()){
                // Success
                echo json_encode(array("success" => true));
                exit;
            }
        }
    }
}

// Error or failure
echo json_encode(array("success" => false));
?>

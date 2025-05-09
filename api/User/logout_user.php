<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Simulate logout by returning a success message
http_response_code(200);
echo json_encode(["message" => "User logged out successfully."]);

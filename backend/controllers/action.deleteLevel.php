<?php
include_once '../config/cors.php'; // Include the CORS configuration file
include_once '../connection.php';
include_once '../models/class.level.php';

$conn = Connection::getConnection(); // Get the database connection

$data = json_decode(file_get_contents("php://input")); // Get the JSON data from the request body
$levelId = $data->levelId ?? null; // Get the level ID from the request data or set it to null if not provided

$level = new Level(); // Create a new Level object

if($level->delete($levelId)){
    echo json_encode([
        "success" => true,
        "message" => "Level deleted successfully."
    ]);  
}exit;
?>
<?php
include_once "../config/cors.php"; // Include the CORS configuration file
$data = json_decode(file_get_contents("php://input")); // Get the JSON data from the request body
$optionId = $data->optionId ?? null; // Get the option ID from the request data or set it to null if not provided
// Include the database connection file
include_once "../connection.php"; // Include the database connection file
$conn = Connection::getConnection(); // Get the database connection
// Include the Option model class
include_once "../models/class.option.php"; // Include the Option model class
// Create option object
$option = new Option();
if($option->delete($optionId)){
    echo json_encode([
        "success" => true,
        "message" => "Option deleted successfully."
    ]);
}exit;
?>
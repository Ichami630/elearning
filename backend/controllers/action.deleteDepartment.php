<?php
    include_once '../config/cors.php'; // Include the CORS configuration file
    include_once '../connection.php'; // Include the database connection file
    include_once '../models/class.department.php'; // Include the Department model class
    $conn = Connection::getConnection(); // Get the database connection

    $data = json_decode(file_get_contents("php://input")); // Get the JSON data from the request body
    $id = $data->id ?? null; // Get the ID from the request data or set it to null if not provided
    $dept = new Department(); // Create a new Department object
    if($dept->delete($id)){
        echo json_encode([
            "success" => true,
            "message" => "Department deleted successfully."
        ]);
    }exit; // Call the delete method to delete the department
?>
<?php 
include_once '../config/cors.php'; // Include the CORS configuration file

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once '../connection.php'; // Include the database connection file
    include_once '../models/class.option.php'; // Include the Option model class
    $conn = Connection::getConnection(); // Get the database connection

    $data = json_decode(file_get_contents("php://input")); // Get the JSON data from the request body
    $departmentId = $data->departmentId ?? null; // Get the department ID from the request data or set it to null if not provided
    $name = $data->name ?? null; // Get the name from the request data or set it to null if not provided

    // Create option object
    $option = new Option();
    $option->setDepartmentId($departmentId); // Set the department ID property of the Option object
    $option->setName(trim($name)); // Set the name property of the Option object

   //check if that option already exist
    if (!$option->isOptionNameTaken($name)){
        // Check if the option is created successfully
        if($option->insert()){
            echo json_encode([
                "success" => true,
                "message" => "Option created successfully."
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Option not created."
            ]);
        }
    }else{
        echo json_encode([
            "success" => false,
            "message" => "Option already exists."
        ]);
        
    }exit;
}
?>
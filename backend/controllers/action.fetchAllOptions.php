<?php
    include_once '../config/cors.php'; // Include the CORS configuration file

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        include_once '../connection.php'; // Include the database connection file
        include_once '../models/class.option.php'; // Include the Option model class
        include_once '../models/class.department.php'; // Include the Department model class
        $conn = Connection::getConnection(); // Get the database connection

        $data = json_decode(file_get_contents("php://input")); // Get the JSON data from the request body
        $departmentId = $data->departmentId ?? null; // Get the department ID from the request data or set it to null if not provided

        // Create department object
        $department = new Department();

        // Create option object
        $option = new Option();

        // Fetch all options for the given department ID
        $options = $option->getAllOptions();

        if ($options) {
            echo json_encode([
                "success" => true,
                "options" => $options
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No options found."
            ]);
        }
    }
?>
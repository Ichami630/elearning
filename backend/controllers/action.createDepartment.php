<?php
    include_once '../config/cors.php'; // Include the CORS configuration file

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include_once '../connection.php'; // Include the database connection file
        include_once '../models/class.department.php'; // Include the Department model class
        $conn = Connection::getConnection(); // Get the database connection

        $data = json_decode(file_get_contents("php://input")); // Get the JSON data from the request body
        $name = $data->name ?? null; // Get the name from the request data or set it to null if not provided
        $description = $data->description ?? null; // Get the description from the request data or set it to null if not provided

        // Create department object
        $department = new Department();
        $department->setName(trim($name));
        $department->setDescription(trim($description));

        //check if that department already exist
        if (!$department->isDepartmentNameTaken($name)){
            // Check if the department is created successfully
            if($department->insert()){
                echo json_encode([
                    "success" => true,
                    "message" => "Department created successfully."
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Department not created."
                ]);
            }
        }else{
            echo json_encode([
                "success" => false,
                "message" => "Department already exists."
            ]);
            
        }exit;
    }
?>
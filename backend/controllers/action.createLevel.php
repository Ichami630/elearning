<?php
    include_once '../config/cors.php'; // Include the CORS configuration file

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include_once '../connection.php'; // Include the database connection file
        include_once '../models/class.level.php'; // Include the Option model class
        $conn = Connection::getConnection(); // Get the database connection

        $data = json_decode(file_get_contents("php://input")); // Get the JSON data from the request body
        $name = $data->name ?? null; // Get the name from the request data or set it to null if not provided
        $description = $data->description ?? null; // Get the description from the request data or set it to null if not provided


        // Create option object
        $level = new Level();
        $level->setName(trim($name)); // Set the name property of the Option object
        $level->setDescription(trim($description)); // Set the description property of the Option object

        //check if that level already exist
        if(!$level->isLevelNameTaken($name)){
            // Check if the level is created successfully
            if($level->insert()){
                echo json_encode([
                    "success" => true,
                    "message" => "Level created successfully."
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Level not created."
                ]);
            }
        }

    }
    exit; // Exit the script after processing the request
?>
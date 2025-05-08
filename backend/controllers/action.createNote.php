<?php
// Allow cross-origin requests
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST,GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-control-Allow-Credentials: true");

include_once '../connection.php'; // Include the database connection file
include_once '../models/class.module.php'; // Include the Course model class
$conn = Connection::getConnection(); // Get the database connection

if($_SERVER['REQUEST_METHOD'] =='POST'){
    $data = json_decode(file_get_contents("php://input"));

    $courseOfferingId = $data->courseOfferingId ?? null; // Get the course offering ID from the request data or set it to null if not provided
    $title = $data->title ?? null; // Get the title from the request data or set it to null if not provided
    $description = $data->description ?? null; // Get the description from the request data or set it to null if not provided
    $module = new Module(); // Create a new Module object
    $module->setCourseOfferingId($courseOfferingId); // Set the course offering ID property of the Module object
    $module->setTitle($title); // Set the title property of the Module object
    $module->setDescription($description); // Set the description property of the Module object
    if($module->insert()){
         echo json_encode(['status' => 'success', 'message' => 'New module created successfully.']);
    }else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert module.']);
    }exit;

}
?>
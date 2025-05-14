<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

include_once '../connection.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    include_once '../connection.php'; // Include the database connection file
    include_once '../models/class.department.php'; // Include the Department model class
    $conn = Connection::getConnection(); // Get the database connection

    $department = new Department(); // Create a new Department object
    $departmentResult = $department->getAllDepartments(); // Fetch all departments from the database

    $departments = []; // Initialize an empty array to store department data
    if($departmentResult && $departmentResult->num_rows > 0){
        while($row = $departmentResult->fetch_object()){
            $departments[] = [
                "id" => $row->id,
                "name" => $row->name
            ];
        }

        echo json_encode([
            "success" => true,
            "departments" => $departments // Return the array of departments
        ]);
    }else{
        echo json_encode([
            "success" => false,
            "message" => "No departments found."
        ]);}
} else {
    echo json_encode([
        "success" => false,
        "message" => "internal server error."
    ]);
}exit;
?>
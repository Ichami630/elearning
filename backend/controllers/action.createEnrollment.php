<?php
// Allow cross-origin requests
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST,GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-control-Allow-Credentials: true");

include_once '../connection.php'; // Include the database connection file
include_once '../models/class.enrollment.php'; // Include the Course model class
$conn = Connection::getConnection(); // Get the database connection

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = json_decode(file_get_contents("php://input"));

    $studentId = $data->studentId ?? null; // Get the student ID from the request data or set it to null if not provided
    $courseOfferingId = $data->courseOfferingId ?? null; // Get the course offering ID from the request data or set it to null if not provided

    // Create a new Enrollment object
    $enrollment = new Enrollment(); 
    $enrollment->setStudentId($studentId); // Set the student ID property of the Enrollment object
    $enrollment->setCourseOfferingId($courseOfferingId); // Set the course offering ID property of the Enrollment object

    // Check if the student is already enrolled in the course offering
    if($enrollment->isEnrolled()){
        echo json_encode(['status' => 'error', 'message' => 'You are already enrolled in this course.']);
        exit;
    } else {
        // Insert the enrollment into the database
        if($enrollment->insert()){
            echo json_encode(['status' => 'success', 'message' => 'You have successfully enrolled to the course.']);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create enrollment.']);
            exit;
        }
    }
}
?>
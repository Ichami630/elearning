<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

include_once '../connection.php';
include_once '../models/class.student.php';

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $student = new Student();

    //get all the student in the platform
    $students = $student->allStudents();
    echo json_encode([
        'success'=> true,
        'students'=>$students
    ]);exit;
}
?>
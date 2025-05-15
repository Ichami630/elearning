<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

include_once '../connection.php';
include_once '../models/class.student.php';
include_once '../models/class.course.php';

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $student = new Student();
    $course = new Course();

    $courseId = $_GET['courseId'] ?? null;

    //get all students enrolled in a course
    if(isset($courseId) && !empty($courseId)){
        $participants = $course->getCourseEnroledStudents((int)$courseId);
        echo json_encode([
            'success'=> true,
            'partcipants'=>$participants
        ]);exit;
    }


    //get all the student in the platform
    $students = $student->allStudents();
    echo json_encode([
        'success'=> true,
        'students'=>$students
    ]);exit;
}
?>
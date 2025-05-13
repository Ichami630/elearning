<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

include_once '../connection.php';
include_once '../models/class.enrollment.php';

$conn = Connection::getConnection();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = json_decode(file_get_contents("php://input"));

    $studentId = $data->studentId;
    $courseOfferingId = $data->course;

    //check if the student is already enrolled for that course
    $enrollment = new Enrollment();
    $enrollment->setStudentId((int)$studentId);
    $enrollment->setCourseOfferingId((int)$courseOfferingId);
    if($enrollment->isEnrolled()){
        echo json_encode([
            'success'=>false,
            'message'=>"You are already enrolled in this course"
        ]);exit;
    }else{
        //enroll the student to the course
        if($enrollment->insert()){
            echo json_encode([
                'success'=>true,
            ]);exit;
        }else{
            echo json_encode([
                'success'=>false,
                'message'=>'failed to enroll,please contact the admin'
            ]);exit;
        }
    }
}else{
    echo json_encode([
        'success'=>false,
        'message'=>'invalid request'
    ]);exit;
}
?>
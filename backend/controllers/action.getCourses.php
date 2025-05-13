<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

include_once '../connection.php';
include_once '../models/class.course.php';

$conn = Connection::getConnection();

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $course = new Course();

    $id = $_GET['id'] ?? null;
    $role = $_GET['role'] ?? null;
    $semester = $_GET['semester'] ?? null;

    $courses = [];

    if (isset($role) || isset($semester)) {
        if ($role === 'admin') {
            $courses = $course->getAllCourseNameThumbnail();
        } else if ($role === 'lecturer') {
            $courses = $course->getAllCourseOfLecturer((int)$id);
        } else if ($role === 'student') {
            $courses = $course->getAllStudentEnrolledCourses((int)$id);
        }else if($semester === 'First' || $semester === 'Second'){
            $courses = $course->getAllcourseNotEnrolled($id,$semester);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "invalid request"
            ]);
            exit;
        }

        echo json_encode([
            "success" => true,
            "courses" => $courses
        ]);
        exit;
    } else {
        echo json_encode([
            "success" => false,
            "message" => "role not set"
        ]);
        exit;
    }
}

?>
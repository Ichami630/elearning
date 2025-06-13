<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

include_once("../models/class.user.php");
include_once("../models/class.course.php");
include_once("../models/class.enrollment.php");
include_once("../models/class.assignment.php");
include_once("../models/class.quiz.php");
$u = new User();
$co = new CourseOfferings();
$e = new Enrollment();
$a = new Assignment();
$q = new Quiz();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = json_decode(file_get_contents("php://input"));
    $id = $data->id ?? null;
    $role = $data->role ?? null;

    $u->select((int)$id);
    $name = $u->getName();
    $email = $u->getEmail();
    $deptId = $u->getDepartmentId();

    //get the other details of the student
    if($role === 'student'){
        $deptLevel = $co->getDeptLevel((int)$deptId);
        $department = $deptLevel[0]["dept"];
        $level = $deptLevel[0]["level"];
        $enrolledCourses = $e->getEnrolledCoursesExtended((int)$id);
        $pendingAssignments = $a->getPendingAssignments((int)$id);
        $pendingQuiz = $q->getPendingQizzes((int)$id);
        echo json_encode([
            "success" => true,
            "name" => $name,
            "email" => $email,
            "department" => $department,
            "level" => $level,
            "enrollment" => $enrolledCourses,
            "pendingAssignments" => $pendingAssignments,
            "pendingQuizzes" => $pendingQuiz
        ]);exit;
    }
}
?>
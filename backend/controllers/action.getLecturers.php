<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    
    include_once '../connection.php';
    include_once '../models/class.teacher.php';

    $conn = Connection::getConnection();

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $lecturer = new Teacher();
        $role  = $_GET['role'] ?? null;
        $id = $_GET['id'] ?? null;

        $lecturers = [];

        if($role === 'student'){
            $lecturers = $lecturer->getAllStudentLecturers((int)$id);
        }else if ($role === "admin" || $role === 'lecturer'){
            $lecturers = $lecturer->getAllLecturers();
        }else{
            echo json_encode([
                "success"=> false,
                "message"=> "invalid request"
            ]);exit;
        }

        echo json_encode([
            "success"=>true,
            "lecturers"=>$lecturers
        ]);exit;
    }
?>
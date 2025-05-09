<?php
    // Allow cross-origin requests

    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST,GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-control-Allow-Credentials: true");

    include_once '../connection.php';
    include_once '../models/class.user.php';
    $conn = Connection::getConnection();

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $user = new User();
        $totalAdmins = $user->selectCount('admin');
        $totalLecturers = $user->selectCount('lecturer');   
        $totalStudents = $user->selectCount('student');

        echo json_encode([
            'status' => true,
            'totalAdmins' => $totalAdmins,
            'totalLecturers' => $totalLecturers,
            'totalStudents' => $totalStudents
        ]);
    }
?>
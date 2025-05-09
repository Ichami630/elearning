<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST,GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-control-Allow-Credentials: true");

    include_once '../connection.php';
    include_once '../models/class.announcement.php';
    $conn = Connection::getConnection();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $data = json_decode(file_get_contents("php://input"));

        $courseOfferingId = $data->courseOfferingId ?? null;
        $title = $data->title ?? null;
        $message = $data->message ?? null;

        $announcement = new Announcement();
        $announcement->setCourseOfferingId($courseOfferingId);
        $announcement->setTitle($title);
        $announcement->setMessage($message);
        if(!isset($title) && !isset($message)){
            echo json_encode([
                'success'=> false,
                'message'=> 'title and message cannot be empty'
            ]);
            exit;
        }
        if($announcement->insert()){
            echo json_encode([
                'status'=> true,
                'message'=> 'Announcement created successfully'
            ]);exit;
        }
    }
?>
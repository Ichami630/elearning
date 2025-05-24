<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

    include_once("../models/class.submission.php");
    include_once '../models/class.fileUpload.php';
    $submission = new Submission();

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        //get all the submissions for a particular assignment
        $assignmentId = $_GET['assignmentId'] ?? null;
        if(isset($assignmentId)){
            $submissions = $submission->getSubmissionsByAssignmentId((int)$assignmentId);
            echo json_encode([
                'success' => true,
                'submissions' => $submissions
            ]);exit;
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $submission->setAssignmentId((int)$_POST['assignmentId'] ?? null);
        $submission->setStudentId((int)$_POST['studentId'] ?? null);
        $submission->setTitle($_POST['title'] ?? null);
        $submission->setMessage($_POST['message'] ?? null);
        if(!isset($_FILES['file']) && !isset($_POST['message'])){
            echo json_encode([
                "success"=>false,
                "message"=>"message or file cannot be empty"
            ]);exit;
        }else{
            if(isset($_FILES['file'])){
                $uploader = new FileUploader();
                $uploadDir = "../../frontend/public/submissions";
                $uploadResult = $uploader->upload($_FILES['file'], $uploadDir);
                if (!$uploadResult['success']) {
                    echo json_encode(['success' => false, 'message' => $uploadResult['message']]);
                    exit;
                }
                $submission->setFileUrl($uploadResult['filename']);
            }
            //check if the student has already submitted this assignment
            if($submission->hasSubmitted((int)$_POST['assignmentId'], (int)$_POST['studentId'])){
                echo json_encode([
                    'success'=>false,
                    'message'=>'You have already submitted this assignment,you cannot submit an assignment twice'
                ]);exit;
            }
            //now proceed to submit the assignment
            if($submission->insert()){
                echo json_encode([
                    'success'=>true,
                    'message'=>'Submission created successfully'
                ]);exit;
            }else{
                echo json_encode([
                    'success'=>false,
                    'message'=>'Opps, something went wrong while creating the submission, contact admin'
                ]);exit; 
            }
        }

    }
?>
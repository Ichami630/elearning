<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

    include_once("../models/class.submission.php");
    include_once '../models/class.fileUpload.php';
    include_once("../services/class.phpmailer.php");
    $submission = new Submission();
    $mailer = new Mailer();

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
            //check if the student has already submitted this assignment
            if($submission->hasSubmitted((int)$_POST['assignmentId'], (int)$_POST['studentId'])){
                echo json_encode([
                    'success'=>false,
                    'message'=>'You have already submitted this assignment,you cannot submit an assignment twice'
                ]);exit;
            }
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
            //now proceed to submit the assignment
            if($submission->insert()){
                //get the name of the lecturer
                $lecturer = $submission->getLecturerNameByAssId((int)$_POST['assignmentId']);
                $studentName = trim($_POST['studentName']) ?? 'Unknown Student';
                //now we process the email and send to the lecturer
                $subject = "New Assignment Submission: " . $submission->getTitle();
                $body = "Dear ".$lecturer.",<br> A new assignment has been submitted by student ".$studentName."<br><br>";

                //if the submission has a file, attach it to the email
                if($submission->getFileUrl()){
                    $filePath = realpath($uploadDir . '/' . $submission->getFileUrl());
                    $body .="Best Regards";
                    if (!$mailer->sendWithAttachment("brandonichami630@gmail.com",$subject,$body,$filePath)){
                        echo json_encode([
                            'success'=>false,
                            'message'=>'Submission created successfully, but failed to send email notification'
                        ]);exit;
                    }
                }else{
                    $message = $_POST['message'] ?? 'No message content.';
                    $body .= "<br><br>Message from student:<br><i>{$message}</i><br><br>Best Regards";
                    if (!$mailer->send("brandonichami630@gmail.com",$subject,$body)){
                        echo json_encode([
                            'success'=>false,
                            'message'=>'Submission created successfully, but failed to send email notification'
                        ]);exit;
                    }
                }
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
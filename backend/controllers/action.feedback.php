<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

    include_once("../models/class.submission.php");
    $submission = new Submission();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $submissionId = $_POST['submissionId'] ?? null;
        $feedback = $_POST['feedback'] ?? null;
        $grade = $_POST['grade'] ?? null;

        if (isset($submissionId) && isset($feedback) && isset($grade)) {
            $submission->setId((int)$submissionId);
            $submission->setFeedback($feedback);
            $submission->setGrade((float)$grade);

            if ($submission->update()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Feedback and grade updated successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update feedback and grade'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid input data'
            ]);
        }
    } 
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $submissionId = $_GET['submissionId'] ?? null;
        if(isset($submissionId)){
            $submissionData = $submission->getGradeAndFeedback((int)$submissionId);
            echo json_encode([
                'success'=>true,
                'submission' => $submissionData
            ]);exit;
        }exit;
    }
?>
<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

     include_once("../models/class.quiz.php");

     if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $quiz = new Quiz();
        $studentId = $_GET['id'];
        $role = $_GET['role'];

        if($role === 'student' && isset($studentId)){
            $quizzes = $quiz->getAttemptedQuiz((int)$studentId);
            echo json_encode([
                'success'=> true,
                'attemptedQuizzes'=>$quiz->getAttemptedQuiz((int)$studentId)
            ]);exit;
        }
     }
?>
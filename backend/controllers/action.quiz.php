<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

    include_once("../connection.php");
    include_once("../models/class.quiz.php");
    include_once("../models/class.course.php");
    $conn = Connection::getConnection();
    $quiz = new Quiz();
    $course = new Course();
    $q = new Questions();
    $r = new Result();

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $id = $_GET['id'];
        $questions = $q->getQuestions((int)$id);
        $quiz->getQuiz((int)$id);
        echo json_encode([
            "success"=>true,
            "title"=>$quiz->getTitle(),
            "totalMarks"=>$quiz->getTotalMarks(),
            "duration"=>$quiz->getDuration(),
            "questions"=>$questions
        ]);exit;
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $data = json_decode(file_get_contents("php://input"));

        //get the studentid and quizid and insert into the quiz result table
        $r->setStudentId((int)$data->studentId);
        $r->setQuizId((int)$data->quizId);
        $r->setScore(sprintf("%.2f",$data->score));
        if(isset($data->studentId) && isset($data->quizId)){
            if($r->insert()){
                echo json_encode([
                    'success'=> true,
                    'message'=>'Congratulations,now your results awaits you'
                ]);exit;
            }else{
                echo json_encode([
                    'success'=> false,
                    'message'=>'Opps something when wrong, please you have to retake this quiz'
                ]);exit;
            }
        }
        //get the course_offering id from the courseid
        $courseOfferingId = $course->getCourseOfferingId($data->courseId);
        if($courseOfferingId !== 0){
            $quiz->setCourseOfferingId((int)$courseOfferingId);
            $quiz->setTitle($data->title);
            $quiz->setTotalMarks((int)$data->totalMarks);
            $quiz->setDuration((int)$data->duration);
            if($quiz->insert()){
                $questions = $data->questions;
                foreach($questions as $question){
                    $q->setQuizId($quiz->getLastInsertId());
                    $q->setQuestion($question->question);
                    $q->setOptionA($question->optionA);
                    $q->setOptionB($question->optionB);
                    $q->setOptionC($question->optionC);
                    $q->setOptionD($question->optionD);
                    $q->setCorrectOption($question->correct);
                    if(!$q->insert()){
                        echo json_encode([
                            'success'=> false,
                            'message'=>'failed to insert question'
                        ]);exit;
                    }
                }
                echo json_encode([
                    'success'=> true,
                    'message'=>'quiz created successfully'
                ]);exit;
        }

    }
    }
?>
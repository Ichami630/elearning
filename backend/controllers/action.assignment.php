<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

    include_once("../models/class.assignment.php");
    include_once("../connection.php");
    include_once("../models/class.course.php");
    $assignment = new Assignment();
    $course = new Course();
    $conn = Connection::getConnection();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $courseId = $_POST['courseId'] ?? null;
        $title = $_POST['title'] ?? null;
        $description = $_POST['noteContent'] ?? null;
        $dueDate = $_POST['dueDate'] ?? null;

        //get the courseoffering from the course id
        $courseOfferingId = $course->getCourseOfferingId((int)$courseId);
        if($courseOfferingId !== 0){
            $assignment->setCourseOfferingId((int)$courseOfferingId);
            $assignment->setTitle($title);
            $assignment->setDescription($description);
            $assignment->setDueDate($dueDate);
            //insert into the assignment table
            if($assignment->insert()){
                echo json_encode([
                    'success'=>true,
                    'message'=>'New assignment created successfully'
                ]);exit;
            }else{
               echo json_encode([
                    'success'=>false,
                    'message'=>'Opps, something when wrong while creating the assignment,contact admin'
                ]);exit; 
            }
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $courseId = $_GET['courseId'] ?? null;
        if(isset($courseId)){
            $assignments = $assignment->getCourseAssignments((int)$courseId);
            echo json_encode([
                'success'=>true,
                'assignments'=>$assignments
            ]);exit;
        }

        //get the assignment title and description by id
        $assignmentId = $_GET['assignmentId'] ?? null;
        if(isset($assignmentId)){
            $data = $assignment->getAssignment((int)$assignmentId);
            echo json_encode([
            'success' => true,
            'id' => $data['id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'dueDate' => $data['due_date']
            ]);exit;
        }
    }
?>
<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

    include_once("../models/class.assignment.php");
    include_once("../connection.php");
    include_once("../models/class.course.php");
    include_once("../services/class.phpmailer.php");
    $assignment = new Assignment();
    $course = new Course();
    $mailer = new Mailer();
    $conn = Connection::getConnection();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $courseId = $_POST['courseId'] ?? null;
        $title = $_POST['title'] ?? null;
        $description = $_POST['noteContent'] ?? null;
        $dueDate = $_POST['dueDate'] ?? null;

        //get the courseoffering from the course id
        $courseOfferingId = $course->getCourseOfferingId((int)$courseId);
        $course->select((int)$courseId);
        $courseTitle = $course->getTitle();
        if($courseOfferingId !== 0){
            $assignment->setCourseOfferingId((int)$courseOfferingId);
            $assignment->setTitle($title);
            $assignment->setDescription($description);
            $assignment->setDueDate($dueDate);
            //insert into the assignment table
            if($assignment->insert()){
                //get an array of the students email studying  that course
                // $studentEmails = $course->getCourseStudentEmail((int)$courseOfferingId);
                $studentEmails = [
                    ['name'=>'Brandon','email'=>'brandonichami630@gmail.com'],
                    ['name'=>'Precious','email'=>'kedjuprecious@gmail.com']
                ];
                foreach($studentEmails as $email){
                    $body = "
                    <h1>Dear {$email['name']},</h1>
                    <p>We are pleased to inform you that a new assignment has been created for the course <strong>{$courseTitle}</strong>.</p>
                    <p><strong>Assignment Title:</strong> {$title}</p>
                    <p><strong>Due Date: </strong> {$dueDate}</p>
                    <p>Do well to submit your assignment on time </p>
                    ";
                    $sent = $mailer->send($email['email'], 'Student Quiz Submission', $body);
                }
                //now let send email to all the students tto notify of a assignment
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

        //get student assignments
        $studentId = $_GET['studentId'] ?? null;
        if(isset($studentId)){
            $assignments = $assignment->getStudentAssignments((int)$studentId);
            echo json_encode([
                'success'=>true,
                'assignments'=>$assignments
            ]);exit;
        }
    }
?>
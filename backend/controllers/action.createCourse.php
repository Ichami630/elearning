<?php
// Allow cross-origin requests
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST,GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-control-Allow-Credentials: true");

include_once '../connection.php'; // Include the database connection file
include_once '../models/class.course.php'; // Include the Course model class
$conn = Connection::getConnection(); // Get the database connection

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = json_decode(file_get_contents("php://input"));

    $title = $data->title ?? null; // Get the title from the request data or set it to null if not provided
    $code = $data->code ?? null; // Get the code from the request data or set it to null if not provided
    $courseType = $data->courseType ?? null; // Get the course type from the request data or set it to null if not provided

    $courseId = null; // Initialize the variable without type declaration
    $departmentId = $data->departmentId ?? null; // Get the department ID from the request data or set it to null if not provided
    $optionId = $data->optionId ?? null; // Get the option ID from the request data or set it to null if not provided
    $levelId = $data->levelId ?? null; // Get the level ID from the request data or set it to null if not provided
    $academicYear = $data->academicYear ?? null; // Get the academic year from the request data or set it to null if not provided
    $semester = $data->semester ?? null; // Get the semester from the request data or set it to null if not provided
    $instructorId = $data->instructorId ?? null; // Get the instructor ID from the request data or set it to null if not provided


    //first insert into the course table and then get the last insert id
    $course = new Course(); // Create a new Course object
    $course->setTitle($title); // Set the title property of the Course object
    $course->setCode($code); // Set the code property of the Course object
    $course->setCourseType($courseType); // Set the course type property of the Course object

    //check if the coursecode is already taken
    if($course->isCodeTaken()){
        echo json_encode([
            'success'=> false,
            'da'=>$data,
            'message' => 'course code already assign'
        ]);exit;
    }
    if($course->insert()){
        $courseId = $course->getLastInsertId(); // Get the last inserted course ID

        // now insert into the course offerings table
        $courseOfferings = new CourseOfferings(); // Create a new CourseOfferings object
        $courseOfferings->setCourseId($courseId); // Set the course ID property of the CourseOfferings object
        $courseOfferings->setDepartmentId($departmentId); // Set the department ID property of the CourseOfferings object
        $courseOfferings->setOptionId($optionId); // Set the option ID property of the CourseOfferings object
        $courseOfferings->setLevelId($levelId); // Set the level ID property of the CourseOfferings object
        $courseOfferings->setAcademicYear($academicYear); // Set the academic year property of the CourseOfferings object
        $courseOfferings->setSemester($semester); // Set the semester property of the CourseOfferings object
        $courseOfferings->setInstructorId($instructorId); // Set the instructor ID property of the CourseOfferings object
        if($courseOfferings->insert()){
            echo json_encode(['success' => true, 'message' => 'New course created successfully.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to insert course offering.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to insert course.']);
        exit;
    }
}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Allow cross-origin requests
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST,GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-control-Allow-Credentials: true");

include_once '../connection.php'; // Include the database connection file
include_once '../models/class.course.php'; // Include the Course model class
include_once '../models/class.fileUpload.php';
$conn = Connection::getConnection(); // Get the database connection

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    

// Sanitize and get POST fields
    $courseId = null;
    $academicYear = $_POST['academicYear'] ?? null;
    $semester = $_POST['semester'] ?? null;
    $title = $_POST['title'] ?? null;
    $code = $_POST['code'] ?? null;
    $courseType = $_POST['courseType'] ?? null;
    $optionId = $_POST['optionId'] ?? null;
    $instructorId = $_POST['instructorId'] ?? null;
    $levelId = $_POST['levelId'] ?? null;
    $departmentId = $_POST['departmentId'] ?? null;

    //handle file upload
    $uploader = new FileUploader();
    $uploadDir = "../../frontend/public/courseThumbnail";
    $uploadResult = ['success' => true, 'filename' => null];

    if (isset($_FILES['thumbnail'])) {
        $uploadResult = $uploader->upload($_FILES['thumbnail'], $uploadDir);
        if (!$uploadResult['success']) {
            echo json_encode(['success' => false, 'message' => $uploadResult['message']]);
            exit;
        }
    }

    $thumbnail = $uploadResult['filename'];

    //first insert into the course table and then get the last insert id
    $course = new Course(); // Create a new Course object
    $course->setTitle($title); // Set the title property of the Course object
    $course->setCode($code); // Set the code property of the Course object
    $course->setCourseType($courseType); // Set the course type property of the Course object
    $course->setThumbnail($thumbnail);


    //check if the coursecode is already taken
    if($course->isCodeTaken()){
        echo json_encode([
            'success'=> false,
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
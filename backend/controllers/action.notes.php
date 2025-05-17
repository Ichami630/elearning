<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

include_once '../connection.php';
include_once '../models/class.module.php';
include_once '../models/class.course.php';
$module = new Module();
$material = new Material();
$course = new Course();

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $courseId = $_GET['courseId'] ?? null;
    $title = $_GET['title'] ?? null;

    if(isset($courseId) && !empty($courseId)){
        $modules = $module->getCourseModules((int)$courseId);
        echo json_encode([
            'success'=> true,
            'modules'=>$modules
        ]);exit;
    }

    //get the content of a module topic
    if(isset($title) && !empty($title)){
        $notes = $material->getNoteContent($title);
        echo json_encode([
            'success'=> true,
            'notes'=>$notes
        ]);exit;
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $courseId = $_POST['courseId'] ?? null;
    $title = $_POST['moduleTitle'] ?? null;

    //get the course_offering id from the courseid
    $courseOfferingId = $course->getCourseOfferingId($courseId);
    if($courseOfferingId !== 0){
        $module->setCourseOfferingId($courseOfferingId);
        $module->setTitle($title);
        $module->setDescription(null);
        if($module->insert()){
            $material = new Material();

            $material->setModuleId($module->getLastInsertId());
            $material->setTitle($_POST["noteTitle"]);
            $material->setMaterialType($_POST["noteType"]);
            $material->setContent($_POST["noteContent"]);
            $material->setVideoUrl(null);
            $material->setFileUrl(null);

            if($material->insert()){
                echo json_encode([
                    'success'=> true,
                    'message'=>'module and note created successfully'
                ]);exit;
            }
        }else{
            echo json_encode([
                'success'=> false,
                'message'=>'module creation failed'
            ]);exit;
        }
    }

}
?>
<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

include_once '../connection.php';
include_once '../models/class.module.php';

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $courseId = $_GET['courseId'] ?? null;

    if(isset($courseId) && !empty($courseId)){
        $module = new Module();
        $modules = $module->getCourseModules((int)$courseId);
        echo json_encode([
            'success'=> true,
            'modules'=>$modules
        ]);exit;
    }
}
?>
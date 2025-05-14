<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

include_once '../connection.php';
include_once '../models/class.option.php';

$option = new Option();
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $deptId = $_GET['deptId'] ?? null;

    if(isset($deptId) && !empty($deptId)){
        $options = $option->getOptionBydeptId((int)$deptId);

        echo json_encode([
            'success'=> true,
            'options'=>$options
        ]);exit;
    }else{
        echo json_encode([
            'success'=> false,
            'message'=>'invalid department id'
        ]);exit;
    }
}
?>
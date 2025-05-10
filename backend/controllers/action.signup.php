<?php
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include_once '../connection.php'; // Include the database connection file
        include_once '../models/class.user.php'; // Include the User model class
        $conn = Connection::getConnection(); // Get the database connection

        $data = json_decode(file_get_contents("php://input")); // Get the JSON data from the request body
        $name = $data->name ?? null;
        $email = $data->email ?? null; 
        $password = $data->password ?? null; 
        $role = $data->role ?? null;
        $title = $data->title ?? null;
        $departmentId = $data->departmentId ?? null;
        $optionId = $data->optionId ?? null;
        $levelId = $data->levelId ?? null;

        //create user object
        $user = new User();

        $user->setName(trim($name));
        $user->setEmail(trim($email));
        $user->setPassword($password);
        $user->setRole(trim($role));
        $user->setTitle(trim($title));
        $user->setDepartmentId(is_numeric($departmentId) ? (int)$departmentId : null);
        $user->setOptionId(is_numeric($optionId) ? (int)$optionId : null);
        $user->setLevelId(is_numeric($levelId) ? (int)$levelId : null);


        //check whether the email is already taken
        if ($user->isEmailTaken($email)){
            echo json_encode([
                "success" => false,
                "message" => "Email already taken."]);exit;
        }
        //check if the user is created successfully
        if($user->insert()){
            echo json_encode([
                "success" => true,
                "message" => "User created successfuly."
            ]);
        }else{
            echo json_encode([
                "success" => false,
                "message" => "User not created."]);
        }

        exit;



    }
?>
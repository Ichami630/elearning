<?php
    // Allow cross-origin requests
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST,GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    // Handle preflight (OPTIONS) requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); // Stop here so the rest of the script doesn't run
}
    session_start(); // Start the session to access session variables

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include_once '../connection.php'; // Include the database connection file
        include_once '../models/class.user.php'; // Include the User model class
        $conn = Connection::getConnection(); // Get the database connection

        $data = json_decode(file_get_contents("php://input")); // Get the JSON data from the request body

        $email = $data->email ?? null; // Get the email from the request data or set it to null if not provided
        $password = $data->password ?? null; // Get the password from the request data or set it to null if not provided

        //check if the email and databse is empty
        if(empty($email) || empty($password)){
            echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
            exit;
        }

        $user = new User(); // Create a new User object
        $user->setEmail(trim($email)); // Set the email property of the User object

        if($user->login($email,$password)){
            // If login is successful, set session expiration time to 30 minutes
            $_SESSION['id'] = $user->getId(); // Store user ID in session
            $_SESSION['expiry'] = time() + 3600; // 1 hour expiry
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Login successful.',
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole(),
                    'title' => $user->getTitle()
                ]
            ]);
        } else {
            // If login fails, return error response
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
        }
    }
?>
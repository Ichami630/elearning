<?php
    // Allow cross-origin requests
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST,GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-control-Allow-Credentials: true");   
    session_start();

    if(isset($_SESSION['id']) && isset($_SESSION['expiry'])){
        if($_SESSION['expiry'] < time()){
            //session expired
            session_destroy(); // Destroy the session
            echo json_encode(['status' => 'expired', 'message' => 'Session expired. Please log in again.']);
            exit;
        }else{
            //session is still valid
            $_SESSION['expiry'] = time() + 3600; // Reset session expiry time to 1 hour from now
            echo json_encode(['status' => 'active']);
        }
    }
?>
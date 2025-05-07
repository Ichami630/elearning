<?php
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
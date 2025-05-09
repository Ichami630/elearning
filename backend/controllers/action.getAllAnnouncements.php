<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

include_once '../connection.php';
include_once '../models/class.announcement.php';

$conn = Connection::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $announcement = new Announcement();
    $announcements = $announcement->getAll(); // No need to wrap in array

    echo json_encode([
        'success' => true,
        'announcements' => $announcements
    ]);
}
?>

<?php
    // Allow cross-origin requests

    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: POST,GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-control-Allow-Credentials: true");

    include_once '../connection.php';
    include_once '../models/class.user.php';
    $conn = Connection::getConnection();

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $user = new User();
        if (isset($_GET['id']) || isset($_GET['role'])) {
            $result = $user->getStudents((int)$_GET['id'], $_GET['role']);

            $formatted = [
                [
                    'name' => 'Total',
                    'count' => 0,
                    'fill' => 'white'
                ],
                [
                    'name' => 'Girls',
                    'count' => 0,
                    'fill' => '#FAE27C'
                ],
                [
                    'name' => 'Boys',
                    'count' => 0,
                    'fill' => '#C3EBFA'
                ]
            ];

            foreach ($result as $row) {
                $sex = strtolower(trim($row['name']));
                $count = (int) $row['count'];

                $formatted[0]['count'] += $count; // Total count

                if ($sex === 'male') {
                    $formatted[2]['count'] = $count; // Boys
                } elseif ($sex === 'female') {
                    $formatted[1]['count'] = $count; // Girls
                }
            }

            echo json_encode([
                'success' => true,
                'result' => $formatted
            ]);
            exit;
        }

        $totalAdmins = $user->selectCount('admin');
        $totalLecturers = $user->selectCount('lecturer');   
        $totalStudents = $user->selectCount('student');

        echo json_encode([
            'status' => true,
            'totalAdmins' => $totalAdmins,
            'totalLecturers' => $totalLecturers,
            'totalStudents' => $totalStudents
        ]);
    }
?>
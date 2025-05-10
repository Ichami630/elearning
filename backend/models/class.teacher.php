<?php
declare(strict_types = 1);
include_once '../connection.php';

class Teacher {
    protected $database;

    public function __construct(){
        $this->database = Connection::getConnection();
    }

    public function getAllLecturers(): array{
        $sql = "SELECT u.id,u.name,u.email,GROUP_CONCAT(DISTINCT c.title) AS courses,GROUP_CONCAT(DISTINCT l.name) AS levels,
        GROUP_CONCAT(DISTINCT d.name) as departments FROM users u
        JOIN course_offerings co ON co.instructor_id = u.id
        JOIN courses c ON co.course_id = c.id
        JOIN levels l ON co.level_id = l.id
        JOIN departments d ON co.department_id = d.id
        WHERE u.role = 'lecturer'
        GROUP BY u.id, u.name, u.email";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllStudentLecturers($studentId): array{
        $sql = "SELECT u.id,u.name,u.email,GROUP_CONCAT(DISTINCT c.title) AS courses,GROUP_CONCAT(DISTINCT l.name) AS levels,
        GROUP_CONCAT(DISTINCT d.name) as departments FROM users u
        JOIN course_offerings co ON co.instructor_id = u.id
        JOIN courses c ON co.course_id = c.id
        JOIN levels l ON co.level_id = l.id
        JOIN departments d ON co.department_id = d.id
        JOIN enrollments e ON e.course_offering_id = co.id
        WHERE u.role = 'lecturer' AND e.student_id = ?
        GROUP BY u.id, u.name, u.email";

        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i',$studentId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
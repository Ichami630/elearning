<?php
declare(strict_types = 1);
include_once '../connection.php';

class Student {
    protected $database;
    private string $role = "student";

    public function __construct(){
        $this->database = Connection::getConnection();
    }

    public function allStudents(): array{
        $sql = "SELECT u.id,u.name,u.email,d.name AS department,o.name AS option_name,l.name AS level FROM users u
        JOIN departments d ON u.department_id = d.id
        JOIN options o ON u.option_id = o.id
        JOIN levels l ON u.option_id = l.id
        WHERE u.role = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('s',$this->role);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
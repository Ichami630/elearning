<?php
declare(strict_types=1);
include_once '../connection.php';

class Enrollment{
    private int $studentId;
    private int $courseOfferingId;

    protected $database;

    public function __construct() {
        $this->database = Connection::getConnection(); // get the shared database connection
    }

    //getters and setters for private properties
    public function getStudentId(): int { return $this->studentId; }
    public function getCourseOfferingId(): int { return $this->courseOfferingId; }
    public function setStudentId(int $studentId): void { $this->studentId = $studentId; }
    public function setCourseOfferingId(int $courseOfferingId): void { $this->courseOfferingId = $courseOfferingId; }

    //method to insert a new enrollment into the database
    public function insert(): bool {
        $sql = "INSERT INTO enrollments (student_id, course_offering_id) VALUES (?, ?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('ii', $this->studentId, $this->courseOfferingId);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    //method to check if a student is already enrolled in a course offering
    public function isEnrolled(): bool {
        $sql = "SELECT * FROM enrollments WHERE student_id = ? AND course_offering_id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('ii', $this->studentId, $this->courseOfferingId);
        $stmt->execute();
        $result = $stmt->get_result(); // get the result set

        return $result->num_rows > 0; // return true if the student is already enrolled
    }
    //method to get all enrollments for a student
    public function getEnrollments(): array {
        $sql = "SELECT * FROM enrollments WHERE student_id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $this->studentId);
        $stmt->execute();
        $result = $stmt->get_result(); // get the result set

        return $result->fetch_all(MYSQLI_ASSOC); // return all enrollments as an associative array
    }
    //method to get all enrollments for a course offering
    public function getCourseOfferings(): array {
        $sql = "SELECT * FROM enrollments WHERE course_offering_id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $this->courseOfferingId);
        $stmt->execute();
        $result = $stmt->get_result(); // get the result set

        return $result->fetch_all(MYSQLI_ASSOC); // return all enrollments as an associative array
    }
    //method to delete an enrollment from the database
    public function delete(): bool {
        $sql = "DELETE FROM enrollments WHERE student_id = ? AND course_offering_id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('ii', $this->studentId, $this->courseOfferingId);
        return $stmt->execute();
    }
    //method to get the number of students enrolled in a course offering
    public function getEnrollmentCount(): int {
        $sql = "SELECT COUNT(*) as count FROM enrollments WHERE course_offering_id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $this->courseOfferingId);
        $stmt->execute();
        $result = $stmt->get_result(); // get the result set

        return (int)$result->fetch_assoc()['count']; // return the count as an integer
    }

    //get student enrolledcourse with lecturer name
    public function getEnrolledCoursesExtended(int $studentId): array{
        $sql = "SELECT CONCAT(u.title, '. ', u.name) AS lecturer, c.title FROM enrollments e
        JOIN course_offerings co ON e.course_offering_id = co.id
        JOIN courses c ON co.course_id = c.id
        JOIN users u ON co.instructor_id = u.id
        WHERE e.student_id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i',$studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
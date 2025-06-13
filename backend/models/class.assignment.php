<?php
declare(strict_types=1);
include_once("../connection.php");

class Assignment{
    private int $id;
    private int $courseOfferingId;
    private string $title;
    private string $description;
    private int $marks;
    private ?string $dueDate;

    protected $database;

    public function __construct(){
        $this->database = Connection::getConnection();
    }

    public function getId(): int {
        return $this->id;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function getCourseOfferingId(): int {
        return $this->courseOfferingId;
    }
    public function setCourseOfferingId(int $courseOfferingId): void {
        $this->courseOfferingId = $courseOfferingId;
    }
    public function getTitle(): string {
        return $this->title;
    }
    public function setTitle(string $title): void {
        $this->title = $title;
    }
    public function getDescription(): string {
        return $this->description;
    }
    public function setDescription(string $description): void {
        $this->description = $description;
    }
    public function getMarks(): int {
        return $this->marks;
    }
    public function setMarks(int $marks): void {
        $this->marks = $marks;
    }
    public function getDueDate(): ?string {
        return $this->dueDate;
    }
    public function setDueDate(?string $dueDate): void {
        $this->dueDate = $dueDate;
    }

    public function insert(): bool {
        $stmt = $this->database->prepare("INSERT INTO assignments (course_offering_id, title, description,marks, due_date) VALUES (?, ?, ?, ?,?)");
        $stmt->bind_param("issis", $this->courseOfferingId, $this->title, $this->description,$this->marks, $this->dueDate);
        return $stmt->execute();
    }

    //get all assignments for a particular course
    public function getCourseAssignments(int $courseId): array{
        $sql = "SELECT a.id,a.title,a.due_date FROM assignments a
        JOIN course_offerings co ON a.course_offering_id = co.id
        JOIN courses c ON co.course_id = c.id
        WHERE c.id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //get the assignment details by it's id
    public function getAssignment(int $assignmentId): array{
        $sql = "SELECT a.id,a.title,a.description,a.marks,a.due_date FROM assignments a
        WHERE a.id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    //get all assignments on the courses the student is studying
    public function getStudentAssignments(int $studentId): array {
        $sql = "SELECT a.id,c.title AS course_title,c.code,a.title,a.due_date FROM assignments a
        JOIN course_offerings co ON a.course_offering_id=co.id
        JOIN courses c ON co.course_id=c.id
        JOIN enrollments e ON co.id=e.course_offering_id
        WHERE e.student_id = ? OR co.instructor_id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ii", $studentId, $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //get student pending assignments
    public function getPendingAssignments(int $studentId): int{
        $sql = "SELECT COUNT(*) AS pending_assignments
        FROM assignments a
        JOIN enrollments e ON a.course_offering_id = e.course_offering_id
        WHERE e.student_id = ?
        AND NOT EXISTS (
        SELECT 1
        FROM submissions s
        WHERE s.assignment_id = a.id
        AND s.student_id = e.student_id)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i',$studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row["pending_assignments"];
    }
}
?>
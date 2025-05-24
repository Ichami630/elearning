<?php
declare(strict_types=1);
include_once("../connection.php");

class Submission{
    private int $id;
    private int $assignmentId;
    private int $studentId;
    private string $title;
    private ?string $message;
    private ?string $fileUrl;
    private ?float $grade;
    private ?string $feedback;

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
    public function getAssignmentId(): int {
        return $this->assignmentId;
    }
    public function setAssignmentId(int $assignmentId): void {
        $this->assignmentId = $assignmentId;
    }
    public function getStudentId(): int {
        return $this->studentId;
    }
    public function setStudentId(int $studentId): void {
        $this->studentId = $studentId;
    }
    public function getTitle(): string {
        return $this->title;
    }
    public function setTitle(string $title): void {
        $this->title = $title;
    }
    public function getMessage(): string {
        return $this->message;
    }
    public function setMessage(?string $message): void {
        $this->message = $message;
    }
    public function getFileUrl(): ?string {
        return $this->fileUrl;
    }
    public function setFileUrl(?string $fileUrl): void {
        $this->fileUrl = $fileUrl;
    }
    public function getGrade(): ?float {
        return $this->grade;
    }
    public function setGrade(?float $grade): void {
        $this->grade = $grade;
    }
    public function getFeedback(): ?string {
        return $this->feedback;
    }
    public function setFeedback(?string $feedback): void {
        $this->feedback = $feedback;
    }

    public function insert(): bool {
        $stmt = $this->database->prepare("INSERT INTO submissions (assignment_id, student_id, title, message, file_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $this->assignmentId, $this->studentId, $this->title, $this->message, $this->fileUrl);
        return $stmt->execute();
    }

    //update the submission with grade and feedback
    public function update(): bool {
        $stmt = $this->database->prepare("UPDATE submissions SET grade = ?, feedback = ? WHERE id = ?");
        $stmt->bind_param("dsi", $this->grade, $this->feedback, $this->id);
        return $stmt->execute();
    }

    //get all submissions for a particular assignment
    public function getSubmissionsByAssignmentId(int $assignmentId): array {
        $stmt = $this->database->prepare("SELECT s.*,u.name FROM submissions s
        JOIN users u ON s.student_id=u.id
        WHERE assignment_id = ?");
        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //get a submission by id
    public function getSubmissionById(int $submissionId): array {
        $stmt = $this->database->prepare("SELECT * FROM submissions WHERE id = ?");
        $stmt->bind_param("i", $submissionId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    //get all submissions by student id
    public function getSubmissionsByStudentId(int $studentId): array {
        $stmt = $this->database->prepare("SELECT * FROM submissions WHERE student_id = ?");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //check is a student has alreay submitted an assignment
    public function hasSubmitted(int $assignmentId, int $studentId): bool {
        $stmt = $this->database->prepare("SELECT COUNT(*) FROM submissions WHERE assignment_id = ? AND student_id = ?");
        $stmt->bind_param("ii", $assignmentId, $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0] > 0;
    }

}
?>
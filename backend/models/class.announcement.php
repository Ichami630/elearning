<?php
declare(strict_types = 1);
include_once '../connection.php';

class Announcement {
    private int $id;
    private ?int $courseOfferingId;
    private string $title;
    private string $message;

    protected $database;

    public function __construct(){
        $this->database = Connection::getConnection();
    }

    //getters and setters for the private properties
    public function getId(): int {return $this->id;}
    public function setId(int $id): void {$this->id = $id;}
    public function getCourseOfferingId(): ?int {return $this->courseOfferingId;}
    public function setCourseOfferingId(?int $courseOfferingId): void {$this->courseOfferingId = $courseOfferingId;}
    public function getTitle(): string {return $this->title;}
    public function setTitle(string $title): void {$this->title = $title;}
    public function getMessage(): string {return $this->message;}
    public function setMessage(string $message): void {$this->message = $message;}

    //create a new announcement
    public function insert(): bool{
        $sql = "INSERT INTO announcements (course_offering_id,title,message) VALUES(?,?,?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('iss',$this->courseOfferingId,$this->title,$this->message);
        return $stmt->execute();
    }

    //get all announcement with null course offerings
    public function getAll(): array {
        $sql = "SELECT * FROM announcements WHERE course_offering_id IS NULL ORDER BY posted_at DESC";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_all(MYSQLI_ASSOC); // Return all rows as array of assoc arrays
    }
    
}
?>
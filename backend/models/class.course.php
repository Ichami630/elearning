<?php
declare(strict_types=1);
include_once '../connection.php';

class Course {
    private int $id;
    private string $title;
    private string $code;
    private ?string $thumbnail;
    private string $courseType;

    private int $lastInsertId;

    //instance of the database
    protected $database;

    //constructor to initialise the database connection
    public function __construct() {
        $this->database = Connection::getConnection(); // get the shared database connection
    }

    //getters and setters for course private properties
    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getCode(): string { return $this->code; }
    public function getThumbnail(): string { return $this->thumbnail; }
    public function getCourseType(): string { return $this->courseType; }
    public function getLastInsertId(): int { return $this->lastInsertId; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setCode(string $code): void { $this->code = $code; }
    public function setThumbnail(?string $thumbnail): void { $this->thumbnail = $thumbnail; }
    public function setCourseType(string $courseType): void { $this->courseType = $courseType; }

    //method to select a course by id
    public function select(int $id) {
        $sql = "SELECT * FROM courses WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result(); // get the result set

        //assign result to course properties
        if ($course = $result->fetch_object()) {
            $this->id = $course->id;
            $this->title = $course->title;
            $this->code = $course->code;
            $this->thumbnail = $course->thumbnail;
            $this->courseType = $course->course_type;
        }
    }
    //method to insert a new course into the database
    public function insert(): bool {
        $sql = "INSERT INTO courses (title,code,thumbnail,course_type) VALUES (?,?,?,?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('ssss', $this->title,$this->code,$this->thumbnail,$this->courseType);
        if ($stmt->execute()) {
            $this->lastInsertId = $stmt->insert_id; // get the last inserted id
            return true;
        } else {
            return false;
        }
    }

    //method to update an existing course in the database
    public function update(): bool {
        $sql = "UPDATE courses SET title = ?, code = ?,thumbnail = ?,course_type = ? WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('ssssi', $this->title, $this->code,$this->thumbnail, $this->courseType, $this->id);
        return $stmt->execute();
    }

    //method to delete a course from the database
    public function delete(int $id): bool {
        $sql = "DELETE FROM courses WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    //get courseThumnail,title
    public function getAllCourseNameThumbnail(): array{
        $sql = "SELECT c.id, c.title,c.code,c.thumbnail,u.name,l.name AS c_level FROM courses c
        JOIN course_offerings co ON c.id=co.course_id
        JOIN users u ON co.instructor_id = u.id
        JOIN levels l ON co.level_id = l.id
        ORDER BY c.id DESC";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //check if the courseCode already exist
    public function isCodeTaken(): bool {
    $stmt = $this->database->prepare("SELECT id FROM courses WHERE code = ?");
    $stmt->bind_param('s', $this->code);
    $stmt->execute();
    $stmt->store_result(); // Store the result to count rows
    return $stmt->num_rows > 0; // Return true if a course with the same code exists
    }


    //get distinct courses a lecturer teaches
    public function getAllCourseOfLecturer(int $lecturerId): array{
        $sql = "SELECT DISTINCT c.id,c.title,c.code,c.thumbnail,u.name,l.name AS c_level
        FROM courses c
        JOIN course_offerings co ON c.id = co.course_id
        JOIN users u ON co.instructor_id = u.id
        JOIN levels l ON co.level_id = l.id
        WHERE co.instructor_id = ?
        ORDER BY c.id DESC";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i',$lecturerId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //get courses a student is currently enrolled in
    public function getAllStudentEnrolledCourses(int $studentId): array{
        $sql = "SELECT c.id, c.title, c.code, c.thumbnail
        FROM enrollments e
        JOIN course_offerings co ON e.course_offering_id = co.id
        JOIN courses c ON co.course_id = c.id
        WHERE e.student_id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i",$studentId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //get the emails of the student enrolled in this course
    public function getCourseStudentEmail(int $courseOfferingId): array{
        $sql = "SELECT u.name,u.email FROM users u 
        JOIN enrollments e ON u.id=e.student_id
        JOIN course_offerings co ON co.id=e.course_offering_id
        WHERE co.id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i',$courseOfferingId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);


    }

    //get all students enrolled in a course
    public function getCourseEnroledStudents(int $courseId): array{
        $sql = "SELECT u.id,u.name,u.email FROM courses c
        JOIN course_offerings co ON c.id=co.course_id
        JOIN enrollments e ON e.course_offering_id = co.id 
        JOIN users u ON u.id = e.student_id
        WHERE c.id = ? AND u.role = 'student'
        ORDER BY c.id DESC";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i',$courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //get courses in student department
    public function getAllcourseNotEnrolled(int $id,string $semester): array{
        $sql = "SELECT c.id AS course_id,co.id,c.title AS course_title,c.code AS course_code,
    CONCAT(u.title,' ',u.name) AS instructor_name
    FROM users s
    JOIN course_offerings co ON (
    co.department_id = s.department_id OR co.department_id IS NULL)
    JOIN courses c ON co.course_id = c.id
    LEFT JOIN users u ON co.instructor_id = u.id
    WHERE s.id = ?
    AND (c.course_type = 'general_all'
    OR (c.course_type = 'general_dept' AND co.department_id = s.department_id)
    OR (c.course_type = 'specific' AND co.department_id = s.department_id))
    AND co.semester = ?";

        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('is',$id,$semester);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

        //get the courseoffering id from the courseid
    public function getCourseOfferingId(int $courseId): int {
        $sql = "SELECT id FROM course_offerings WHERE course_id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($courseOffering = $result->fetch_object()) {
            return (int)$courseOffering->id;
        }
        return 0; // Return 0 if no course offering found
    }


}

//course offerings class
class CourseOfferings{
    private int $id;
    private int $courseId;
    private ?int $depatmentId;
    private ?int $optionId;
    private int $levelId;
    private string $academicYear;
    private string $semester;
    private int $instructorId;

    protected $database;

    public function __construct() {
        $this->database = Connection::getConnection(); // get the shared database connection
    }

    //getters and setters for course offerings private properties
    public function getId(): int { return $this->id; }
    public function getCourseId(): int { return $this->courseId; }
    public function getDepartmentId(): int { return $this->depatmentId; }
    public function getOptionId(): int { return $this->optionId; }
    public function getLevelId(): int { return $this->levelId; }
    public function getAcademicYear(): string { return $this->academicYear; }
    public function getSemester(): string { return $this->semester; }
    public function getInstructorId(): int { return $this->instructorId; }
    public function setCourseId(int $courseId): void { $this->courseId = $courseId; }
    public function setDepartmentId(?int $departmentId): void { $this->depatmentId = $departmentId; }
    public function setOptionId(?int $optionId): void { $this->optionId = $optionId; }
    public function setLevelId(int $levelId): void { $this->levelId = $levelId; }
    public function setAcademicYear(string $academicYear): void { $this->academicYear = $academicYear; }
    public function setSemester(string $semester): void { $this->semester = $semester; }
    public function setInstructorId(int $instructorId): void { $this->instructorId = $instructorId; }

    //method to select a course offering by id
    public function select(int $id) {
        $sql = "SELECT * FROM course_offerings WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result(); // get the result set

        //assign result to course offering properties
        if ($courseOffering = $result->fetch_object()) {
            $this->id = $courseOffering->id;
            $this->courseId = $courseOffering->course_id;
            $this->depatmentId = $courseOffering->department_id;
            $this->optionId = $courseOffering->option_id;
            $this->levelId = $courseOffering->level_id;
            $this->academicYear = $courseOffering->academic_year;
            $this->semester = $courseOffering->semester;
            $this->instructorId = $courseOffering->instructor_id;
        }
    }

    //method to insert a new course offering into the database
    public function insert(): bool {
        $sql = "INSERT INTO course_offerings (course_id,department_id,option_id,level_id,academic_year,semester,instructor_id) VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('iiiissi', $this->courseId,$this->depatmentId,$this->optionId,$this->levelId,$this->academicYear,$this->semester,$this->instructorId);
        return $stmt->execute();
    }

    //method to update an existing course offering in the database
    public function update(int $id): bool {
        $sql = "UPDATE course_offerings SET course_id = ?, department_id = ?, option_id = ?, level_id = ?, academic_year = ?, semester = ?, instructor_id = ? WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('iiiissii', $this->courseId,$this->depatmentId,$this->optionId,$this->levelId,$this->academicYear,$this->semester,$this->instructorId,$id);
        return $stmt->execute();
    }

    //method to delete a course offering from the database
    public function delete(int $id): bool {
        $sql = "DELETE FROM course_offerings WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();

    }

    //method to select all course offerings
    public function getAllCourseOfferings(){
        $sql = "SELECT * FROM course_offerings";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $courseOfferings = []; //array to hold all course offerings
        while($courseOffering = $result->fetch_object()){
            $courseOfferings[] = $courseOffering; //add each course offering to the array    
        }

        return $courseOfferings; //return the array of course offerings
    }

}
?>
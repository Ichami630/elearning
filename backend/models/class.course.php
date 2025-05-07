<?php
declare(strict_types=1);
include_once '../connection.php';

class Course {
    private int $id;
    private string $title;
    private string $code;
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
    public function getCourseType(): string { return $this->courseType; }
    public function getLastInsertId(): int { return $this->lastInsertId; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setCode(string $code): void { $this->code = $code; }
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
            $this->courseType = $course->course_type;
        }
    }
    //method to insert a new course into the database
    public function insert(): bool {
        $sql = "INSERT INTO courses (title,code,course_type) VALUES (?,?,?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('sss', $this->title,$this->code,$this->courseType);
        if ($stmt->execute()) {
            $this->lastInsertId = $stmt->insert_id; // get the last inserted id
            return true;
        } else {
            return false;
        }
    }

    //method to update an existing course in the database
    public function update(): bool {
        $sql = "UPDATE courses SET title = ?, code = ?,course_type = ? WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('sssi', $this->title, $this->code, $this->courseType, $this->id);
        return $stmt->execute();
    }

    //method to delete a course from the database
    public function delete(int $id): bool {
        $sql = "DELETE FROM courses WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    //method to select all courses
    public function getAllCourses(){
        $sql = "SELECT * FROM courses";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $courses = []; //array to hold all courses
        while($course = $result->fetch_object()){
            $courses[] = $course; //add each course to the array    
        }

        return $courses; //return the array of courses
    }
}

//course offerings class
class CourseOfferings{
    private int $id;
    private int $courseId;
    private int $depatmentId;
    private int $optionId;
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
    public function setDepartmentId(int $departmentId): void { $this->depatmentId = $departmentId; }
    public function setOptionId(int $optionId): void { $this->optionId = $optionId; }
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
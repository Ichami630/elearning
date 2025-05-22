<?php
declare(strict_types = 1);
include_once("../connection.php");
class Quiz{
    private int $id;
    private int $courseOfferingId;
    private string $title;
    private int $totalMarks;
    private int $duration;

    private int $lastInsertId;
    protected $database;

    public function __construct(){
        $this->database = Connection::getConnection();
    }

    public function getId(): int { return $this->id; }
    public function getCourseOfferingId(): int {return $this->courseOfferingId; }
    public function getTitle(): string {return $this->title; }
    public function getTotalMarks(): int { return $this->totalMarks; }
    public function getDuration(): int { return $this->duration;}
    public function getLastInsertId(): int { return $this->lastInsertId; }

    public function setCourseOfferingId(int $id): void{ $this->courseOfferingId = $id;}
    public function setTitle(string $title):void{$this->title = $title;}
    public function setTotalMarks(int $marks): void { $this->totalMarks = $marks;}
    public function setDuration(int $duration): void{ $this->duration = $duration;}

    //insert into the quiz table
    public function insert(): int{
        $sql = "INSERT INTO quizzes(course_offering_id,title,total_marks,duration_minutes) VALUES(?,?,?,?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('isii',$this->courseOfferingId,$this->title,$this->totalMarks,$this->duration);
        $stmt->execute();
        return $this->lastInsertId = $stmt->insert_id;
    }

    public function getQuiz(int $id){
        $sql = 'SELECT title,total_marks,duration_minutes
        FROM quizzes 
        WHERE id=?';
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($q=$result->fetch_object()){
            $this->title = $q->title;
            $this->totalMarks = $q->total_marks;
            $this->duration = $q->duration_minutes;
        }
    }


}

class Questions{
    private int $id;
    private int $quizId;
    private string $question;
    private string $optionA;
    private string $optionB;
    private ?string $optionC;
    private ?string $optionD;
    private string $correctOption;

    protected $database;

    public function __construct(){
        $this->database = Connection::getConnection();
    }

    public function getId(): int {return $this->id; }
    public function getQuizId(): int { return $this->quizId; }
    public function getQuestion(): string { return $this->question; }
    public function getOptionA(): string { return $this->optionA; }
    public function getOptionB(): string { return $this->optionB; }
    public function getOptionC(): string { return $this->optionC; }
    public function getOptionD(): string { return $this->optionD; }
    public function getCorrectOption(): string { return $this->correctOption; }
    public function setQuizId(int $quizId): void{ $this->quizId = $quizId;}
    public function setQuestion(string $question): void{ $this->question = $question;}
    public function setOptionA(string $optionA): void{ $this->optionA = $optionA;}
    public function setOptionB(string $optionB): void{ $this->optionB = $optionB;}
    public function setOptionC(?string $optionC): void{ $this->optionC = $optionC;}
    public function setOptionD(?string $optionD): void{ $this->optionD = $optionD;}
    public function setCorrectOption(string $correctOption): void{ $this->correctOption = $correctOption;}

    //insert into the quiz questions table
    public function insert():bool{
        $sql = "INSERT INTO quiz_questions(quiz_id,question,option_a,option_b,option_c,option_d,correct_option) VALUES(?,?,?,?,?,?,?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("issssss",$this->quizId,$this->question,$this->optionA,$this->optionB,$this->optionC,$this->optionD,$this->correctOption);
        return $stmt->execute();
    }

    public function getQuestions(int $id): array{
        $sql = 'SELECT qu.id,qu.question,GROUP_CONCAT(qu.option_a,",",qu.option_b,",",qu.option_c,",",qu.option_d) AS options,qu.correct_option
        FROM quizzes q
        JOIN quiz_questions qu ON q.id=qu.quiz_id
        WHERE q.id = ?
        GROUP BY qu.id';
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result= $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
}

class Result{
    private int $id;
    private int $quizId;
    private int $studentId;
    private float $score;

    protected $database;

    public function __construct(){
        $this->database = Connection::getConnection();
    }

    public function getId(): int { return $this->id; }
    public function getQuizId(): int { return $this->quizId; }
    public function getStudentId(): int { return $this->studentId; }
    public function getScore(): float { return $this->score; }
    public function setQuizId(int $quizId): void{ $this->quizId = $quizId;}
    public function setStudentId(int $studentId): void{ $this->studentId = $studentId;}
    public function setScore(float $score): void{ $this->score = $score;}

    public function insert(): bool{
        $sql = "INSERT INTO quiz_results(quiz_id,student_id,score) VALUES(?,?,?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("iid",$this->quizId,$this->studentId,$this->score);
        return $stmt->execute();
    }

    

}
?>
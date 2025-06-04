<?php
declare(strict_types = 1); //enforce strict typing
include_once '../connection.php';
class User{
    //properties to hold user data
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $role;
    private string $sex;
    private ?string $title;
    private ?int $departmentId;
    private ?int $optionId;
    private ?int $levelId; // Nullable if not always set at instantiation

    //id of the last inserted record
    private int $lastid;

    //instance of the database
    protected $database;

    //constructor to initialise the database connection
    public function __construct(){
        $this->database = Connection::getConnection(); //get the shared database connection
    }

    //getter and setters for user private properties
    // Type hint for input parameters and return types 
    public function getId(): int { return $this->id;}
    public function getName(): string { return $this->name;}
    public function getEmail(): string{ return $this->email;}
    public function getRole(): string{ return $this->role;}
    public function getSex(): string{return $this->sex;}
    public function getTitle(): string{ return $this->title;}
    public function getDepartmentId(): int{return $this->departmentId;}
    public function getOptionId(): int{return $this->optionId;}
    public function getLevelId(): int{return $this->levelId;}
    public function getPassword(): string { return $this->password;}
    public function getLastId(): int { return $this->lastid;}
    public function setName(string $name): void{ $this->name = $name;}
    public function setEmail(string $email): void{ $this->email = $email;}
    public function setRole(string $role): void { $this->role = $role;}
    public function setSex(string $sex): void { $this->sex = $sex;}
    public function setTitle(string $title): void { $this->title = $title;}
    public function setDepartmentId(?int $deptId): void { $this->departmentId = $deptId;}
    public function setOptionId(?int $optionId): void { $this->optionId = $optionId;}
    public function setLevelId(?int $levelId): void { $this->levelId = $levelId;}
    public function setPassword(string $password): void { $this->password = password_hash($password,PASSWORD_BCRYPT);}

    //method to select a user by id
    public function select(int $id){
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result = $stmt->get_result(); //get the result set

        //assign result to user properties
        if($user = $result->fetch_object()){
            $this->id = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
            $this->sex = $user->sex;
            $this->title = $user->title;
            $this->departmentId = $user->department_id;
            $this->optionId = $user->option_id;
            $this->levelId = $user->level_id;
            $this->password = $user->password; //stored hash password
        }
    }

    //select count total based on roles
    public function selectCount(string $role): int{
        $sql = "SELECT COUNT(*) AS tot FROM users WHERE role = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("s",$role);
        $stmt->execute();
        $result = $stmt->get_result(); //get the result set
        $rows = $result->fetch_object(); //fetch the object
        return (int)$rows->tot; //return the total count
    }

    //select the total male and females students
    public function getStudents(?int $id, string $role): array {
    $sql  = "SELECT sex AS name, COUNT(*) AS count FROM users WHERE role = 'student'";

    if (!is_null($id) && $role === 'student') {
        $sql .= " AND department_id = (SELECT department_id FROM users WHERE id = ?)";
    }

    $sql .= " GROUP BY sex";

    $stmt = $this->database->prepare($sql);

    if (!is_null($id) && $role === 'student') {
        $stmt->bind_param("i", $id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


    // method to delete a user by id
    public function delete(int $id){
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->execute();
    }

    //methos to create a new user
    public function insert(): bool {
        $sql = "INSERT INTO users(name,email,sex,password,role,title,department_id,option_id,level_id) VALUES(?,?,?,?,?,?,?,?,?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ssssssiii",$this->name,$this->email,$this->sex,$this->password,$this->role,$this->title,$this->departmentId,$this->optionId,$this->levelId);
        if($stmt->execute()){
            $this->lastid = $stmt->insert_id; //captures the last inserted id
            return true;
        }

        return false;
        
    }


    //method to log in a user
    public function login(string $email,string $password): bool {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        //verify password if user exist
        if($user = $result->fetch_object()){
            if(password_verify($password,$user->password)){
                $this->id = $user->id;
                $this->role = $user->role;
                $this->title = $user->title;
                $this->name = $user->name;
                return true;
            }; //verify the password
        }

        return false; //return false if not found
    }

    //method to check if email is already taken
    public function isEmailTaken(string $email): bool{
        $sql = "SELECT COUNT(*) AS tot FROM users WHERE email = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("s",$email);
        $stmt->execute();

        $result = $stmt->get_result();
        $rows = $result->fetch_object();
        return $rows->tot > 0; //return true if email is taken
    }

};
?>
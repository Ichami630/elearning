<?php
declare(strict_types=1);
include_once '../connection.php'; // Include the database connection file

class Option {
    private int $id;
    private int $departmentId;
    private string $name;

    protected $database;
    // Constructor to initialize the database connection
    public function __construct() {
        $this->database = Connection::getConnection(); // Get the shared database connection
    }
    // Getter and setter methods for private properties
    public function getId(): int { return $this->id; }
    public function getDepartmentId(): int { return $this->departmentId; }
    public function setDepartmentId(int $departmentId): void { $this->departmentId = $departmentId; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }
    // Method to select an option by ID
    public function select(int $id) {
        $sql = "SELECT * FROM options WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set

        // Assign result to option properties
        if ($option = $result->fetch_object()) {
            $this->id = $option->id;
            $this->name = $option->name;
        }
    }
    // Method to insert a new option into the database
    public function insert(): bool {
        $sql = "INSERT INTO options (department_id,name) VALUES (?,?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('is', $this->departmentId,$this->name);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    // Method to update an existing option in the database
    public function update(): bool {
        $sql = "UPDATE options SET name = ? WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('si', $this->name, $this->id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    // Method to delete an option from the database
    public function delete(int $id): bool {
        $sql = "DELETE FROM options WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    // Method to get all options from the database
    public function getAllOptions(): array {
        $sql = "SELECT * FROM options";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set

        $options = []; // Initialize an empty array to store options
        while ($option = $result->fetch_object()) {
            $options[] = $option; // Add each option to the array
        }
        return $options; // Return the array of options
    }
    // Method to check if an option name already exists in the database
    public function isOptionNameTaken(string $name): bool {
        $sql = "SELECT * FROM options WHERE name = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set
        return $result->num_rows > 0; // Return true if option name exists, false otherwise
    }

    //get all the options of a department
    public function getOptionBydeptId(int $deptId): array{
        $sql = "SELECT o.name,o.id FROM options o
        JOIN departments d ON o.department_id = d.id
        WHERE o.department_id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i',$deptId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

}
?>
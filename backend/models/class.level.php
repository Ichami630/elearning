<?php
declare(strict_types=1);
include_once '../connection.php'; // Include the database connection file
class Level {
    private int $id;
    private string $name;
    private ?string $description;

    protected $database;
    // Constructor to initialize the database connection
    public function __construct() {
        $this->database = Connection::getConnection(); // Get the shared database connection
    }
    // Getter and setter methods for private properties
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function setName(string $name): void { $this->name = $name; }   
    public function setDescription(string $description): void { $this->description = $description; }
    // Method to select a level by ID
    public function select(int $id) {
        $sql = "SELECT * FROM levels WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set

        // Assign result to level properties
        if ($level = $result->fetch_object()) {
            $this->id = $level->id;
            $this->name = $level->name;
            $this->description = $level->description;
        }
    }
    // Method to insert a new level into the database
    public function insert(): bool {
        $sql = "INSERT INTO levels (name, description) VALUES (?, ?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('ss', $this->name, $this->description);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    // Method to update an existing level in the database
    public function update(): bool {
        $sql = "UPDATE levels SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('ssi', $this->name, $this->description, $this->id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    // Method to delete a level from the database
    public function delete(int $id): bool {
        $sql = "DELETE FROM levels WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Method to get all levels from the database
    public function getAllLevels(): array {
        $sql = "SELECT * FROM levels";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set

        $levels = []; // Initialize an empty array to store levels
        while ($level = $result->fetch_object()) {
            $levels[] = [
                "id" => $level->id,
                "name" => $level->name,
                "description" => $level->description
            ];
        }
        return $levels; // Return the array of levels
    }

    // Method to check if a level name already exists in the database
    public function isLevelNameTaken(string $name): bool {
        $sql = "SELECT * FROM levels WHERE name = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set
        return $result->num_rows > 0; // Return true if level name exists, false otherwise
    }
}
?>
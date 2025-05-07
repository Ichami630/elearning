<?php
declare(strict_types=1);
include_once '../connection.php'; // Include the database connection file

class Option {
    private int $id;
    private string $name;

    protected $database;
    // Constructor to initialize the database connection
    public function __construct() {
        $this->database = Connection::getConnection(); // Get the shared database connection
    }
    // Getter and setter methods for private properties
    public function getId(): int { return $this->id; }
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
        $sql = "INSERT INTO options (name) VALUES (?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('s', $this->name);
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

}
?>
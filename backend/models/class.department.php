<?php
    declare(strict_types = 1);
    include_once '../connection.php'; // Include the database connection file

    class Department {
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
        // Method to select a department by ID
        public function select(int $id) {
            $sql = "SELECT * FROM departments WHERE id = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result(); // Get the result set

            // Assign result to department properties
            if ($department = $result->fetch_object()) {
                $this->id = $department->id;
                $this->name = $department->name;
                $this->description = $department->description;
            }
        }
        // Method to insert a new department into the database
        public function insert(): bool {
            $sql = "INSERT INTO departments (name, description) VALUES (?, ?)";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param('ss', $this->name, $this->description);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        // Method to update an existing department in the database
        public function update(): bool {
            $sql = "UPDATE departments SET name = ?, description = ? WHERE id = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param('ssi', $this->name, $this->description, $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        // Method to delete a department from the database
        public function delete(int $id): bool {
            $sql = "DELETE FROM departments WHERE id = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param('i', $id);
            return $stmt->execute(); // Return true if successful, false otherwise
        }
        // Method to get all departments from the database
        public function getAllDepartments() {
            $sql = "SELECT * FROM departments";
            $stmt = $this->database->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result(); // Get the result set
            return $result; // Return the result set
        }
        // Method to check if a department name already exists in the database
        public function isDepartmentNameTaken(string $name): bool {
            $sql = "SELECT * FROM departments WHERE name = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $result = $stmt->get_result(); // Get the result set
            return $result->num_rows > 0; // Return true if department name exists, false otherwise
        }     
    }

?>
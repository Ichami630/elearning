<?php
declare(strict_types=1);
include_once '../connection.php';

class Module {
    private string $courseOfferingId;
    private string $title;
    private ?string $description;

    protected $database;

    public function __construct() {
        $this->database = Connection::getConnection(); // get the shared database connection
    }

    // Getters and setters for private properties
    public function getCourseOfferingId(): string { return $this->courseOfferingId; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): ?string { return $this->description; }
    public function setCourseOfferingId(string $courseOfferingId): void { $this->courseOfferingId = $courseOfferingId; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setDescription(?string $description): void { $this->description = $description; }

    // Method to select a module by id
    public function select(int $id) {
        $sql = "SELECT * FROM modules WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result(); // get the result set

        // Assign result to module properties
        if ($module = $result->fetch_object()) {
            $this->courseOfferingId = $module->course_offering_id;
            $this->title = $module->title;
            $this->description = $module->description;
        }
    }

    // Method to insert a new module into the database
    public function insert(): bool {
        $sql = "INSERT INTO modules (course_offering_id, title, description) VALUES (?, ?, ?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('iss', $this->courseOfferingId, $this->title, $this->description);
        return $stmt->execute();
    }

    // Method to update an existing module in the database
    public function update(int $id): bool {
        $sql = "UPDATE modules SET course_offering_id = ?, title = ?, description = ? WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('issi', $this->courseOfferingId, $this->title, $this->description, $id);
        return $stmt->execute(); // return true if the update was successful
    }

    // Method to delete a module from the database
    public function delete(int $id): bool {
        $sql = "DELETE FROM modules WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute(); // return true if the delete was successful
    }


    // Method to select all modules for a specific course offering
    public function getCourseModules(int $courseId): array {
        $sql = "SELECT m.title,ma.id,GROUP_CONCAT(DISTINCT ma.title) AS topics FROM modules m
        LEFT JOIN materials ma ON m.id = ma.module_id
        JOIN course_offerings co ON m.course_offering_id = co.id
        JOIN courses c ON co.course_id = c.id
        WHERE c.id = ?
        GROUP BY M.title";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $courseId);
        $stmt->execute();
        $result = $stmt->get_result(); // get the result set

        return $result->fetch_all(MYSQLI_ASSOC); // return all modules as an associative array
    }

}

class Material {
    private int $moduleId;
    private string $title;
    private ?string $description;
    private string $materialType;
    private ?string $content;
    private ?string $videoUrl;
    private ?string $fileUrl;

    protected $database;

    public function _construct() {
        $this->database = Connection::getConnection(); // get the shared database connection
    }

    // Getters and setters for private properties
    public function getModuleId(): int { return $this->moduleId; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): ?string { return $this->description; }
    public function getMaterialType(): string { return $this->materialType; }
    public function getContent(): ?string { return $this->content; }
    public function getVideoUrl(): ?string { return $this->videoUrl; }
    public function getFileUrl(): ?string { return $this->fileUrl; }
    public function setModuleId(int $moduleId): void { $this->moduleId = $moduleId; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setMaterialType(string $materialType): void { $this->materialType = $materialType; }
    public function setContent(?string $content): void { $this->content = $content; }
    public function setVideoUrl(?string $videoUrl): void { $this->videoUrl = $videoUrl; }
    public function setFileUrl(?string $fileUrl): void { $this->fileUrl = $fileUrl; }

    // Method to select a material by id
    public function select(int $id) {
        $sql = "SELECT * FROM materials WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result(); // get the result set

        // Assign result to material properties
        if ($material = $result->fetch_object()) {
            $this->moduleId = $material->module_id;
            $this->title = $material->title;
            $this->description = $material->description;
            $this->materialType = $material->material_type;
            $this->content = $material->content;
            $this->videoUrl = $material->video_url;
            $this->fileUrl = $material->file_url;
        }
    }

    // Method to insert a new material into the database
    public function insert(): bool {
        $sql = "INSERT INTO materials (module_id, title, description, material_type, content, video_url, file_url) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('issssss', $this->moduleId, $this->title, $this->description, $this->materialType, $this->content, $this->videoUrl, $this->fileUrl);
        return $stmt->execute(); // return true if the insert was successful
    }

    // Method to update an existing material in the database
    public function update(int $id): bool {
        $sql = "UPDATE materials SET module_id = ?, title = ?, description = ?, material_type = ?, content = ?, video_url = ?, file_url = ? WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('issssssi', $this->moduleId, $this->title, $this->description, $this->materialType, $this->content, $this->videoUrl, $this->fileUrl, $id);
        return $stmt->execute(); // return true if the update was successful
    }

    // Method to delete a material from the database
    public function delete(int $id): bool {
        $sql = "DELETE FROM materials WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute(); // return true if the delete was successful
    }

    // Method to select all materials for a specific module
    public function selectByModuleId(int $moduleId): array {
        $sql = "SELECT * FROM materials WHERE module_id = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param('i', $moduleId);
        $stmt->execute();
        $result = $stmt->get_result(); // get the result set

        return $result->fetch_all(MYSQLI_ASSOC); // return all materials as an associative array
    }
}
?>
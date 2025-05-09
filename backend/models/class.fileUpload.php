<?php
declare(strict_types=1);

class FileUploader {
    private array $allowedExtensions = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'];
    private int $maxSize = 5 * 1024 * 1024; // 5MB

    public function upload(array $file, string $targetDir): array {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'File upload error'];
        }

        $fileName = basename($file['name']);
        $fileSize = $file['size'];
        $fileTmpPath = $file['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate extension
        if (!in_array($fileExt, $this->allowedExtensions)) {
            return ['success' => false, 'message' => 'Invalid file type'];
        }

        // Validate size
        if ($fileSize > $this->maxSize) {
            return ['success' => false, 'message' => 'File is too large'];
        }

        // Ensure target directory exists
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Generate unique filename
        $newFileName = uniqid('file_', true) . '.' . $fileExt;
        $destination = rtrim($targetDir, '/') . '/' . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destination)) {
            return ['success' => true, 'filename' => $newFileName];
        } else {
            return ['success' => false, 'message' => 'Failed to move uploaded file'];
        }
    }
}

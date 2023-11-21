<?php

namespace Core\File;

use Core\Security\Random;

class Uploader
{
    protected string $targetDirectory;
    protected array $allowedFileTypes = [];
    protected int $maxFileSize = 1048576;
    protected string $rootUpload = ROOTPATH . 'assets/uploads/';

    public function __construct(string $targetDirectory = '')
    {
        $this->targetDirectory = $this->rootUpload . 'uploads/' . ltrim($targetDirectory, '/');
    }
    
    public function set_allowed_types(array $allowedFileTypes = [])
    {
        $this->allowedFileTypes = $allowedFileTypes;
    }
    
    public function set_max_size(int $maxFileSize = 1048576)
    {
        $this->maxFileSize = $maxFileSize;
    }

    public function upload(string $fileInputName)
    {
        if (!isset($_FILES[$fileInputName])) return [null, "No file provided."];

        $file = $_FILES[$fileInputName];

        // Check for errors
        if ($file['error'] !== UPLOAD_ERR_OK) return [false, "File upload error: {$file['error']}"];

        // Check file type
        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!empty($this->allowedFileTypes) && !in_array($fileType, $this->allowedFileTypes)) {
            return [false, "Invalid file type. Allowed types: " . implode(", ", $this->allowedFileTypes)];
        }

        // Check file size
        if ($file['size'] > $this->maxFileSize) {
            return [false, "File is too large. Maximum size: " . $this->formatSize($this->maxFileSize)];
        }

        // Make directory if not exists
        if (!file_exists($this->targetDirectory)) {
            mkdir($this->targetDirectory, 0777, true);
        }

        // Move the uploaded file to the target directory
        $file_name = date("Y-m-d_H-i-s_") . Random::numString(5) . '_' . basename($file['name']);
        $targetPath = "$this->targetDirectory/$file_name";
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return [true, $targetPath];
        } else {
            return "Error moving the uploaded file.";
        }
    }

    private function formatSize(int $size)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
        return round($size, 2) . ' ' . $units[$i];
    }
}
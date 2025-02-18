<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allowedExtensions = ['jpg', 'jpeg', 'png'];

    // Get file details
    $uploadedFile = $_FILES['picture'];
    $fileName = basename($uploadedFile['name']);
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Check if the uploaded file format is allowed
    if (!in_array($fileExtension, $allowedExtensions)) {
        $error_message = "Invalid file extension. Only .jpg, .jpeg, and .png are allowed.";
        include "formulaire.php";
        exit();
    } else {
        // Ensure the 'uploads' directory exists
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create the directory with proper permissions
        }

        // Define the file path
        $filePath = $uploadDir . $fileName;
        
        // Move the uploaded file to the 'uploads' directory
        if (!move_uploaded_file($uploadedFile['tmp_name'], $filePath)) {
            echo "Error uploading the file.";
        }
    }
}

?>
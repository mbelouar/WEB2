<?php
if (isset($_POST["upload"])) {
    $target_dir = "uploads/";
    
    // Check if the uploads directory exists, if not, create it
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory with full permissions
    }

    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["file"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only specific file formats
    $allowedTypes = ["txt", "pdf", "doc", "docx"];
    if (!in_array($fileType, $allowedTypes)) {
        echo "Sorry, only txt, pdf, doc, docx files are allowed.";
        $uploadOk = 0;
    }

    // Stop execution if there was an error
    if ($uploadOk == 0) {
        exit;
    }

    // Try to upload file
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

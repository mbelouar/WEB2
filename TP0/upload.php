<?php
session_start(); // Start session at the very top

if (isset($_POST["upload"])) {
    $target_dir = "uploads/";
    
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["uploadedFile"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (file_exists($target_file)) {
        $_SESSION["message"] = "⚠️ Sorry, file already exists.";
        $_SESSION["msg_type"] = "error";
        $uploadOk = 0;
    }
    
    if ($_FILES["file"]["size"] > 5000000) {
        $_SESSION["message"] = "⚠️ Sorry, your file is too large.";
        $_SESSION["msg_type"] = "error";
        $uploadOk = 0;
    }
    
    $allowedTypes = ["txt"];
    if (!in_array($fileType, $allowedTypes)) {
        $_SESSION["message"] = "⚠️ Only TXT files are allowed.";
        $_SESSION["msg_type"] = "error";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_file)) {
            $_SESSION["message"] = "✅ The file " . htmlspecialchars(basename($_FILES["uploadedFile"]["name"])) . " has been uploaded.";
            $_SESSION["msg_type"] = "success";
        } else {
            $_SESSION["message"] = "❌ Sorry, there was an error uploading your file.";
            $_SESSION["msg_type"] = "error";
        }
    }

    // Redirect back to index.php without sending any output before it
    header("Location: index.php");
    exit; // Ensure script stops executing
}
?>

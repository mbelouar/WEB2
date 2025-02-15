<?php
session_start(); // Ensure session starts
include_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_email"])) {
    $email = trim($_POST["email"]);

    // Get the uploaded file name from session
    $fileName = isset($_SESSION["uploadedFile"]) ? $_SESSION["uploadedFile"] : '';

    $emailsFile = "uploads/" . $fileName;

    if (empty($fileName)) {
        $_SESSION["message"] = "⚠️ Please upload a file first!";
        $_SESSION["msg_type"] = "error";
    } elseif (!file_exists($emailsFile)) {
        $_SESSION["message"] = "❌ File does not exist!";
        $_SESSION["msg_type"] = "error";
    } else {
        $emails = readEmails($emailsFile);

        if (!isValidEmail($email)) {
            $_SESSION["message"] = "❌ Invalid email!";
            $_SESSION["msg_type"] = "error";
        } elseif (in_array($email, $emails)) {
            $_SESSION["message"] = "⚠️ Email already exists!";
            $_SESSION["msg_type"] = "error";
        } else {
            $emails[] = $email;
            writeEmails($emailsFile, $emails);
            $_SESSION["message"] = "✅ Email added successfully!";
            $_SESSION["msg_type"] = "success";
        }
    }
}

header("Location: index.php"); // Redirect back to the main page
exit;
?>

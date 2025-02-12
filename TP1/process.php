<?php
include_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_email"])) {
    $email = trim($_POST["email"]);
    $emailsFile = "Emails.txt";

    $emails = readEmails($emailsFile);

    if (!isValidEmail($email)) {
        echo "<p style='color:red;'> Invalid email!</p>";
    } elseif (in_array($email, $emails)) {
        echo "<p style='color:red;'> Email already exists!</p>";
    } else {
        $emails[] = $email;
        // Write emails back to file
        writeEmails($emailsFile, $emails);
        echo "<p style='color:green;'> Email added successfully!</p>";
    }
}
?>


<?php
session_start(); 

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function readEmails($file) {
    return file_exists($file) ? file($file, FILE_SKIP_EMPTY_LINES) : [];
}

function writeEmails($file, $emails) {
    file_put_contents($file, implode("\n", array_map('trim', $emails))); 
}

function displayMessage() {
    if (isset($_SESSION["message"])) {
        echo '<p style="color: ' . ($_SESSION["msg_type"] == "success" ? 'green' : 'red') . '; font-weight: bold;">';
        echo $_SESSION["message"];
        echo '</p>';

        // Clear the message after displaying
        unset($_SESSION["message"], $_SESSION["msg_type"]);
    }
}

?>

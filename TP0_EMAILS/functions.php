<?php
session_start(); 

function isValidEmail($email) {
    // Improved regex for email validation
    $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    return preg_match($regex, $email) === 1; // returns true if valid
}


function readEmails($file) {
    $emails = [];
    
    // Open file in read mode
    $handle = fopen($file, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            // Trim and validate each email
            $email = trim($line);
            if (isValidEmail($email)) {
                $emails[] = $email;
            }
        }
        fclose($handle);
    }
    
    return $emails;
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

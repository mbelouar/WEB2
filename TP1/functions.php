<?php
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Read and return emails from file
function readEmails($file) {
    return file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
}

// Write emails back to file
function writeEmails($file, $emails) {
    file_put_contents($file, implode("\n", $emails) . PHP_EOL);
}
?>

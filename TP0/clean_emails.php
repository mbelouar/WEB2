<?php
include_once 'functions.php';

$emails = file_exists("Emails.txt") ? file("Emails.txt", FILE_SKIP_EMPTY_LINES) : [];
$validEmails = [];
$invalidEmails = [];
$emailFrequency = [];

// Clean and organize emails
foreach ($emails as $email) {
    $email = trim($email);
    if (isValidEmail($email)) {
        $validEmails[$email] = true; // verify unique emails
        $emailFrequency[$email] = isset($emailFrequency[$email]) ? $emailFrequency[$email] + 1 : 1; // count email frequency
    } else {
        $invalidEmails[] = $email;
    }
}

//save the invalid emails in a file
if (!empty($invalidEmails)) {
    file_put_contents("adressesNonValides.txt", implode(PHP_EOL, $invalidEmails) . PHP_EOL);
}

// Sort and save the valid emails in a file, create the file if it doesn't exist
$sortedEmails = array_keys($validEmails);
sort($sortedEmails);
if (!file_exists("EmailsT.txt")) {
    touch("EmailsT.txt");
}
file_put_contents("EmailsT.txt", implode(PHP_EOL, $sortedEmails) . PHP_EOL);

// separate emails by domain
$domainDir = "domains";
if (!is_dir($domainDir)) {
    mkdir($domainDir, 0777, true);
}

foreach ($sortedEmails as $email) {
    $domain = substr(strrchr($email, "@"), 1);
    $domainFile = "$domainDir/$domain.txt";

    // Get existing emails for the domain
    $existingEmails = file_exists($domainFile) ? file($domainFile, FILE_SKIP_EMPTY_LINES) : [];

    // Add email if it doesn't exist
    if (!in_array($email, $existingEmails)) {
        file_put_contents($domainFile, $email . PHP_EOL, FILE_APPEND);
    }
}

?>

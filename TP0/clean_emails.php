<?php
include_once 'functions.php';

function cleanAndOrganizeEmails($file) {
    $emails = file_exists($file) ? file($file, FILE_SKIP_EMPTY_LINES) : [];
    $validEmails = [];
    $invalidEmails = [];
    $emailFrequency = [];

    foreach ($emails as $email) {
        $email = trim($email);
        if (isValidEmail($email)) {
            $validEmails[$email] = true; 
            $emailFrequency[$email] = isset($emailFrequency[$email]) ? $emailFrequency[$email] + 1 : 1; // count email frequency
        } else {
            $invalidEmails[] = $email;
        }
    }

    return [$validEmails, $invalidEmails, $emailFrequency];
}

function displayInvalidEmails($file) {
    list($validEmails, $invalidEmails, $emailFrequency) = cleanAndOrganizeEmails($file);
    
    if (!empty($invalidEmails)) {
        echo "<ul>";
        foreach ($invalidEmails as $email) {
            echo "<li>$email</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No invalid emails found.</p>";
    }
}

function displayValidEmails($file) {
    list($validEmails, $invalidEmails, $emailFrequency) = cleanAndOrganizeEmails($file);
    
    if (!empty($validEmails)) {
        echo "<ul>";
        foreach ($validEmails as $email => $status) {
            echo "<li>$email</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No valid emails found.</p>";
    }
}

function displayDuplicateEmails($file) {
    list($validEmails, $invalidEmails, $emailFrequency) = cleanAndOrganizeEmails($file);
    
    $duplicates = array_filter($emailFrequency, function($count) {
        return $count > 1;
    });

    if (!empty($duplicates)) {
        echo "<ul>";
        foreach ($duplicates as $email => $count) {
            echo "<li>$email - $count occurrences</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No duplicate emails found.</p>";
    }
}

function displayDomainEmails($file) {
    list($validEmails, $invalidEmails, $emailFrequency) = cleanAndOrganizeEmails($file);
    
    $domains = array_map(function($email) {
        return explode('@', $email)[1];
    }, array_keys($validEmails));

    $domainFrequency = array_count_values($domains);

    if (!empty($domainFrequency)) {
        echo "<ul>";
        foreach ($domainFrequency as $domain => $count) {
            echo "<li>$domain - $count occurrences</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No domain emails found.</p>";
    }
}

?>

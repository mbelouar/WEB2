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
            // echo "Invalid email: $email\n";
        }
    }

    // display emails for debugging
    // echo "<pre>";
    // print_r($validEmails);
    // echo "</pre>";

    return [$validEmails, $invalidEmails, $emailFrequency];
}

function displayInvalidEmails($file) {
    list($validEmails, $invalidEmails, $emailFrequency) = cleanAndOrganizeEmails($file);
    
    // Debugging: Check if there are invalid emails
    // echo "<pre>";
    // print_r($invalidEmails);
    // echo "</pre>";
    
    if (!empty($invalidEmails)) {
        echo "<div class='emails-container'>";  // Make invalid emails scrollable
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>";
            
        // Loop through invalid emails and display each in a table row
        foreach ($invalidEmails as $email) {
            echo "<tr><td>$email</td></tr>";
        }
        echo "</tbody></table>";
        echo "</div>";  // End scrollable container
    } else {
        echo "<p>No invalid emails found.</p>";
    }
    // Write invalid emails to file
    if (!file_exists("Files"))
        mkdir ("Files");
    file_put_contents("Files/adressesNonValides.txt", implode("\n", $invalidEmails));
}



function displayValidEmails($file) {
    list($validEmails, $invalidEmails, $emailFrequency) = cleanAndOrganizeEmails($file);
    
    if (!empty($validEmails)) {
        echo "<div class='emails-container'>";
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>";
        
        // Loop through valid emails and display each in a table row
        foreach ($validEmails as $email => $status) {
            echo "<tr><td>$email</td></tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
    } else {
        echo "<p>No valid emails found.</p>";
    }

    // Create the directory if it doesn't exist
    if (!file_exists("Files")) {
        mkdir("Files");
    }

    // Save the valid emails to a file EmailsT.txt in ascending order
    $validEmails = array_keys($validEmails);
    sort($validEmails);
    file_put_contents("Files/EmailsT.txt", implode("\n", $validEmails));
}


function displayDuplicateEmails($file) {
    list($validEmails, $invalidEmails, $emailFrequency) = cleanAndOrganizeEmails($file);
    
    $duplicates = array_filter($emailFrequency, function($count) {
        return $count > 1;
    });

    if (!empty($duplicates)) {
        echo "<div class='emails-container'>";
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>";
        
        // Loop through duplicate emails and display each in a table row
        foreach ($duplicates as $email => $count) {
            echo "<tr><td>$email</td><td>$count</td></tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
    } else {
        echo "<p>No duplicate emails found.</p>";
    }

    if (!file_exists("Files"))
        mkdir ("Files");
    file_put_contents("Files/adressesDupliquees.txt", implode("\n", array_keys($duplicates)));
}

function displayDomainEmails($file) {
    list($validEmails, $invalidEmails, $emailFrequency) = cleanAndOrganizeEmails($file);
    
    // Extract domains from valid emails
    $domains = array_map(function($email) {
        return explode('@', $email)[1];
    }, array_keys($validEmails));

    $domainFrequency = array_count_values($domains);

    $domainDir = "domains";
    if (!is_dir($domainDir)) {
        mkdir($domainDir);
    }

    foreach (array_keys($validEmails) as $email) {
        $domain = substr(strrchr($email, "@"), 1);
        $domainFile = "$domainDir/$domain.txt";

        $existingEmails = file_exists($domainFile) ? file($domainFile, FILE_SKIP_EMPTY_LINES) : [];

        if (!in_array($email . PHP_EOL, $existingEmails)) {
            file_put_contents($domainFile, $email . PHP_EOL, FILE_APPEND);
        }
    }

    // Display domain frequency on the page
    if (!empty($domainFrequency)) {
        echo "<div class='emails-container'>";  // Start scrollable container
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>Domain</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>";
        
        // Loop through domain frequency and display each in a table row
        foreach ($domainFrequency as $domain => $count) {
            echo "<tr><td>$domain</td><td>$count</td></tr>";
        }
        echo "</tbody></table>";
        echo "</div>";  // End scrollable container
    } else {
        echo "<p>No domain emails found.</p>";
    }
}


?>

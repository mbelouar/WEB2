<?php
include_once 'functions.php';

$emailsFile = "Emails.txt";
$emails = readEmails($emailsFile);
$emailCounts = array_count_values($emails);

if (count($emailCounts) > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Email</th><th>Frequency</th></tr>";
    foreach ($emailCounts as $email => $count) {
        echo "<tr><td>$email</td><td>$count</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No emails stored.</p>";
}
?>

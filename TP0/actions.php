<?php

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'valid_emails') {
        include 'EmailT.txt';
    } elseif ($_POST['action'] == 'invalid_emails') {
        // display invalid emails
        include '';
    } elseif ($_POST['action'] == 'add_email') {
        include 'process.php';
    }
}
?>
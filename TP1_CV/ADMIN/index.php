<?php
/**
 * Main entry point for the Admin section
 */

// Start session for message passing
session_start();

// Redirect to the list of students
header('Location: IHM/list_students.php');
exit();
?>

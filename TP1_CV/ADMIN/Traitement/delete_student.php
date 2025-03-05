<?php
/**
 * Processing file for deleting a student
 */

// Start session to store messages
session_start();

// Include database functions
require_once '../BD/database.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "ID de candidat invalide.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php');
    exit();
}

$studentId = $_GET['id'];

// Delete the student
$deleted = deleteStudent($studentId);

// Set success/error message
if ($deleted) {
    $_SESSION['message'] = "Le candidat a été supprimé avec succès.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Erreur lors de la suppression du candidat.";
    $_SESSION['message_type'] = "danger";
}

// Redirect back to the admin page
header('Location: ../index.php');
exit();
?> 
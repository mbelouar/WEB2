<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database functions
require_once 'getInfo.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ADMIN/index.php');
    exit();
}

$userId = $_GET['id'];

// Get CV data from database
$cv_data = getCvData($userId);

// If CV not found, redirect to admin
if (empty($cv_data)) {
    header('Location: ADMIN/index.php');
    exit();
}

// Store data in session for compatibility with existing code
$_SESSION['cv_data'] = $cv_data;

// Redirect to the CV generation page
header('Location: cv_gen.php');
exit();
?> 
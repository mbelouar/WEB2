<?php
/**
 * Processing file for redirecting to edit a student
 */

// Start session to store data
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug log function
function debug_log($message, $data = null) {
    error_log('[EDIT_STUDENT] ' . $message);
    if ($data !== null) {
        if (is_array($data) || is_object($data)) {
            error_log('[EDIT_STUDENT] ' . print_r($data, true));
        } else {
            error_log('[EDIT_STUDENT] ' . $data);
        }
    }
}

debug_log('Starting edit_student.php');

// Include the main getInfo.php file first since it has the most comprehensive functions
debug_log('Including getInfo.php');
require_once '../../getInfo.php';

// Include database functions - this is safe now because getInfo.php checks if functions exist
debug_log('Including database.php');
require_once '../BD/database.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    debug_log('Invalid student ID');
    $_SESSION['message'] = "ID de candidat invalide.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php');
    exit();
}

$studentId = $_GET['id'];
debug_log('Processing student ID: ' . $studentId);

// Get the student data
debug_log('Retrieving student data');
$student = getStudentById($studentId);

if (!$student) {
    debug_log('Student not found');
    $_SESSION['message'] = "Candidat non trouvé.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php');
    exit();
}

// Clear any existing CV data in session to avoid conflicts
debug_log('Clearing existing CV data in session');
unset($_SESSION['cv_data']);

// Get the full CV data
debug_log('Retrieving full CV data');
$cv_data = getCvData($studentId);
debug_log('CV data retrieved', $cv_data);

// Make sure we have data
if (empty($cv_data)) {
    debug_log('CV data is empty');
    $_SESSION['message'] = "Données du candidat non trouvées.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php');
    exit();
}

// Store in session to pre-fill the form
debug_log('Storing CV data in session');
$_SESSION['cv_data'] = $cv_data;
$_SESSION['cv_data']['user_id'] = $studentId; // Set user ID for update instead of insert
$_SESSION['edit_mode'] = true; // Flag to indicate edit mode
$_SESSION['preserve_edit_mode'] = true; // Flag to preserve edit mode

// Map database field names to form field names
$_SESSION['cv_data']['name'] = $_SESSION['cv_data']['lastname']; // Map lastname to name for the form
$_SESSION['cv_data']['address'] = $_SESSION['cv_data']['adresse']; // Map adresse to address for the form

// Convert niveau values to match form radio button values
if (isset($_SESSION['cv_data']['niveau'])) {
    switch ($_SESSION['cv_data']['niveau']) {
        case '1er année':
            $_SESSION['cv_data']['niveau'] = 'niveau_1';
            break;
        case '2ème année':
            $_SESSION['cv_data']['niveau'] = 'niveau_2';
            break;
        case '3ème année':
            $_SESSION['cv_data']['niveau'] = 'niveau_3';
            break;
        default:
            // If the value is already in the correct format, keep it
            if (!in_array($_SESSION['cv_data']['niveau'], ['niveau_1', 'niveau_2', 'niveau_3'])) {
                $_SESSION['cv_data']['niveau'] = 'niveau_1'; // Default to first year if unknown
            }
    }
    debug_log('Converted niveau value to: ' . $_SESSION['cv_data']['niveau']);
}

// Map profile description
$_SESSION['cv_data']['profile_desc'] = $_SESSION['cv_data']['message'] ?? '';
debug_log('Mapped profile description: ' . $_SESSION['cv_data']['profile_desc']);

// Ensure all array fields are properly set
$arrayFields = [
    'projects', 'projectDesc', 'projectStartDate', 'projectEndDate',
    'stages', 'stageDesc', 'stageStartDate', 'stageEndDate', 'stageEntreprise', 'stageLocation',
    'experiences', 'experienceDesc', 'experienceStartDate', 'experienceEndDate', 
    'experienceEntreprise', 'experienceLocation', 'experiencePosition',
    'competences', 'interests', 'modules'
];

foreach ($arrayFields as $field) {
    if (!isset($_SESSION['cv_data'][$field]) || !is_array($_SESSION['cv_data'][$field])) {
        debug_log('Initializing array field: ' . $field);
        $_SESSION['cv_data'][$field] = [];
    }
}

// Set counters for dynamic fields
$_SESSION['cv_data']['projectCount'] = count($_SESSION['cv_data']['projects']);
$_SESSION['cv_data']['stageCount'] = count($_SESSION['cv_data']['stages']);
$_SESSION['cv_data']['experienceCount'] = count($_SESSION['cv_data']['experiences']);

// For debugging
debug_log('Setting edit mode for user ID: ' . $studentId);
debug_log('CV data in session', $_SESSION['cv_data']);
debug_log('Session ID: ' . session_id());

// Redirect to debug page first to verify data
debug_log('Redirecting to debug page');
header('Location: ../../debug_session.php');
exit();

// Uncomment to go directly to the form
// Redirect to the form page with edit mode
// header('Location: ../../formulaire.php');
// exit();
?> 
<?php
// Start session
session_start();

// Function to print array in a readable format
function prettyPrint($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

// Header
echo '<html><head><title>Session Debug</title>';
echo '<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h1, h2 { color: #333; }
    .debug-section { border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; background: #f9f9f9; }
    pre { background: #f0f0f0; padding: 10px; overflow: auto; }
    .back-btn { display: inline-block; margin: 10px 0; padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
    .back-btn:hover { background: #0056b3; }
</style>';
echo '</head><body>';

echo '<h1>Session Debug Information</h1>';

echo '<a href="formulaire.php" class="back-btn">Back to Form</a> ';
echo '<a href="ADMIN/IHM/list_students.php" class="back-btn">Back to Student List</a>';

// Session ID
echo '<div class="debug-section">';
echo '<h2>Session ID</h2>';
echo 'Session ID: ' . session_id();
echo '</div>';

// Edit Mode
echo '<div class="debug-section">';
echo '<h2>Edit Mode Status</h2>';
if (isset($_SESSION['edit_mode'])) {
    echo 'Edit Mode: ' . ($_SESSION['edit_mode'] ? 'ENABLED' : 'DISABLED');
} else {
    echo 'Edit Mode: NOT SET';
}

if (isset($_SESSION['preserve_edit_mode'])) {
    echo '<br>Preserve Edit Mode: ' . ($_SESSION['preserve_edit_mode'] ? 'ENABLED' : 'DISABLED');
} else {
    echo '<br>Preserve Edit Mode: NOT SET';
}

if (isset($_SESSION['cv_data']['user_id'])) {
    echo '<br>User ID: ' . $_SESSION['cv_data']['user_id'];
} else {
    echo '<br>User ID: NOT SET';
}
echo '</div>';

// CV Data Overview
echo '<div class="debug-section">';
echo '<h2>CV Data Overview</h2>';
if (isset($_SESSION['cv_data']) && !empty($_SESSION['cv_data'])) {
    echo '<ul>';
    foreach ($_SESSION['cv_data'] as $key => $value) {
        if (is_array($value)) {
            echo '<li>' . $key . ': Array with ' . count($value) . ' elements</li>';
        } else {
            echo '<li>' . $key . ': ' . (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value) . '</li>';
        }
    }
    echo '</ul>';
} else {
    echo 'CV Data: NOT SET or EMPTY';
}
echo '</div>';

// Array Fields
$arrayFields = [
    'projects', 'projectDesc', 'projectStartDate', 'projectEndDate',
    'stages', 'stageDesc', 'stageStartDate', 'stageEndDate', 'stageEntreprise', 'stageLocation',
    'experiences', 'experienceDesc', 'experienceStartDate', 'experienceEndDate', 
    'experienceEntreprise', 'experienceLocation', 'experiencePosition',
    'competences', 'interests', 'modules'
];

foreach ($arrayFields as $field) {
    echo '<div class="debug-section">';
    echo '<h2>' . $field . '</h2>';
    if (isset($_SESSION['cv_data'][$field]) && is_array($_SESSION['cv_data'][$field])) {
        prettyPrint($_SESSION['cv_data'][$field]);
    } else {
        echo $field . ': NOT SET or NOT AN ARRAY';
    }
    echo '</div>';
}

// Full Session Data
echo '<div class="debug-section">';
echo '<h2>Full Session Data</h2>';
prettyPrint($_SESSION);
echo '</div>';

echo '</body></html>';
?> 
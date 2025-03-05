<?php
// Start session
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
function db_connect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cv_generator";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Check for user ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "ID de candidat invalide.";
    $_SESSION['message_type'] = "danger";
    header("Location: ADMIN/IHM/list_students.php");
    exit();
}

$userId = $_GET['id'];
$conn = db_connect();

// Set edit mode flags
$_SESSION['edit_mode'] = true;
$_SESSION['preserve_edit_mode'] = true;

// 1. Get user basic info
$result = $conn->query("SELECT * FROM users WHERE id = $userId");
if ($result->num_rows === 0) {
    $_SESSION['message'] = "Candidat non trouvé.";
    $_SESSION['message_type'] = "danger";
    header("Location: ADMIN/IHM/list_students.php");
    exit();
}

$user = $result->fetch_assoc();
$_SESSION['cv_data'] = [
    'user_id' => $userId,
    'firstname' => $user['firstname'],
    'lastname' => $user['lastname'],
    'email' => $user['email'],
    'phone' => $user['phone'],
    'age' => $user['age'],
    'adresse' => $user['address'],
    'github' => $user['github'] ?? '',
    'linkedin' => $user['linkedin'] ?? '',
    'message' => $user['profile_desc'] ?? '',
    'picture' => $user['picture_path'] ?? ''
];

// 2. Get academic info
$result = $conn->query("SELECT * FROM academic_info WHERE user_id = $userId");
if ($result->num_rows > 0) {
    $academic = $result->fetch_assoc();
    $_SESSION['cv_data']['formation'] = $academic['formation'];
    $_SESSION['cv_data']['niveau'] = $academic['niveau'];
    $academicId = $academic['id'];
    
    // Get modules
    $modules = [];
    $result = $conn->query("SELECT module_name FROM modules WHERE academic_id = $academicId");
    while ($row = $result->fetch_assoc()) {
        $modules[] = $row['module_name'];
    }
    $_SESSION['cv_data']['modules'] = $modules;
}

// 3. Get projects
$projects = [];
$projectDesc = [];
$projectStartDate = [];
$projectEndDate = [];

$result = $conn->query("SELECT * FROM projects WHERE user_id = $userId");
while ($row = $result->fetch_assoc()) {
    $projects[] = $row['name'];
    $projectDesc[] = $row['description'];
    $projectStartDate[] = $row['start_date'];
    $projectEndDate[] = $row['end_date'];
}
$_SESSION['cv_data']['projects'] = $projects;
$_SESSION['cv_data']['projectDesc'] = $projectDesc;
$_SESSION['cv_data']['projectStartDate'] = $projectStartDate;
$_SESSION['cv_data']['projectEndDate'] = $projectEndDate;
$_SESSION['cv_data']['projectCount'] = count($projects);

// 4. Get internships
$stages = [];
$stageDesc = [];
$stageStartDate = [];
$stageEndDate = [];
$stageEntreprise = [];
$stageLocation = [];

$result = $conn->query("SELECT * FROM internships WHERE user_id = $userId");
while ($row = $result->fetch_assoc()) {
    $stages[] = $row['name'];
    $stageDesc[] = $row['description'];
    $stageStartDate[] = $row['start_date'];
    $stageEndDate[] = $row['end_date'];
    $stageEntreprise[] = $row['company'];
    $stageLocation[] = $row['location'];
}
$_SESSION['cv_data']['stages'] = $stages;
$_SESSION['cv_data']['stageDesc'] = $stageDesc;
$_SESSION['cv_data']['stageStartDate'] = $stageStartDate;
$_SESSION['cv_data']['stageEndDate'] = $stageEndDate;
$_SESSION['cv_data']['stageEntreprise'] = $stageEntreprise;
$_SESSION['cv_data']['stageLocation'] = $stageLocation;
$_SESSION['cv_data']['stageCount'] = count($stages);

// 5. Get experiences
$experiences = [];
$experienceDesc = [];
$experienceStartDate = [];
$experienceEndDate = [];
$experienceEntreprise = [];
$experienceLocation = [];
$experiencePosition = [];

$result = $conn->query("SELECT * FROM experiences WHERE user_id = $userId");
while ($row = $result->fetch_assoc()) {
    $experiences[] = $row['name'];
    $experienceDesc[] = $row['description'];
    $experienceStartDate[] = $row['start_date'];
    $experienceEndDate[] = $row['end_date'];
    $experienceEntreprise[] = $row['company'];
    $experienceLocation[] = $row['location'];
    $experiencePosition[] = $row['position'];
}
$_SESSION['cv_data']['experiences'] = $experiences;
$_SESSION['cv_data']['experienceDesc'] = $experienceDesc;
$_SESSION['cv_data']['experienceStartDate'] = $experienceStartDate;
$_SESSION['cv_data']['experienceEndDate'] = $experienceEndDate;
$_SESSION['cv_data']['experienceEntreprise'] = $experienceEntreprise;
$_SESSION['cv_data']['experienceLocation'] = $experienceLocation;
$_SESSION['cv_data']['experiencePosition'] = $experiencePosition;
$_SESSION['cv_data']['experienceCount'] = count($experiences);

// 6. Get skills
$competences = [];
$result = $conn->query("SELECT skill_name FROM skills WHERE user_id = $userId");
while ($row = $result->fetch_assoc()) {
    $competences[] = $row['skill_name'];
}
$_SESSION['cv_data']['competences'] = $competences;

// 7. Get interests
$interests = [];
$result = $conn->query("SELECT interest_name FROM interests WHERE user_id = $userId");
while ($row = $result->fetch_assoc()) {
    $interests[] = $row['interest_name'];
}
$_SESSION['cv_data']['interests'] = $interests;

// 8. Get languages
$result = $conn->query("SELECT * FROM languages WHERE user_id = $userId");
if ($result->num_rows > 0) {
    $languages = $result->fetch_all(MYSQLI_ASSOC);
    if (isset($languages[0])) {
        $_SESSION['cv_data']['langue1'] = $languages[0]['language_name'];
        $_SESSION['cv_data']['niveau1'] = $languages[0]['level'];
    }
    if (isset($languages[1])) {
        $_SESSION['cv_data']['langue2'] = $languages[1]['language_name'];
        $_SESSION['cv_data']['niveau2'] = $languages[1]['level'];
    }
    if (isset($languages[2])) {
        $_SESSION['cv_data']['langue3'] = $languages[2]['language_name'];
        $_SESSION['cv_data']['niveau3'] = $languages[2]['level'];
    }
}

// Close database connection
$conn->close();

// Debug log
error_log("Edit mode data loaded for user ID: " . $userId);
error_log("Session data: " . print_r($_SESSION['cv_data'], true));

// Redirect to the form
header("Location: formulaire.php");
exit();
?> 
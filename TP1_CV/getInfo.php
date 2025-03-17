<?php 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection function - check if it's already defined
if (!function_exists('connectDB')) {
    function connectDB() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "cv_generator";
        
        // Create connection - use mysqli_report to see detailed errors
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $conn = new mysqli($servername, $username, $password, $dbname);
            return $conn;
        } catch (Exception $e) {
            // Output the actual database error for debugging
            echo "Database connection failed: " . $e->getMessage();
            die();
        }
    }
}

// Function to retrieve CV data from the database
if (!function_exists('getCvData')) {
    function getCvData($userId) {
        error_log('[getCvData] Starting data retrieval for user: ' . $userId);
        $conn = connectDB();
        $data = [];
        
        try {
            // Get user personal information
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($user = $result->fetch_assoc()) {
                error_log('[getCvData] User found: ' . $user['firstname'] . ' ' . $user['lastname']);
                $data = [
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
            } else {
                // No user found with this ID
                error_log('[getCvData] No user found with ID: ' . $userId);
                return [];
            }
            $stmt->close();
            
            // Get academic information
            $stmt = $conn->prepare("SELECT * FROM academic_info WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $academicId = null;
            if ($academic = $result->fetch_assoc()) {
                error_log('[getCvData] Academic info found. Formation: ' . $academic['formation'] . ', Niveau: ' . $academic['niveau']);
                $data['formation'] = $academic['formation'];
                $data['niveau'] = $academic['niveau'];
                
                // Get modules
                $academicId = $academic['id'];
            } else {
                error_log('[getCvData] No academic info found for user: ' . $userId);
            }
            $stmt->close();
            
            // Get modules if we have an academic ID
            if ($academicId) {
                $stmt = $conn->prepare("SELECT module_name FROM modules WHERE academic_id = ?");
                $stmt->bind_param("i", $academicId);
                $stmt->execute();
                $result = $stmt->get_result();
                $modules = [];
                while ($module = $result->fetch_assoc()) {
                    $modules[] = $module['module_name'];
                }
                $data['modules'] = $modules;
                error_log('[getCvData] Found ' . count($modules) . ' modules');
                $stmt->close();
            } else {
                $data['modules'] = [];
                error_log('[getCvData] No modules found (no academic ID)');
            }
            
            // Get projects
            $stmt = $conn->prepare("SELECT * FROM projects WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $projects = [];
            $projectDesc = [];
            $projectStartDate = [];
            $projectEndDate = [];
            while ($project = $result->fetch_assoc()) {
                $projects[] = $project['name'];
                $projectDesc[] = $project['description'];
                $projectStartDate[] = $project['start_date'];
                $projectEndDate[] = $project['end_date'];
            }
            error_log('[getCvData] Found ' . count($projects) . ' projects');
            $data['projectCount'] = count($projects);
            $data['projects'] = $projects;
            $data['projectDesc'] = $projectDesc;
            $data['projectStartDate'] = $projectStartDate;
            $data['projectEndDate'] = $projectEndDate;
            $stmt->close();
            
            // Get internships
            $stmt = $conn->prepare("SELECT * FROM internships WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $stages = [];
            $stageDesc = [];
            $stageStartDate = [];
            $stageEndDate = [];
            $stageEntreprise = [];
            $stageLocation = [];
            while ($stage = $result->fetch_assoc()) {
                $stages[] = $stage['name'];
                $stageDesc[] = $stage['description'];
                $stageStartDate[] = $stage['start_date'];
                $stageEndDate[] = $stage['end_date'];
                $stageEntreprise[] = $stage['company'];
                $stageLocation[] = $stage['location'];
            }
            $data['stageCount'] = count($stages);
            $data['stages'] = $stages;
            $data['stageDesc'] = $stageDesc;
            $data['stageStartDate'] = $stageStartDate;
            $data['stageEndDate'] = $stageEndDate;
            $data['stageEntreprise'] = $stageEntreprise;
            $data['stageLocation'] = $stageLocation;
            $stmt->close();
            
            // Get experiences
            $stmt = $conn->prepare("SELECT * FROM experiences WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $experiences = [];
            $experienceDesc = [];
            $experienceStartDate = [];
            $experienceEndDate = [];
            $experienceEntreprise = [];
            $experienceLocation = [];
            $experiencePosition = [];
            while ($experience = $result->fetch_assoc()) {
                $experiences[] = $experience['name'];
                $experienceDesc[] = $experience['description'];
                $experienceStartDate[] = $experience['start_date'];
                $experienceEndDate[] = $experience['end_date'];
                $experienceEntreprise[] = $experience['company'];
                $experienceLocation[] = $experience['location'];
                $experiencePosition[] = $experience['position'];
            }
            $data['experienceCount'] = count($experiences);
            $data['experiences'] = $experiences;
            $data['experienceDesc'] = $experienceDesc;
            $data['experienceStartDate'] = $experienceStartDate;
            $data['experienceEndDate'] = $experienceEndDate;
            $data['experienceEntreprise'] = $experienceEntreprise;
            $data['experienceLocation'] = $experienceLocation;
            $data['experiencePosition'] = $experiencePosition;
            $stmt->close();
            
            // Get skills
            $stmt = $conn->prepare("SELECT skill_name FROM skills WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $competences = [];
            while ($competence = $result->fetch_assoc()) {
                $competences[] = $competence['skill_name'];
            }
            $data['competences'] = $competences;
            $stmt->close();
            
            // Get interests
            $stmt = $conn->prepare("SELECT interest_name FROM interests WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $interests = [];
            while ($interest = $result->fetch_assoc()) {
                $interests[] = $interest['interest_name'];
            }
            $data['interests'] = $interests;
            $stmt->close();
            
            // Get languages
            $stmt = $conn->prepare("SELECT language_name, level FROM languages WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $languages = $result->fetch_all(MYSQLI_ASSOC);
            if (count($languages) > 0) {
                $data['langue1'] = $languages[0]['language_name'] ?? '';
                $data['niveau1'] = $languages[0]['level'] ?? '';
            }
            if (count($languages) > 1) {
                $data['langue2'] = $languages[1]['language_name'] ?? '';
                $data['niveau2'] = $languages[1]['level'] ?? '';
            }
            if (count($languages) > 2) {
                $data['langue3'] = $languages[2]['language_name'] ?? '';
                $data['niveau3'] = $languages[2]['level'] ?? '';
            }
            $stmt->close();
            
        } catch (Exception $e) {
            error_log("Database retrieval error: " . $e->getMessage());
            return [];
        } finally {
            $conn->close();
        }
        
        return $data;
    }
}

// Validate age input
if (!isset($_POST['age']) || !ctype_digit($_POST['age']) || $_POST['age'] < 1 || $_POST['age'] > 120) {
    $error_message = "Veuillez entrer un âge valide entre 1 et 120.";
    include "formulaire.php";
    exit();
} else {
    $age = $_POST["age"];
}

// Collect form data
$firstname = $_POST["firstname"] ?? "Non renseigné";
$lastname = $_POST["name"] ?? "Non renseigné";
$email = $_POST["email"] ?? "Non renseigné";
$phone = $_POST["phone"] ?? "Non renseigné";
$age = $_POST["age"] ?? "Non renseigné";
$adresse = $_POST["address"] ?? "Non renseigné";
$github = $_POST["github"] ?? "Non renseigné";
$linkedin = $_POST["linkedin"] ?? "Non renseigné";

// Get formation and level data
$formation = $_POST["formation"] ?? "Non renseigné";

$niveau = $_POST["niveau"] ?? "Non renseigné";
if ($niveau == "niveau_1")
    $niveau = "1er année";
elseif ($niveau == "niveau_2")
    $niveau = "2ème année";
elseif ($niveau == "niveau_3")
    $niveau = "3ème année";


$selectedModules = $_POST['modules'] ?? []; // Ensure it's an array
if (!is_array($selectedModules)) {
    $selectedModules = [$selectedModules];
}

$modulesSuivi = implode(", ", $_POST["modules"] ?? []);

// Get the projects
$projectCount = $_POST["project"] ?? "0";
$projects = isset($_POST['projectNames']) ? $_POST['projectNames'] : [];
$projectDesc = isset($_POST['projectDescriptions']) ? $_POST['projectDescriptions'] : [];
$projectStartDate = isset($_POST['projectStartDates']) ? $_POST['projectStartDates'] : [];
$projectEndDate = isset($_POST['projectEndDates']) ? $_POST['projectEndDates'] : [];

// Format dates properly for database
for ($i = 0; $i < count($projectStartDate); $i++) {
    if (!empty($projectStartDate[$i])) {
        $projectStartDate[$i] = date('Y-m-d', strtotime($projectStartDate[$i]));
    }
    if (!empty($projectEndDate[$i])) {
        $projectEndDate[$i] = date('Y-m-d', strtotime($projectEndDate[$i]));
    }
}

// check if the project start date is before the end date
for ($i = 0; $i < count($projectStartDate); $i++) {
    if ($projectStartDate[$i] > $projectEndDate[$i]) {
        $error_message = "La date de début du projet " . ($i + 1) . " doit être avant la date de fin.";
        include "formulaire.php";
        exit();
    }
}

// Get the stages
$stageCount = $_POST["stage"] ?? "0";
$stages = $_POST['stageNames'] ?? []; 
$stageDesc = $_POST['stageDescriptions'] ?? []; 
$stageStartDate = $_POST['stageStartDates'] ?? [];  
$stageEndDate = $_POST['stageEndDates'] ?? [];  
$stageEntreprise = $_POST['stageEntreprises'] ?? [];
$stageLocation = $_POST['stageLocations'] ?? [];

// Format dates properly for database
for ($i = 0; $i < count($stageStartDate); $i++) {
    if (!empty($stageStartDate[$i])) {
        $stageStartDate[$i] = date('Y-m-d', strtotime($stageStartDate[$i]));
    }
    if (!empty($stageEndDate[$i])) {
        $stageEndDate[$i] = date('Y-m-d', strtotime($stageEndDate[$i]));
    }
}

// check if the stage start date is before the end date
for ($i = 0; $i < count($stageStartDate); $i++) {
    if ($stageStartDate[$i] > $stageEndDate[$i]) {
        $error_message = "La date de début du stage " . ($i + 1) . " doit être avant la date de fin.";
        include "formulaire.php";
        exit();
    }
}

// Get the experiences
$experienceCount = $_POST["experience"] ?? "0";
$experiences = $_POST['experienceNames'] ?? []; 
$experienceDesc = $_POST['experienceDescriptions'] ?? []; 
$experienceStartDate = $_POST['experienceStartDates'] ?? []; 
$experienceEndDate = $_POST['experienceEndDates'] ?? [];  
$experienceEntreprise = $_POST['experienceEntreprises'] ?? [];  
$experienceLocation = $_POST['experienceLocations'] ?? [];  
$experiencePosition = $_POST['experiencePositions'] ?? [];

// Format dates properly for database
for ($i = 0; $i < count($experienceStartDate); $i++) {
    if (!empty($experienceStartDate[$i])) {
        $experienceStartDate[$i] = date('Y-m-d', strtotime($experienceStartDate[$i]));
    }
    if (!empty($experienceEndDate[$i])) {
        $experienceEndDate[$i] = date('Y-m-d', strtotime($experienceEndDate[$i]));
    }
}

// check if the experience start date is before the end date
for ($i = 0; $i < count($experienceStartDate); $i++) {
    if ($experienceStartDate[$i] > $experienceEndDate[$i]) {
        $error_message = "La date de début de l'expérience " . ($i + 1) . " doit être avant la date de fin.";
        include "formulaire.php";
        exit();
    }
}

// Get the competences
$competences = $_POST["competences"] ?? [];

if (!empty($competences)) {
    foreach ($competences as $index => $competence) {
        $competences[$index] = htmlspecialchars(trim($competence)); // Sanitize input
    }
}

// Get the interests
$interests = $_POST["interests"] ?? [];

if (!empty($interests)) {
    foreach ($interests as $index => $interest) {
        $interests[$index] = htmlspecialchars(trim($interest)); // Sanitize input
    }
}

// Get the languages
$langue1 = $_POST["langue1"] ?? "";
$niveau1 = $_POST["niveau1"] ?? "";
$langue2 = $_POST["langue2"] ?? "";
$niveau2 = $_POST["niveau2"] ?? "";
$langue3 = $_POST["langue3"] ?? "";
$niveau3 = $_POST["niveau3"] ?? "";

// Get the message without spaces at the beginning and end
$message = $_POST["profile_desc"] ?? "";
$message = trim($message);

// Save the picture path in the session
$picPath = $_SESSION['cv_data']['picture'] ?? "Non renseigné";

// Store all the information in the session as an associative array
$_SESSION['cv_data'] = [
    'firstname'      => $firstname,
    'lastname'       => $lastname,
    'email'          => $email,
    'phone'          => $phone,
    'age'            => $age,
    'adresse'        => $adresse,
    'github'         => $github,
    'linkedin'       => $linkedin,

    'formation'      => $formation,
    'niveau'         => $niveau,
    'modules'        => $selectedModules,

    'projectCount'   => $projectCount,
    'projects'       => $projects,
    'projectDesc'    => $projectDesc,
    'projectStartDate' => $projectStartDate,
    'projectEndDate' => $projectEndDate,

    'stageCount'     => $stageCount,
    'stages'         => $stages,
    'stageDesc'      => $stageDesc,
    'stageStartDate' => $stageStartDate,
    'stageEndDate'   => $stageEndDate,
    'stageEntreprise'=> $stageEntreprise,
    'stageLocation'  => $stageLocation,

    'experienceCount'=> $experienceCount,
    'experiences'    => $experiences,
    'experienceDesc' => $experienceDesc,
    'experienceStartDate' => $experienceStartDate,
    'experienceEndDate' => $experienceEndDate,
    'experienceEntreprise' => $experienceEntreprise,
    'experienceLocation' => $experienceLocation,
    'experiencePosition' => $experiencePosition,

    // Store competences dynamically as an array
    'competences'    => !empty($_POST['competences']) ? array_filter($_POST['competences'], 'trim') : [],

    // Store interests dynamically as an array
    'interests'      => !empty($_POST['interests']) ? array_filter($_POST['interests'], 'trim') : [],

    'langue1'        => $langue1,
    'niveau1'        => $niveau1,
    'langue2'        => $langue2,
    'niveau2'        => $niveau2,
    'langue3'        => $langue3,
    'niveau3'        => $niveau3,
    
    'message'        => $message,
];

// Preserve the user ID if we're in edit mode
if (isset($_POST['edit_mode']) && $_POST['edit_mode'] == 1 && isset($_POST['user_id'])) {
    $_SESSION['cv_data']['user_id'] = $_POST['user_id'];
    $_SESSION['edit_mode'] = true;
}

// Insert data into database
try {
    $conn = connectDB();
    
    // Check if we're in update mode - make sure to use string comparison
    $isUpdateMode = isset($_POST['edit_mode']) && $_POST['edit_mode'] === '1' && isset($_POST['user_id']);
    $userId = $isUpdateMode ? intval($_POST['user_id']) : null;
    
    // Debug log for update mode
    if ($isUpdateMode) {
        error_log('UPDATE MODE: editing user ID: ' . $userId . ' (from POST)');
        error_log('POST data: ' . print_r($_POST, true));
    } else {
        error_log('CREATE MODE: creating new user');
    }
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // 1. Handle user information
        if ($isUpdateMode) {
            error_log("UPDATING existing user ID: " . $userId);
            
            // First delete all related data except the user
            $tables = ['academic_info', 'projects', 'internships', 'experiences', 'skills', 'interests', 'languages'];
            
            foreach ($tables as $table) {
                $stmt = $conn->prepare("DELETE FROM $table WHERE user_id = ?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $stmt->close();
            }
            
            // Get the academic ID for module deletion
            $stmt = $conn->prepare("SELECT id FROM academic_info WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $academicId = $row['id'];
                $stmt = $conn->prepare("DELETE FROM modules WHERE academic_id = ?");
                $stmt->bind_param("i", $academicId);
                $stmt->execute();
            }
            $stmt->close();
            
            // Update user information
            $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, phone = ?, age = ?, address = ?, github = ?, linkedin = ?, profile_desc = ? WHERE id = ?");
            // Don't update picture path if not provided (to avoid overwriting existing picture)
            $stmt->bind_param("sssssssssi", $firstname, $lastname, $email, $phone, $age, $adresse, $github, $linkedin, $message, $userId);
            $stmt->execute();
            $stmt->close();
            
            // Update picture path if provided
            if (!empty($picPath)) {
                $stmt = $conn->prepare("UPDATE users SET picture_path = ? WHERE id = ?");
                $stmt->bind_param("si", $picPath, $userId);
                $stmt->execute();
                $stmt->close();
            }
        } else {
            // Insert a new user
            $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, phone, age, address, github, linkedin, profile_desc, picture_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $firstname, $lastname, $email, $phone, $age, $adresse, $github, $linkedin, $message, $picPath);
            $stmt->execute();
            $userId = $conn->insert_id;
            $stmt->close();
        }
        
        // 2. Insert academic information
        $stmt = $conn->prepare("INSERT INTO academic_info (user_id, formation, niveau) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $formation, $niveau);
        $stmt->execute();
        $academicId = $conn->insert_id;
        $stmt->close();
        
        error_log("Inserted academic info for user ID: " . $userId . ", Academic ID: " . $academicId);
        
        // 3. Insert modules
        if (!empty($selectedModules)) {
            error_log("Inserting " . count($selectedModules) . " modules for academic ID: " . $academicId);
            $stmt = $conn->prepare("INSERT INTO modules (academic_id, module_name) VALUES (?, ?)");
            foreach ($selectedModules as $module) {
                $stmt->bind_param("is", $academicId, $module);
                $stmt->execute();
            }
            $stmt->close();
        }
        
        // 4. Insert projects
        if (!empty($projects)) {
            error_log("Inserting " . count($projects) . " projects for user ID: " . $userId);
            $stmt = $conn->prepare("INSERT INTO projects (user_id, name, description, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
            foreach ($projects as $index => $project) {
                if (!empty($project) && !empty($projectStartDate[$index]) && !empty($projectEndDate[$index])) {
                    $stmt->bind_param("issss", $userId, $project, $projectDesc[$index], $projectStartDate[$index], $projectEndDate[$index]);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }
        
        // 5. Insert internships
        if (!empty($stages)) {
            $stmt = $conn->prepare("INSERT INTO internships (user_id, name, description, start_date, end_date, company, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach ($stages as $index => $stage) {
                if (!empty($stage) && !empty($stageStartDate[$index]) && !empty($stageEndDate[$index])) {
                    $stmt->bind_param("issssss", $userId, $stage, $stageDesc[$index], $stageStartDate[$index], $stageEndDate[$index], $stageEntreprise[$index], $stageLocation[$index]);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }
        
        // 6. Insert experiences
        if (!empty($experiences)) {
            $stmt = $conn->prepare("INSERT INTO experiences (user_id, name, description, start_date, end_date, company, location, position) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($experiences as $index => $experience) {
                if (!empty($experience) && !empty($experienceStartDate[$index]) && !empty($experienceEndDate[$index])) {
                    $stmt->bind_param("isssssss", $userId, $experience, $experienceDesc[$index], $experienceStartDate[$index], $experienceEndDate[$index], $experienceEntreprise[$index], $experienceLocation[$index], $experiencePosition[$index]);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }
        
        // 7. Insert skills
        if (!empty($competences)) {
            $stmt = $conn->prepare("INSERT INTO skills (user_id, skill_name) VALUES (?, ?)");
            foreach ($competences as $competence) {
                if (!empty($competence)) {
                    $stmt->bind_param("is", $userId, $competence);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }
        
        // 8. Insert interests
        if (!empty($interests)) {
            $stmt = $conn->prepare("INSERT INTO interests (user_id, interest_name) VALUES (?, ?)");
            foreach ($interests as $interest) {
                if (!empty($interest)) {
                    $stmt->bind_param("is", $userId, $interest);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }
        
        // 9. Insert languages
        if (!empty($langue1)) {
            $stmt = $conn->prepare("INSERT INTO languages (user_id, language_name, level) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $userId, $langue1, $niveau1);
            $stmt->execute();
            if (!empty($langue2)) {
                $stmt->bind_param("iss", $userId, $langue2, $niveau2);
                $stmt->execute();
            }
            if (!empty($langue3)) {
                $stmt->bind_param("iss", $userId, $langue3, $niveau3);
                $stmt->execute();
            }
            $stmt->close();
        }
        
        // Commit the transaction
        $conn->commit();
        
    } catch (Exception $e) {
        // Roll back the transaction in case of error
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->rollback();
        }
        // Log the error and display a user-friendly message
        error_log("Database error: " . $e->getMessage());
        
        // For debugging purposes - show actual error message
        $error_message = "Erreur base de données: " . $e->getMessage();
        include "formulaire.php";
        exit();
    } finally {
        // Close database connection
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
    }

} catch (Exception $e) {
    // Roll back the transaction in case of error
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->rollback();
    }
    // Log the error and display a user-friendly message
    error_log("Database error: " . $e->getMessage());
    
    // For debugging purposes - show actual error message
    $error_message = "Erreur base de données: " . $e->getMessage();
    include "formulaire.php";
    exit();
}

?>
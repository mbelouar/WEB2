<?php 

session_start();

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

$file = fopen($lastname . ".txt", "w") or die("Unable to open file!");

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

// Format the data
$data = "---------------- Renseignement Personnel ----------------\n";
$data .= "Nom: $lastname\n";
$data .= "Prénom: $firstname\n";
$data .= "Age: $age\n";
$data .= "Email: $email\n";
$data .= "Téléphone: $phone\n";
$data .= "Adresse: $adresse\n";
$data .= "Github: $github\n";
$data .= "Linkedin: $linkedin\n";

$data .= "--------------- Renseignement Académique ---------------\n";
$data .= "Formation: $formation\n";
$data .= "Niveau: $niveau\n";
$data .= "Modules suivis: $modulesSuivi\n";
$data .= "Nombre de projets: $projectCount\n";
$data .= "Projets réalisés: \n";
if (!empty($projects)) {
    foreach ($projects as $key => $project) {
        $data .= "Projet " . ($key + 1) . ": $project\n";
        $data .= "Description: " . $projectDesc[$key] . "\n";
        $data .= "Date de début: " . $projectStartDate[$key] . "\n";
        $data .= "Date de fin: " . $projectEndDate[$key] . "\n";
    }
}

$data .= "------------------------ Stages ------------------------\n";
if (!empty($stages)) {
    foreach ($stages as $key => $stage) {
        $data .= "Stage " . ($key + 1) . ": $stage\n";
        $data .= "Description: " . ($stageDesc[$key] ?? "N/A") . "\n";
        $data .= "Date de début: " . ($stageStartDate[$key] ?? "N/A") . "\n";
        $data .= "Date de fin: " . ($stageEndDate[$key] ?? "N/A") . "\n";
        $data .= "Entreprise: " . ($stageEntreprise[$key] ?? "N/A") . "\n";
        $data .= "Location: " . ($stageLocation[$key] ?? "N/A") . "\n";
    }
}

$data .= "--------------------- Expériences ----------------------\n";
if (!empty($experiences)) {
    foreach ($experiences as $key => $experience) {
        $data .= "Expérience " . ($key + 1) . ": $experience\n";
        $data .= "Description: " . ($experienceDesc[$key] ?? "N/A") . "\n";
        $data .= "Date de début: " . ($experienceStartDate[$key] ?? "N/A") . "\n";
        $data .= "Date de fin: " . ($experienceEndDate[$key] ?? "N/A") . "\n";
        $data .= "Entreprise: " . ($experienceEntreprise[$key] ?? "N/A") . "\n";
        $data .= "Location: " . ($experienceLocation[$key] ?? "N/A") . "\n";
        $data .= "Poste: " . ($experiencePosition[$key] ?? "N/A") . "\n";
    }
}

$data .= "---------------------- Compétences ---------------------\n";
if (!empty($_POST["competences"])) {
    foreach ($_POST["competences"] as $index => $competence) {
        if (!empty($competence)) {
            $data .= "Compétence " . ($index + 1) . ": " . htmlspecialchars(trim($competence)) . "\n";
        }
    }
}

$data .= "------------------- Centre d'intérêt -------------------\n";
if (!empty($_POST["interests"])) {
    foreach ($_POST["interests"] as $index => $interest) {
        if (!empty($interest)) {
            $data .= "Intérêt " . ($index + 1) . ": " . htmlspecialchars(trim($interest)) . "\n";
        }
    }
}

$data .= "----------------------- Langues ------------------------\n";
if (!empty($langue1))
    $data .= "Langue 1: $langue1 => Niveau: $niveau1\n";
if (!empty($langue2))
    $data .= "Langue 2: $langue2 => Niveau: $niveau2\n";
if (!empty($langue3))
    $data .= "Langue 3: $langue3 => Niveau: $niveau3\n";

$data .= "------------------------ Profile -----------------------\n";
if (!empty($message))
    $data .= "Profile: $message\n";
else
    $data .= "Aucun description du profile\n";

$data .= "---------------------- picture path --------------------\n";
$data .= "picture path: $picPath\n";

$data .= "---------------------- Fin du CV -----------------------\n";

// Save the data to the file
fwrite($file, $data);
fclose($file);

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


?>
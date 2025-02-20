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

// Get the stages
$stageCount = $_POST["stage"] ?? "0";
$stages = $_POST['stageNames'] ?? [];  // Correct key for names
$stageDesc = $_POST['stageDescriptions'] ?? [];  // Correct key for descriptions

// Get the experiences
$experienceCount = $_POST["experience"] ?? "0";
$experiences = $_POST['experienceNames'] ?? [];  // Correct key for names
$experienceDesc = $_POST['experienceDescriptions'] ?? [];  // Correct key for descriptions

// Get the competences
$competence1 = $_POST["competence1"] ?? "";
$competence2 = $_POST["competence2"] ?? "";
$competence3 = $_POST["competence3"] ?? "";
$competence4 = $_POST["competence4"] ?? "";

// Get the interests
$interest1 = $_POST["interest1"] ?? "";
$interest2 = $_POST["interest2"] ?? "";
$interest3 = $_POST["interest3"] ?? "";
$interest4 = $_POST["interest4"] ?? "";

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
    }
}

$data .= "------------------------ Stages ------------------------\n";
$data .= "Stages réalisés: \n";
if (!empty($stages)) {
    foreach ($stages as $key => $stage) {
        $data .= "Stage " . ($key + 1) . ": $stage\n";
        $data .= "Description: " . ($stageDesc[$key] ?? "N/A") . "\n";
    }
}

$data .= "--------------------- Expériences ----------------------\n";
$data .= "Expériences professionnelles: \n";
if (!empty($experiences)) {
    foreach ($experiences as $key => $experience) {
        $data .= "Expérience " . ($key + 1) . ": $experience\n";
        $data .= "Description: " . ($experienceDesc[$key] ?? "N/A") . "\n";
    }
}

$data .= "---------------------- Competences ---------------------\n";
if (!empty($competence1))
    $data .= "Compétence 1: $competence1\n";
if (!empty($competence2))
    $data .= "Compétence 2: $competence2\n";
if (!empty($competence3))
    $data .= "Compétence 3: $competence3\n";
if (!empty($competence4))
    $data .= "Compétence 4: $competence4\n";


$data .= "------------------- Centre d'intérêt -------------------\n";
if (!empty($interest1))
    $data .= "Intérêt 1: $interest1\n";
if (!empty($interest2))
    $data .= "Intérêt 2: $interest2\n";
if (!empty($interest3))
    $data .= "Intérêt 3: $interest3\n";
if (!empty($interest4))
    $data .= "Intérêt 4: $interest4\n";

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
    'stageCount'     => $stageCount,
    'stages'         => $stages,
    'stageDesc'      => $stageDesc,
    'experienceCount'=> $experienceCount,
    'experiences'    => $experiences,
    'experienceDesc' => $experienceDesc,
    'competence1'    => $competence1,
    'competence2'    => $competence2,
    'competence3'    => $competence3,
    'competence4'    => $competence4,
    'interest1'      => $interest1,
    'interest2'      => $interest2,
    'interest3'      => $interest3,
    'interest4'      => $interest4,
    'langue1'        => $langue1,
    'niveau1'        => $niveau1,
    'langue2'        => $langue2,
    'niveau2'        => $niveau2,
    'langue3'        => $langue3,
    'niveau3'        => $niveau3,
    'message'        => $message,
];

?>
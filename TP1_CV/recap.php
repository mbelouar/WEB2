<?php

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
    $adresse = $_POST["adress"] ?? "Non renseigné";
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


    $selectedModules = implode(", ", $_POST["modules"] ?? []);

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

    // Get the message
    $message = $_POST["message"] ?? "";

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
    $data .= "Modules suivis: $selectedModules\n";
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

    $data .= "----------------------- Remarques ----------------------\n";
    if (!empty($message))
        $data .= "Message: $message\n";
    else
        $data .= "Aucun message\n";
    $data .= "--------------------------------------------------------\n\n";

    // Save the data to the file
    fwrite($file, $data);
    fclose($file);


    // Handle picture upload
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Define allowed file formats
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
    
        // Get file details
        $uploadedFile = $_FILES['picture'];
        $fileName = basename($uploadedFile['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
        // Check if the uploaded file format is allowed
        if (!in_array($fileExtension, $allowedExtensions)) {
            $error_message = "Invalid file extension. Only .jpg, .jpeg, and .png are allowed.";
            include "formulaire.php";
            exit();
        } else {
            // Ensure the 'uploads' directory exists
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create the directory with proper permissions
            }
    
            // Define the file path
            $filePath = $uploadDir . $fileName;
            
            // Move the uploaded file to the 'uploads' directory
            if (!move_uploaded_file($uploadedFile['tmp_name'], $filePath)) {
                echo "Error uploading the file.";
            }
        }
    }


    // Show confirmation message
    echo "<h2>Données enregistrées avec succès!</h2>";
    echo "<p>Merci, $firstname $lastname. Vos informations ont été sauvegardées.</p>";

    // Form to return to formulaire.php with stored values
    echo "<form action='formulaire.php' method='POST'>";
    echo "<input type='hidden' name='firstname' value='$firstname'>";
    echo "<input type='hidden' name='name' value='$lastname'>";
    echo "<input type='hidden' name='age' value='$age'>";
    echo "<input type='hidden' name='email' value='$email'>";
    echo "<input type='hidden' name='phone' value='$phone'>";
    echo "<input type='hidden' name='adresse' value='$adresse'>";
    echo "<input type='hidden' name='github' value='$github'>";
    echo "<input type='hidden' name='linkedin' value='$linkedin'>";

    echo "<input type='hidden' name='formation' value='$formation'>";
    echo "<input type='hidden' name='niveau' value='{$_POST['niveau']}'>"; // Send the original key

    // Send the selected modules
    if (!empty($_POST['modules'])) {
        foreach ($_POST['modules'] as $module) {
            echo "<input type='hidden' name='modules[]' value='$module'>";
        }
    }

    // Send the project count and names
    echo "<input type='hidden' name='project' value='$projectCount'>";
    if (!empty($projects)) {
        foreach ($projects as $project) {
            echo "<input type='hidden' name='projectNames[]' value='$project'>";
        }
    }

    // Send the project descriptions
    if (!empty($projectDesc)) {
        foreach ($projectDesc as $desc) {
            echo "<input type='hidden' name='projectDescriptions[]' value='$desc'>";
        }
    }

    // Send the stage count and names
    echo "<input type='hidden' name='stage' value='$stageCount'>";
    if (!empty($stages)) {
        foreach ($stages as $stage) {
            echo "<input type='hidden' name='stageNames[]' value='$stage'>";
        }
    }

    // Send the stage descriptions
    if (!empty($stageDesc)) {
        foreach ($stageDesc as $desc) {
            echo "<input type='hidden' name='stageDescriptions[]' value='$desc'>";
        }
    }

    // Send the experience count and names
    echo "<input type='hidden' name='experience' value='$experienceCount'>";
    if (!empty($experiences)) {
        foreach ($experiences as $experience) {
            echo "<input type='hidden' name='experienceNames[]' value='$experience'>";
        }
    }

    // Send the experience descriptions
    if (!empty($experienceDesc)) {
        foreach ($experienceDesc as $desc) {
            echo "<input type='hidden' name='experienceDescriptions[]' value='$desc'>";
        }
    }

    // send the competences
    echo "<input type='hidden' name='competence1' value='$competence1'>";
    echo "<input type='hidden' name='competence2' value='$competence2'>";
    echo "<input type='hidden' name='competence3' value='$competence3'>";
    echo "<input type='hidden' name='competence4' value='$competence4'>";

    // send the interests
    echo "<input type='hidden' name='interest1' value='$interest1'>";
    echo "<input type='hidden' name='interest2' value='$interest2'>";
    echo "<input type='hidden' name='interest3' value='$interest3'>";
    echo "<input type='hidden' name='interest4' value='$interest4'>";

    // send the languages
    echo "<input type='hidden' name='langue1' value='$langue1'>";
    echo "<input type='hidden' name='niveau1' value='$niveau1'>";
    echo "<input type='hidden' name='langue2' value='$langue2'>";
    echo "<input type='hidden' name='niveau2' value='$niveau2'>";
    echo "<input type='hidden' name='langue3' value='$langue3'>";
    echo "<input type='hidden' name='niveau3' value='$niveau3'>";

    // send the message
    echo "<input type='hidden' name='message' value='$message'>";

    echo "<button type='submit'>Modifier</button>";
    echo "</form>";

    echo "<a href='formulaire.php'>Retour au formulaire</a>";

} else {
    echo "<h2>Accès non autorisé.</h2>";
}
?>

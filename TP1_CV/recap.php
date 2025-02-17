<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $firstname = $_POST["firstname"] ?? "Non renseigné";
    $lastname = $_POST["name"] ?? "Non renseigné";
    $email = $_POST["email"] ?? "Non renseigné";
    $phone = $_POST["phone"] ?? "Non renseigné";
    $age = $_POST["age"] ?? "Non renseigné";

    $file = fopen($lastname . "_" . $firstname . ".txt", "w") or die("Unable to open file!");

    $formation = $_POST["formation"] ?? "Non renseigné";
    
    $niveau = $_POST["niveau"] ?? "Non renseigné";
    if ($niveau == "niveau_1")
        $niveau = "1er année";
    elseif ($niveau == "niveau_2")
        $niveau = "2ème année";
    elseif ($niveau == "niveau_3")
        $niveau = "3ème année";


    $selectedModules = implode(", ", $_POST["modules"] ?? []);

    $projectCount = $_POST["project"] ?? "0";

    $projects = isset($_POST['projectNames']) ? $_POST['projectNames'] : [];

    $interest1 = $_POST["interest1"] ?? "";
    $interest2 = $_POST["interest2"] ?? "";
    $interest3 = $_POST["interest3"] ?? "";
    $interest4 = $_POST["interest4"] ?? "";

    $langue1 = $_POST["langue1"] ?? "";
    $niveau1 = $_POST["niveau1"] ?? "";
    $langue2 = $_POST["langue2"] ?? "";
    $niveau2 = $_POST["niveau2"] ?? "";
    $langue3 = $_POST["langue3"] ?? "";
    $niveau3 = $_POST["niveau3"] ?? "";

    $message = $_POST["message"] ?? "";

    // Format the data
    $data = "---------------- Renseignement Personnel ----------------\n";
    $data .= "Nom: $lastname\n";
    $data .= "Prénom: $firstname\n";
    $data .= "Email: $email\n";
    $data .= "Téléphone: $phone\n";
    $data .= "Age: $age\n";

    $data .= "--------------- Renseignement Académique ---------------\n";
    $data .= "Formation: $formation\n";
    $data .= "Niveau: $niveau\n";
    $data .= "Modules suivis: $selectedModules\n";
    $data .= "Nombre de projets: $projectCount\n";
    $data .= "Projets réalisés: " . implode(", ", $projects) . "\n";
    
    
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

    // Handle file upload
    

    // Show confirmation message
    echo "<h2>Données enregistrées avec succès!</h2>";
    echo "<p>Merci, $firstname $lastname. Vos informations ont été sauvegardées.</p>";

    // Form to return to formulaire.php with stored values
    echo "<form action='formulaire.php' method='POST'>";
    echo "<input type='hidden' name='firstname' value='$firstname'>";
    echo "<input type='hidden' name='name' value='$lastname'>";
    echo "<input type='hidden' name='email' value='$email'>";
    echo "<input type='hidden' name='phone' value='$phone'>";
    echo "<input type='hidden' name='age' value='$age'>";
    echo "<input type='hidden' name='formation' value='$formation'>";
    echo "<input type='hidden' name='niveau' value='{$_POST['niveau']}'>"; // Send the original key
    if (!empty($_POST['modules'])) {
        foreach ($_POST['modules'] as $module) {
            echo "<input type='hidden' name='modules[]' value='$module'>";
        }
    }
    echo "<input type='hidden' name='project' value='$projectCount'>";
    if (!empty($projects)) {
        foreach ($projects as $project) {
            echo "<input type='hidden' name='projectNames[]' value='$project'>";
        }
    }
    echo "<input type='hidden' name='interest1' value='$interest1'>";
    echo "<input type='hidden' name='interest2' value='$interest2'>";
    echo "<input type='hidden' name='interest3' value='$interest3'>";
    echo "<input type='hidden' name='interest4' value='$interest4'>";
    echo "<input type='hidden' name='langue1' value='$langue1'>";
    echo "<input type='hidden' name='niveau1' value='$niveau1'>";
    echo "<input type='hidden' name='langue2' value='$langue2'>";
    echo "<input type='hidden' name='niveau2' value='$niveau2'>";
    echo "<input type='hidden' name='langue3' value='$langue3'>";
    echo "<input type='hidden' name='niveau3' value='$niveau3'>";
    echo "<input type='hidden' name='message' value='$message'>";

    echo "<button type='submit'>Modifier</button>";
    echo "</form>";

    echo "<a href='formulaire.php'>Retour au formulaire</a>";

} else {
    echo "<h2>Accès non autorisé.</h2>";
}
?>

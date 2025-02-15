<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Open or create a file to save the data
    $file = fopen("data.txt", "a");

    // Collect form data
    $firstname = $_POST["firstname"] ?? "Non renseigné";
    $lastname = $_POST["name"] ?? "Non renseigné";
    $email = $_POST["email"] ?? "Non renseigné";
    $phone = $_POST["phone"] ?? "Non renseigné";
    $age = $_POST["age"] ?? "Non renseigné";

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
    
    
    $data .= "--------------- Centre d'intérêt ---------------\n";
    if (!empty($interest1))
        $data .= "Intérêt 1: $interest1\n";
    if (!empty($interest2))
        $data .= "Intérêt 2: $interest2\n";
    if (!empty($interest3))
        $data .= "Intérêt 3: $interest3\n";
    if (!empty($interest4))
        $data .= "Intérêt 4: $interest4\n";

    $data .= "--------------- Langues ---------------\n";
    if (!empty($langue1))
        $data .= "Langue 1: $langue1 => Niveau: $niveau1\n";
    if (!empty($langue2))
        $data .= "Langue 2: $langue2 => Niveau: $niveau2\n";
    if (!empty($langue3))
        $data .= "Langue 3: $langue3 => Niveau: $niveau3\n";

    $data .= "--------------- Remarques ---------------\n";
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
    echo "<a href='formulaire.php'>Retour au formulaire</a>";
} else {
    echo "<h2>Accès non autorisé.</h2>";
}
?>

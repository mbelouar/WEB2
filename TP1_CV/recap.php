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
    $data = "------------------------------\n";
    $data .= "Nom: $lastname\n";
    $data .= "Prénom: $firstname\n";
    $data .= "Email: $email\n";
    $data .= "Téléphone: $phone\n";
    $data .= "Age: $age\n";
    $data .= "Formation: $formation\n";
    $data .= "Niveau: $niveau\n";
    $data .= "Modules suivis: $selectedModules\n";
    $data .= "Nombre de projets: $projectCount\n";
    $data .= "Projets réalisés: " . implode(", ", $projects) . "\n";
    $data .= "Centres d'intérêt: $interest1, $interest2, $interest3, $interest4\n";
    $data .= "Langues: $langue1 ($niveau1), $langue2 ($niveau2), $langue3 ($niveau3)\n";
    $data .= "Remarques: $message\n";
    $data .= "------------------------------\n\n";

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

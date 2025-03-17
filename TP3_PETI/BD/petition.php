<?php
// Inclure la connexion à la base de données
require_once 'connexion.php';

// Récupérer toutes les pétitions
function getPetitions() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Petition");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer la pétition la plus signée
function getPetitionPlusSignatures() {
    global $pdo;
    $stmt = $pdo->query("SELECT Titre, COUNT(*) AS nb_signatures FROM Signature GROUP BY IDP ORDER BY nb_signatures DESC LIMIT 1");
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['Titre' => 'Aucune pétition', 'nb_signatures' => 0];
}

// Ajouter une nouvelle pétition
function ajouterPetition($titre, $description, $datePublic, $dateFinP, $porteurP, $email) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO Petition (Titre, Description, DatePublic, DateFinP, PorteurP, Email) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $description, $datePublic, $dateFinP, $porteurP, $email]);
}
?>

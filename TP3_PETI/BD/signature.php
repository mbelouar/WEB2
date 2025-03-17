<?php
// Inclure la connexion à la base de données
require_once 'connexion.php';

// Récupérer les signatures d'une pétition spécifique
function getSignatures($idp) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Signature WHERE IDP = ?");
    $stmt->execute([$idp]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ajouter une nouvelle signature
function ajouterSignature($idp, $nom, $prenom, $pays, $date, $heure, $email) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO Signature (IDP, Nom, Prenom, Pays, Date, Heure, Email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$idp, $nom, $prenom, $pays, $date, $heure, $email]);
}
?>

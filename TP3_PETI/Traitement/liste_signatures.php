<?php
require '../BD/signature.php'; // Inclure la connexion à la base de données
$idp = $_GET['id']; // Récupérer l'ID de la pétition
$signatures = getSignatures($idp); // Récupérer les dernières signatures

foreach ($signatures as $sig) {
    echo "<p>{$sig['Nom']} {$sig['Prenom']} ({$sig['Pays']}) - {$sig['Date']} {$sig['Heure']}</p>";
}
?>

<?php
require_once 'Particulier.php';
require_once 'Entreprise.php';

echo "<h1>Test des classes dérivées de Client</h1>";

// Création d'un client particulier
$particulier = new Particulier(1, "Med BELOUARRAQ", "nr 6 appt 7 casablanca", "AB1234", "2001-07-29");
echo "<h2>Client Particulier:</h2>";
echo $particulier . "<br><br>";
echo "Méthode payerFacture(): " . $particulier->payerFacture() . "<br>";
echo "Méthode reclamation(): " . $particulier->reclamation() . "<br><br>";

// Création d'un client entreprise
$entreprise = new Entreprise(2, "Oracle", "456 CasaNearShore", "PT12345", "ORACLE", "Informatique");
echo "<h2>Client Entreprise:</h2>";
echo $entreprise . "<br><br>";
echo "Méthode payerFacture(): " . $entreprise->payerFacture() . "<br>";
echo "Méthode reclamation(): " . $entreprise->reclamation() . "<br><br>";

// Affichage du nombre total de clients
echo "<h2>Statistiques:</h2>";
echo "Nombre total de clients: " . Client::$nbr_user . "<br><br>";
?> 
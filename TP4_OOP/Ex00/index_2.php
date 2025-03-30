<?php
require_once 'Client.php';

// Création du premier client avec get_Instance
$client1 = Client::get_Instance(1, "Mohammed", "Casablanca");
echo "<h2>Premier client créé</h2>";
echo "ID: " . $client1->getId() . "<br>";
echo "Nom: " . $client1->getNom() . "<br>";
echo "Adresse: " . $client1->getAdresse() . "<br>";
echo "Nombre d'utilisateurs: " . Client::$nbr_user . "<br><br>";

echo "<h2>Tentative de création d'un deuxième client (avec des paramètres différents)</h2>";
echo "Tentative de créer: ID=2, Nom=Simo, Adresse=Lyon<br>";

// Tentative de création d'un deuxième client avec paramètres différents
$client2 = Client::get_Instance(2, "Simo", "Lyon");

echo "<h2>Résultat du deuxième client</h2>";
echo "ID: " . $client2->getId() . " (reste 1 car c'est la même instance)<br>";
echo "Nom: " . $client2->getNom() . " (reste Mohammed car c'est la même instance)<br>";
echo "Adresse: " . $client2->getAdresse() . " (reste Casablanca car c'est la même instance)<br>";
echo "Nombre d'utilisateurs: " . Client::$nbr_user . " (toujours 1 car une seule instance a été créée)<br><br>";

// Démonstration que c'est le même objet
echo "<h2>Démonstration du pattern Singleton</h2>";
echo "client1 === client2: " . (($client1 === $client2) ? "true (le même objet en mémoire)" : "false") . "<br>";

// Test de modification sur client2 qui affecte client1
echo "<h2>Test de modification</h2>";
echo "Modification de l'adresse via client2 à 'Nouvelle adresse'<br>";
$client2->setAdresse("Nouvelle adresse");

echo "<h3>Après modification</h3>";
echo "Adresse client1: " . $client1->getAdresse() . " (a changé car c'est le même objet)<br>";
echo "Adresse client2: " . $client2->getAdresse() . "<br>";
?> 
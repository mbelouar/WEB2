<?php
require_once 'Client.php';

// Création de trois utilisateurs
$client1 = Client::get_Instance(1, "SIMO", "Casablanca");
$client2 = Client::get_Instance(2, "MED", "Meknes");
$client3 = Client::get_Instance(3, "BELLO", "Marseille");

// Affichage des informations des clients
echo "<h2>Informations du client 1</h2>";
$client1->afficher();

echo "<h2>Informations du client 2</h2>";
$client2->afficher();

echo "<h2>Informations du client 3</h2>";
$client3->afficher();

// Affichage du nombre total d'utilisateurs
echo "<h2>Nombre total d'utilisateurs</h2>";
echo "Nombre d'utilisateurs: " . Client::$nbr_user . "<br></br>";
?> 
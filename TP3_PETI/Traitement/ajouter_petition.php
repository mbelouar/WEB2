<?php
require '../BD/petition.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    ajouterPetition($_POST['titre'], $_POST['description'], $_POST['datePublic'], $_POST['dateFinP'], $_POST['porteurP'], $_POST['email']);
    header('Location: ../IHM/liste_petition.php');
}
?>

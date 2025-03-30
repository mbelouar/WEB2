<?php
session_start();
include '../partials/header.php';
?>

<h2>Nouvelle Réclamation</h2>
<form action="../../traitement/reclamationTraitement.php?action=add" method="POST">
    <label for="objet">Objet :</label>
    <input type="text" name="objet" id="objet" required><br>

    <label for="description">Description :</label>
    <textarea name="description" id="description" required></textarea><br>

    <button type="submit">Soumettre la réclamation</button>
</form>

<?php include '../partials/footer.php'; ?>

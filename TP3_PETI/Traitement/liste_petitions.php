<?php
require '../BD/petition.php';

$petitions = getPetitions();
foreach ($petitions as $petition) {
    echo "<p>{$petition['Titre']} - <a href='../IHM/signature.php?id={$petition['IDP']}'>Signer</a></p>";
}
?>

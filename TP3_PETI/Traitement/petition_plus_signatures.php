<?php
require '../BD/petition.php';
echo json_encode(getPetitionPlusSignatures());
?>
<?php
session_start();
include '../partials/header.php';
?>

<h2>Détails de la Facture</h2>
<?php if ($facture): ?>
    <p><strong>ID :</strong> <?php echo $facture['id']; ?></p>
    <p><strong>Montant :</strong> <?php echo $facture['montant']; ?></p>
    <p><strong>Date d'émission :</strong> <?php echo $facture['date_emission']; ?></p>
    <p><strong>Statut :</strong> <?php echo $facture['statut']; ?></p>
<?php else: ?>
    <p>Facture introuvable.</p>
<?php endif; ?>

<a href="../../traitement/factureTraitement.php?action=list">Retour à la liste</a>

<?php include '../partials/footer.php'; ?>

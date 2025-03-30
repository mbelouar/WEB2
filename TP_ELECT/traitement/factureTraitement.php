<?php
// traitement/factureTraitement.php
session_start();
require_once '../BD/db.php';
require_once '../BD/Facture.php';

$factureModel = new Facture($pdo);
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

if (!isset($_SESSION['client'])) {
    header("Location: ../IHM/client/connexion.php");
    exit;
}

$clientId = $_SESSION['client']['id'];

if ($action === 'list') {
    $factures = $factureModel->getFacturesByClient($clientId);
    include '../IHM/factures/list.php';
} elseif ($action === 'detail' && isset($_GET['id'])) {
    $facture = $factureModel->getFactureById($_GET['id']);
    include '../IHM/factures/detail.php';
} elseif ($action === 'pay' && isset($_POST['facture_id'])) {
    $factureModel->markAsPaid($_POST['facture_id']);
    header("Location: factureTraitement.php?action=list");
    exit;
} else {
    echo "Action non définie.";
}
?>

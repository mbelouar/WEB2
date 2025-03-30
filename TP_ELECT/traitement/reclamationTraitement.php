<?php
session_start();
require_once '../BD/db.php';
require_once '../BD/Reclamation.php';

$reclamationModel = new Reclamation($pdo);
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

if (!isset($_SESSION['client'])) {
    header("Location: ../IHM/client/connexion.php");
    exit;
}

$clientId = $_SESSION['client']['id'];

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $objet = $_POST['complaintType'] ?? null;
    $description = $_POST['complaintDetails'] ?? null;
    $photoPath = null;

    // Vérifiez si les données sont reçues
    error_log("Objet: $objet, Description: $description");

    if (isset($_FILES['complaintPhoto']) && $_FILES['complaintPhoto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../uploads/complaints/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = basename($_FILES['complaintPhoto']['name']);
        $targetPath = $uploadDir . time() . "_" . $filename;
        if (move_uploaded_file($_FILES['complaintPhoto']['tmp_name'], $targetPath)) {
            $photoPath = $targetPath;
        }
    }

    if ($reclamationModel->addReclamation($clientId, $objet, $description, $photoPath)) {
        $lastReclamation = $reclamationModel->getLastReclamationByClient($clientId);
        echo json_encode(['success' => true, 'reclamation' => $lastReclamation]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de la réclamation.']);
    }
    exit;
}
?>
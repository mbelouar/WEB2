<?php
session_start();
require_once '../BD/db.php';
require_once '../BD/Fournisseur.php';

$fournModel = new Fournisseur($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $emailF = $_POST['emailF'];
        $passwordF = $_POST['passwordF'];

        // Vérifier dans la table Fournisseur
        $fournisseur = $fournModel->login($emailF, $passwordF);
        if ($fournisseur) {
            // Stocker en session
            $_SESSION['fournisseur'] = $fournisseur;
            header("Location: ../IHM/fournisseur/dashboard.php");
            exit;
        } else {
            echo "Identifiants invalides";
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    if ($action === 'logout') {
        session_destroy();
        header("Location: ../IHM/fournisseur/login.php");
        exit;
    }
}
?>

<?php
// traitement/clientTraitement.php
session_start();
require_once '../BD/db.php';
require_once '../BD/Client.php';

$clientModel = new Client($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Fonctionnalités client existantes
    if ($action === 'register') {
        $data = [
            'cin'       => $_POST['cin'],
            'nom'       => $_POST['nom'],
            'prenom'    => $_POST['prenom'],
            'email'     => $_POST['email'],
            'telephone' => $_POST['telephone'],
            'adresse'   => $_POST['adresse'],
            'password'  => $_POST['password']
        ];
        if ($clientModel->register($data)) {
            header("Location: ../IHM/client/connexion.php");
            exit;
        } else {
            // Affichage d'une erreur (pour inscription client)
            header("Location: ../IHM/client/connexion.php?error=emailUsed");
            exit;
        }
    } elseif ($action === 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $client = $clientModel->login($email, $password);
        if ($client) {
            $_SESSION['client'] = $client;
            header("Location: ../IHM/client/client_dashboard.php");
            exit;
        } else {
            echo "Identifiants invalides";
        }
    } elseif ($action === 'updateProfile') {
        if (!isset($_SESSION['client'])) {
            header("Location: ../IHM/client/connexion.php");
            exit;
        }
        $clientId = $_SESSION['client']['id'];
        $data = [
            'nom'       => $_POST['nom'],
            'prenom'    => $_POST['prenom'],
            'email'     => $_POST['email'],
            'telephone' => $_POST['telephone'],
            'adresse'   => $_POST['adresse']
        ];
        if ($clientModel->updateProfile($clientId, $data)) {
            $_SESSION['client'] = $clientModel->getProfile($clientId);
            header("Location: ../IHM/client/client_dashboard.php");
            exit;
        } else {
            echo "Erreur lors de la mise à jour du profil";
        }
    }
    // --- Traitements pour la gestion des clients par le fournisseur ---
    elseif ($action === 'addClient') {
        // Vérification de la session fournisseur
        if (!isset($_SESSION['fournisseur'])) {
            header("Location: ../IHM/fournisseur/login.php");
            exit;
        }
        $data = [
            'cin'       => $_POST['cin'],
            'nom'       => $_POST['nom'],
            'prenom'    => $_POST['prenom'],
            'email'     => $_POST['email'],
            'telephone' => $_POST['telephone'],
            'adresse'   => $_POST['adresse'],
            'password'  => $_POST['password']
        ];
        if ($clientModel->register($data)) {
            header("Location: ../IHM/fournisseur/clients.php");
            exit;
        } else {
            // Redirection avec paramètre d'erreur pour affichage dans l'interface fournisseur
            header("Location: ../IHM/fournisseur/clients.php?error=emailUsed");
            exit;
        }
    } elseif ($action === 'editClient') {
        // Vérification de la session fournisseur
        if (!isset($_SESSION['fournisseur'])) {
            header("Location: ../IHM/fournisseur/login.php");
            exit;
        }
        $clientId = $_POST['id'];
        $data = [
            'cin'       => $_POST['cin'],
            'nom'       => $_POST['nom'],
            'prenom'    => $_POST['prenom'],
            'email'     => $_POST['email'],
            'telephone' => $_POST['telephone'],
            'adresse'   => $_POST['adresse']
        ];
        // Mise à jour du mot de passe si renseigné
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }
        if ($clientModel->updateClient($clientId, $data)) {
            header("Location: ../IHM/fournisseur/clients.php");
            exit;
        } else {
            echo "Erreur lors de la modification du client";
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    if ($action === 'logout') {
        session_destroy();
        header("Location: ../IHM/client/connexion.php");
        exit;
    }
    // --- Traitement pour la suppression d'un client par le fournisseur ---
    elseif ($action === 'deleteClient' && isset($_GET['id'])) {
        if (!isset($_SESSION['fournisseur'])) {
            header("Location: ../IHM/fournisseur/login.php");
            exit;
        }
        $id = intval($_GET['id']);
        if ($clientModel->deleteClient($id)) {
            header("Location: ../IHM/fournisseur/clients.php");
            exit;
        } else {
            echo "Erreur lors de la suppression du client";
        }
    }
} else {
    echo "Méthode non gérée ou action non définie.";
}
?>

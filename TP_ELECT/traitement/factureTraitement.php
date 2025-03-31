<?php
// traitement/factureTraitement.php
// Define this as an API request to suppress unnecessary output
define('API_REQUEST', true);

session_start();
require_once '../BD/db.php';
require_once '../BD/Facture.php';

$factureModel = new Facture($pdo);
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

if (!isset($_SESSION['client'])) {
    if ($action === 'pay' && (isset($_GET['id']) || isset($_POST['facture_id']))) {
        // For AJAX responses, return JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Non connecté']);
        exit;
    }
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
} elseif ($action === 'pay') {
    // Get invoice ID either from GET or POST
    $invoiceId = $_GET['id'] ?? ($_POST['facture_id'] ?? null);
    
    if (!$invoiceId) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID de facture manquant']);
        } else {
            header("Location: ../IHM/client/client_invoices.php");
        }
        exit;
    }
    
    try {
        // Verify the invoice belongs to the client
        $facture = $factureModel->getFactureById($invoiceId);
        
        if (!$facture || $facture['client_id'] != $clientId) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Facture non trouvée ou non autorisée']);
            } else {
                header("Location: ../IHM/client/client_invoices.php?error=unauthorized");
            }
            exit;
        }
        
        // If already paid, return success
        if (strtolower($facture['statut']) === 'payée') {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Cette facture est déjà payée']);
            } else {
                header("Location: ../IHM/client/client_invoices.php?message=already_paid");
            }
            exit;
        }
        
        // Mark invoice as paid
        $result = $factureModel->markAsPaid($invoiceId);
        
        if ($result) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Paiement effectué avec succès']);
            } else {
                header("Location: ../IHM/client/client_invoices.php?message=payment_success");
            }
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Erreur lors du paiement']);
            } else {
                header("Location: ../IHM/client/client_invoices.php?error=payment_failed");
            }
        }
    } catch (Exception $e) {
        error_log("Error in factureTraitement.php - pay: " . $e->getMessage());
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erreur système: ' . $e->getMessage()]);
        } else {
            header("Location: ../IHM/client/client_invoices.php?error=system_error");
        }
    }
    exit;
} else {
    echo "Action non définie.";
}
?>

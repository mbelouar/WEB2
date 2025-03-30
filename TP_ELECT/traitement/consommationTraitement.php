<?php
session_start();
require_once '../BD/db.php';
require_once '../BD/Consumption.php';

// Fonction pour envoyer une réponse JSON et terminer le script
function sendJsonResponse($success, $message = '', $data = null) {
    $response = ['success' => $success];
    if (!empty($message)) $response['message'] = $message;
    if ($data !== null) $response = array_merge($response, $data);
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

try {
    $consumptionModel = new Consumption($pdo);
    $action = $_GET['action'] ?? ($_POST['action'] ?? '');

    if (!isset($_SESSION['client'])) {
        sendJsonResponse(false, 'Session expirée. Veuillez vous reconnecter.');
    }

    $clientId = $_SESSION['client']['id'];

    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validation des données
        if (!isset($_POST['month']) || empty($_POST['month'])) {
            sendJsonResponse(false, 'Le mois est requis.');
        }
        
        if (!isset($_POST['currentReading']) || !is_numeric($_POST['currentReading'])) {
            sendJsonResponse(false, 'La valeur du compteur doit être un nombre.');
        }
        
        $month = $_POST['month'];
        $currentReading = (int)$_POST['currentReading'];
        $photoPath = null;

        if (isset($_FILES['meterPhoto']) && $_FILES['meterPhoto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "../uploads/consumptions/";
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    sendJsonResponse(false, 'Erreur lors de la création du répertoire d\'upload.');
                }
            }
            $filename = basename($_FILES['meterPhoto']['name']);
            $targetPath = $uploadDir . time() . "_" . $filename;
            if (move_uploaded_file($_FILES['meterPhoto']['tmp_name'], $targetPath)) {
                $photoPath = $targetPath;
            } else {
                sendJsonResponse(false, 'Erreur lors du téléchargement de l\'image.');
            }
        } else if (isset($_FILES['meterPhoto'])) {
            // Gérer les différents codes d'erreur
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE => 'La taille du fichier dépasse la limite autorisée par PHP.',
                UPLOAD_ERR_FORM_SIZE => 'La taille du fichier dépasse la limite autorisée par le formulaire.',
                UPLOAD_ERR_PARTIAL => 'Le fichier n\'a été que partiellement téléchargé.',
                UPLOAD_ERR_NO_FILE => 'Aucun fichier n\'a été téléchargé.',
                UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant.',
                UPLOAD_ERR_CANT_WRITE => 'Échec de l\'écriture du fichier sur le disque.',
                UPLOAD_ERR_EXTENSION => 'Une extension PHP a arrêté le téléchargement du fichier.'
            ];
            $errorCode = $_FILES['meterPhoto']['error'];
            $errorMessage = isset($uploadErrors[$errorCode]) ? $uploadErrors[$errorCode] : 'Erreur inconnue lors du téléchargement.';
            sendJsonResponse(false, $errorMessage);
        } else {
            sendJsonResponse(false, 'Photo du compteur requise.');
        }

        if ($consumptionModel->addConsumption($clientId, $month, $currentReading, $photoPath)) {
            $lastConsumption = $consumptionModel->getLastConsumption($clientId);
            if ($lastConsumption) {
                sendJsonResponse(true, 'Consommation ajoutée avec succès.', ['consumption' => $lastConsumption]);
            } else {
                sendJsonResponse(false, 'La consommation a été ajoutée mais impossible de récupérer les détails.');
            }
        } else {
            sendJsonResponse(false, 'Erreur lors de l\'ajout de la consommation.');
        }
    } else {
        sendJsonResponse(false, 'Action non reconnue ou méthode incorrecte.');
    }
} catch (Exception $e) {
    sendJsonResponse(false, 'Erreur système: ' . $e->getMessage());
}
?>
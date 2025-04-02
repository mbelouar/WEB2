<?php
// traitement/consommationTraitement.php

// Enable strict error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define this as an API request to suppress unnecessary output
if (isset($_GET['api_request']) && $_GET['api_request'] === 'true' || isset($_POST['API_REQUEST'])) {
    define('API_REQUEST', true);
}

// Enable error logging to file
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php-errors.log');

// Test endpoint - uncomment to verify basic JSON functionality
if (isset($_GET['test'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Test endpoint working']);
    exit;
}

session_start();
require_once '../BD/db.php';
require_once '../BD/Consumption.php';
require_once '../BD/Facture.php';
require_once '../BD/Client.php';

// Initialiser la réponse JSON
$response = [
    "success" => false,
    "message" => "",
    "errors" => [],
    "debug" => []
];

// Check if the request is for get_details or edit actions
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');
$allowedWithoutClientSession = ['get_details', 'edit'];

// Vérifier si l'utilisateur est connecté (client ou fournisseur)
if (!isset($_SESSION['client']) && !isset($_SESSION['fournisseur']) && !in_array($action, $allowedWithoutClientSession)) {
    $response["message"] = "Vous devez être connecté pour accéder à cette page.";
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Set client ID if client is logged in
$clientId = isset($_SESSION['client']) ? $_SESSION['client']['id'] : null;
$consumptionModel = new Consumption($pdo);
$clientModel = new Client($pdo);

// Debug function that adds to response
function addDebug($key, $value) {
    global $response;
    $response["debug"][$key] = $value;
}

// Add request info for debugging
addDebug('POST', $_POST);
addDebug('FILES', isset($_FILES) ? $_FILES : 'None');

// Get consumption details
if ($action === 'get_details' && isset($_GET['id'])) {
    $consumptionId = $_GET['id'];
    
    try {
        // Get consumption details from database
        $sql = "SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom 
                FROM Consumption c
                JOIN Client cl ON c.client_id = cl.id
                WHERE c.idC = ?";
        
        error_log("Getting consumption details for ID: $consumptionId");
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$consumptionId]);
        $consumption = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($consumption) {
            error_log("Found consumption: " . print_r($consumption, true));
            $response = [
                'success' => true, 
                'consumption' => $consumption
            ];
        } else {
            error_log("No consumption found with ID: $consumptionId");
            $response = [
                'success' => false, 
                'message' => 'Consommation non trouvée'
            ];
        }
    } catch (Exception $e) {
        error_log("Error getting consumption details: " . $e->getMessage());
        $response = [
            'success' => false, 
            'message' => 'Erreur lors de la récupération des détails'
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Edit consumption
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $consumptionId = $_POST['idC'] ?? null;
    $currentReading = $_POST['current_reading'] ?? null;
    $month = $_POST['month'] ?? null;
    
    // Debug information
    error_log("Edit consumption request: ID=$consumptionId, Reading=$currentReading, Month=$month");
    error_log("POST data: " . print_r($_POST, true));
    
    if (!$consumptionId || !$currentReading || !$month) {
        $response = [
            'success' => false, 
            'message' => 'Paramètres manquants',
            'debug' => [
                'idC' => $consumptionId,
                'current_reading' => $currentReading,
                'month' => $month,
                'post' => $_POST
            ]
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    try {
        // Update consumption - use current_reading which is the actual column name in the database
        $sql = "UPDATE Consumption 
                SET current_reading = ?, month = ? 
                WHERE idC = ?";
        
        error_log("SQL Query: $sql");
        error_log("Parameters: current_reading=$currentReading, month=$month, id=$consumptionId");
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$currentReading, $month, $consumptionId]);
        
        if ($result) {
            // Get updated consumption
            $sql = "SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom 
                    FROM Consumption c
                    JOIN Client cl ON c.client_id = cl.id
                    WHERE c.idC = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$consumptionId]);
            $consumption = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Updated consumption: " . print_r($consumption, true));
            
            $response = [
                'success' => true, 
                'message' => 'Consommation mise à jour avec succès',
                'consumption' => $consumption
            ];
        } else {
            error_log("SQL Error: " . print_r($stmt->errorInfo(), true));
            $response = [
                'success' => false, 
                'message' => 'Erreur lors de la mise à jour de la consommation',
                'error' => $stmt->errorInfo()
            ];
        }
    } catch (Exception $e) {
        error_log("Error updating consumption: " . $e->getMessage());
        $response = [
            'success' => false, 
            'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Approve consumption
if ($action === 'approve' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is a fournisseur
    if (!isset($_SESSION['fournisseur'])) {
        $response = [
            'success' => false, 
            'message' => 'Vous devez être connecté en tant que fournisseur pour approuver une consommation'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    $consumptionId = $_POST['idC'] ?? null;
    
    if (!$consumptionId) {
        $response = [
            'success' => false, 
            'message' => 'ID de consommation manquant'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    try {
        // Get consumption details
        $consumption = $consumptionModel->getConsumptionById($consumptionId);
        
        if (!$consumption) {
            $response = [
                'success' => false, 
                'message' => 'Consommation non trouvée'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        // Update consumption status to approved
        $result = $consumptionModel->updateStatus($consumptionId, 'approved');
        
        if ($result) {
            // Get client ID from consumption
            $clientId = $consumption['client_id'];
            
            // Get previous consumption for this client
            $previousConsumption = $consumptionModel->getPreviousConsumption($clientId, $consumptionId);
            
            // Create a new invoice
            $factureModel = new Facture($pdo);
            $invoiceResult = $factureModel->createInvoiceFromConsumption($clientId, $consumption, $previousConsumption);
            
            if ($invoiceResult['success']) {
                $response = [
                    'success' => true, 
                    'message' => 'Consommation approuvée et facture générée avec succès',
                    'invoice' => $invoiceResult
                ];
            } else {
                // If invoice creation failed, still return success for the approval
                $response = [
                    'success' => true, 
                    'message' => 'Consommation approuvée mais erreur lors de la création de la facture',
                    'error' => $invoiceResult['error'] ?? 'Erreur inconnue'
                ];
            }
        } else {
            $response = [
                'success' => false, 
                'message' => 'Erreur lors de l\'approbation de la consommation'
            ];
        }
    } catch (Exception $e) {
        error_log("Error approving consumption: " . $e->getMessage());
        $response = [
            'success' => false, 
            'message' => 'Erreur système: ' . $e->getMessage()
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Reject consumption
if ($action === 'reject' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is a fournisseur
    if (!isset($_SESSION['fournisseur'])) {
        $response = [
            'success' => false, 
            'message' => 'Vous devez être connecté en tant que fournisseur pour rejeter une consommation'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    $consumptionId = $_POST['idC'] ?? null;
    
    if (!$consumptionId) {
        $response = [
            'success' => false, 
            'message' => 'ID de consommation manquant'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    try {
        // Update consumption status to rejected
        $result = $consumptionModel->updateStatus($consumptionId, 'rejected');
        
        if ($result) {
            $response = [
                'success' => true, 
                'message' => 'Consommation rejetée avec succès'
            ];
        } else {
            $response = [
                'success' => false, 
                'message' => 'Erreur lors du rejet de la consommation'
            ];
        }
    } catch (Exception $e) {
        error_log("Error rejecting consumption: " . $e->getMessage());
        $response = [
            'success' => false, 
            'message' => 'Erreur système: ' . $e->getMessage()
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle client consumption submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && ($_POST['action'] === 'new_consumption' || $_POST['action'] === 'add')) {
    $month = $_POST['month'] ?? '';
    $currentReading = $_POST['currentReading'] ?? $_POST['current_reading'] ?? '';
    
    // Validate month
    if (empty($month)) {
        $response["errors"][] = "Le mois est requis.";
    }
    
    // Validate current reading
    if (empty($currentReading)) {
        $response["errors"][] = "Le relevé actuel est requis.";
    } elseif (!is_numeric($currentReading)) {
        $response["errors"][] = "Le relevé actuel doit être un nombre.";
    } elseif ($currentReading < 0) {
        $response["errors"][] = "Le relevé actuel ne peut pas être négatif.";
    }
    
    // Process photo upload
    $photoPath = null;
    $uploadOk = true;
    
    if (isset($_FILES['meterPhoto']) && $_FILES['meterPhoto']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Check if there was an upload error
        if ($_FILES['meterPhoto']['error'] !== 0) {
            $uploadOk = false;
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => "Le fichier dépasse la taille maximale autorisée par PHP.",
                UPLOAD_ERR_FORM_SIZE => "Le fichier dépasse la taille maximale autorisée par le formulaire.",
                UPLOAD_ERR_PARTIAL => "Le fichier n'a été que partiellement téléchargé.",
                UPLOAD_ERR_NO_TMP_DIR => "Pas de répertoire temporaire pour stocker le fichier.",
                UPLOAD_ERR_CANT_WRITE => "Impossible d'écrire le fichier sur le disque.",
                UPLOAD_ERR_EXTENSION => "Une extension PHP a arrêté le téléchargement du fichier."
            ];
            $response["errors"][] = $errorMessages[$_FILES['meterPhoto']['error']] ?? "Erreur lors du téléchargement du fichier.";
        } else {
            // Check file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $_FILES['meterPhoto']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $uploadOk = false;
                $response["errors"][] = "Seuls les fichiers JPG, PNG et GIF sont autorisés.";
            }
            
            // Check file size (max 5MB)
            if ($_FILES['meterPhoto']['size'] > 5000000) {
                $uploadOk = false;
                $response["errors"][] = "Le fichier est trop volumineux. Taille maximale: 5MB.";
            }
            
            // If everything is ok, try to upload the file
            if ($uploadOk) {
                $uploadDir = "../uploads/meter_photos/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['meterPhoto']['name']);
                $targetFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['meterPhoto']['tmp_name'], $targetFile)) {
                    $photoPath = $targetFile;
                } else {
                    $response["errors"][] = "Erreur lors du déplacement du fichier téléchargé.";
                }
            }
        }
    }
    
    // If no errors, proceed with saving consumption
    if (empty($response["errors"])) {
        // Check if a consumption already exists for this month
        $existingConsumption = $consumptionModel->getConsumptionByClientAndMonth($clientId, $month);
        
        if ($existingConsumption) {
            $response["errors"][] = "Vous avez déjà soumis une consommation pour ce mois.";
        } else {
            try {
                // Get previous consumption for this client
                $previousConsumption = $consumptionModel->getLastConsumptionByClient($clientId);
                $previousReading = $previousConsumption ? $previousConsumption['current_reading'] : 0;
                
                // Calculate consumption in kWh
                $kwhConsumed = $currentReading - $previousReading;
                
                if ($kwhConsumed < 0) {
                    $response["errors"][] = "La consommation ne peut pas être négative. Vérifiez votre relevé actuel.";
                } else {
                    // Save consumption
                    $result = $consumptionModel->addConsumption(
                        $clientId,
                        $month,
                        $currentReading,
                        $photoPath
                    );
                    
                    if ($result) {
                        $response["success"] = true;
                        $response["message"] = "Consommation enregistrée avec succès.";
                        
                        // Get the newly added consumption
                        $newConsumption = $consumptionModel->getLastConsumptionByClient($clientId);
                        $response["consumption"] = $newConsumption;
                    } else {
                        $response["errors"][] = "Erreur lors de l'enregistrement de la consommation.";
                    }
                }
            } catch (Exception $e) {
                $response["errors"][] = "Exception: " . $e->getMessage();
                addDebug('exception', $e->getMessage());
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'debug') {
    // Debugging endpoint
    try {
        $tableInfo = $consumptionModel->checkTableSchema();
        $response["success"] = true;
        $response["debug"] = [
            "table_info" => $tableInfo,
            "php_version" => PHP_VERSION,
            "session" => $_SESSION
        ];
    } catch (Exception $e) {
        $response["errors"][] = "Debug error: " . $e->getMessage();
    }
} else if (!in_array($action, ['get_details', 'edit'])) {
    $response["message"] = "Action non reconnue.";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
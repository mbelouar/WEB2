<?php
// traitement/consommationTraitement.php

// Enable strict error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define this as an API request to suppress unnecessary output
define('API_REQUEST', true);

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

// Initialiser la réponse JSON
$response = [
    "success" => false,
    "message" => "",
    "errors" => [],
    "debug" => []
];

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client'])) {
    $response["message"] = "Vous devez être connecté pour accéder à cette page.";
    echo json_encode($response);
    exit;
}

$clientId = $_SESSION['client']['id'];
$consumptionModel = new Consumption($pdo);

// Debug function that adds to response
function addDebug($key, $value) {
    global $response;
    $response["debug"][$key] = $value;
}

// Add request info for debugging
addDebug('POST', $_POST);
addDebug('FILES', isset($_FILES) ? $_FILES : 'None');

// Ajouter une nouvelle consommation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && ($_POST['action'] === 'new_consumption' || $_POST['action'] === 'add')) {
    $month = $_POST['month'] ?? '';
    $currentReading = $_POST['currentReading'] ?? $_POST['current_reading'] ?? '';
    
    // Validate month
    if (empty($month)) {
        $response["errors"][] = "Le mois est requis.";
    } else {
        // Try to parse the month into a standard format
        $frenchMonths = [
            'janvier' => '01', 'fevrier' => '02', 'février' => '02', 'mars' => '03', 
            'avril' => '04', 'mai' => '05', 'juin' => '06', 'juillet' => '07', 
            'aout' => '08', 'août' => '08', 'septembre' => '09', 
            'octobre' => '10', 'novembre' => '11', 'decembre' => '12', 'décembre' => '12'
        ];
        
        $normalizedMonth = strtolower(trim($month));
        $standardFormat = '';
        
        // Check if it's already in YYYY-MM format
        if (preg_match('/^(\d{4})-(\d{1,2})$/', $normalizedMonth, $matches)) {
            $standardFormat = sprintf('%04d-%02d', $matches[1], $matches[2]);
        }
        // Check if it's in format "Month YYYY" (e.g., "juin 2025")
        elseif (preg_match('/^([a-zéûôèàù]+)\s+(\d{4})$/i', $normalizedMonth, $matches)) {
            $monthName = strtolower($matches[1]);
            $year = $matches[2];
            
            if (isset($frenchMonths[$monthName])) {
                $standardFormat = $year . '-' . $frenchMonths[$monthName];
            }
        }
        // Check if it's in format "YYYY Month" (e.g., "2025 juin")
        elseif (preg_match('/^(\d{4})\s+([a-zéûôèàù]+)$/i', $normalizedMonth, $matches)) {
            $year = $matches[1];
            $monthName = strtolower($matches[2]);
            
            if (isset($frenchMonths[$monthName])) {
                $standardFormat = $year . '-' . $frenchMonths[$monthName];
            }
        }
        // Check if it's a numeric month-year (MM-YYYY or MM/YYYY)
        elseif (preg_match('/^(\d{1,2})[\/-](\d{4})$/', $normalizedMonth, $matches)) {
            $standardFormat = sprintf('%04d-%02d', $matches[2], $matches[1]);
        }
        // Check if it's a numeric year-month (YYYY-MM or YYYY/MM) without leading zeros
        elseif (preg_match('/^(\d{4})[\/-](\d{1,2})$/', $normalizedMonth, $matches)) {
            $standardFormat = sprintf('%04d-%02d', $matches[1], $matches[2]);
        }
        
        if (empty($standardFormat)) {
            $response["errors"][] = "Format de mois invalide. Utilisez le format YYYY-MM ou le nom du mois suivi de l'année (ex: juin 2025).";
        } else {
            // Use the standardized format
            $month = $standardFormat;
            addDebug('normalized_month', $month);
        }
    }
    
    // Validate current reading
    if (empty($currentReading)) {
        $response["errors"][] = "La valeur actuelle du compteur est obligatoire.";
    } elseif (!is_numeric($currentReading) || $currentReading < 0) {
        $response["errors"][] = "La valeur du compteur doit être un nombre positif.";
    }

    // Photo upload handling
    $photoPath = null;
    $uploadOk = true;
    
    // Log the $_FILES array for debugging
    addDebug('files', $_FILES);
    
    if (isset($_FILES['meterPhoto']) && $_FILES['meterPhoto']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Check if there was an upload error
        if ($_FILES['meterPhoto']['error'] !== 0) {
            $uploadOk = false;
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => "La taille du fichier dépasse la limite autorisée par le serveur.",
                UPLOAD_ERR_FORM_SIZE => "La taille du fichier dépasse la limite autorisée par le formulaire.",
                UPLOAD_ERR_PARTIAL => "Le fichier n'a été que partiellement téléchargé.",
                UPLOAD_ERR_NO_TMP_DIR => "Erreur serveur: répertoire temporaire manquant.",
                UPLOAD_ERR_CANT_WRITE => "Erreur serveur: impossible d'écrire le fichier sur le disque.",
                UPLOAD_ERR_EXTENSION => "Une extension PHP a arrêté le téléchargement du fichier."
            ];
            $errorCode = $_FILES['meterPhoto']['error'];
            $response["errors"][] = "Erreur lors du téléchargement de la photo: " . 
                                    ($errorMessages[$errorCode] ?? "Erreur inconnue (code $errorCode)");
        } else {
            // Check file type
            $fileType = strtolower(pathinfo($_FILES['meterPhoto']['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $uploadOk = false;
                $response["errors"][] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
            }
            
            // Check file size (5MB max)
            if ($_FILES['meterPhoto']['size'] > 5000000) {
                $uploadOk = false;
                $response["errors"][] = "La taille du fichier ne doit pas dépasser 5MB.";
            }
            
            // Try to upload the file if no errors
            if ($uploadOk) {
                // Create upload directory if it doesn't exist
                $uploadDir = '../uploads/compteurs/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = "client" . $clientId . "_" . $month . "_" . time() . "." . $fileType;
                $targetFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['meterPhoto']['tmp_name'], $targetFile)) {
                    $photoPath = 'uploads/compteurs/' . $fileName;
                    addDebug('upload_success', true);
                    addDebug('photo_path', $photoPath);
                } else {
                    $response["errors"][] = "Erreur lors de l'enregistrement de la photo.";
                    addDebug('upload_move_failed', true);
                    addDebug('target_file', $targetFile);
                    addDebug('tmp_name', $_FILES['meterPhoto']['tmp_name']);
                    $uploadOk = false;
                }
            }
        }
    } else {
        // No photo provided, set a flag
        addDebug('no_photo_provided', true);
    }

    // If no validation errors, try to add the consumption
    if (empty($response["errors"])) {
        // Check if a consumption already exists for this month
        $existingConsumption = $consumptionModel->getConsumptionByClientAndMonth($clientId, $month);
        
        if ($existingConsumption) {
            $response["errors"][] = "Une consommation existe déjà pour ce mois. Veuillez choisir un autre mois.";
        } else {
            try {
                addDebug('before_add', [
                    'client_id' => $clientId,
                    'month' => $month,
                    'current_reading' => $currentReading,
                    'photo_path' => $photoPath
                ]);
                
                $result = $consumptionModel->addConsumption($clientId, $month, $currentReading, $photoPath);
                
                addDebug('add_result', $result);
                
                if ($result) {
                    $response["success"] = true;
                    $response["message"] = "Consommation enregistrée avec succès!";
                    
                    // Get the consumption data to return to the client
                    $lastConsumption = $consumptionModel->getLastConsumption($clientId);
                    if ($lastConsumption) {
                        $response["consumption"] = $lastConsumption;
                        
                        // Generate an invoice for this consumption
                        $factureModel = new Facture($pdo);
                        
                        // Get the previous consumption to calculate the difference
                        $previousConsumptions = $consumptionModel->getConsumptionsByClient($clientId);
                        $previousConsumption = null;
                        
                        // Find the previous consumption (excluding the current one)
                        if (count($previousConsumptions) > 1) {
                            foreach ($previousConsumptions as $cons) {
                                if ($cons['id'] != $lastConsumption['id']) {
                                    $previousConsumption = $cons;
                                    break;
                                }
                            }
                        }
                        
                        // Create the invoice
                        $invoiceResult = $factureModel->createInvoiceFromConsumption(
                            $clientId, 
                            $lastConsumption, 
                            $previousConsumption
                        );
                        
                        if ($invoiceResult['success']) {
                            $response["invoice"] = $invoiceResult;
                            $response["message"] .= " Une facture a été générée automatiquement.";
                        } else {
                            $response["invoice_error"] = "Impossible de générer la facture: " . 
                                                        ($invoiceResult['error'] ?? "Erreur inconnue");
                        }
                    }
                } else {
                    $response["errors"][] = "Erreur lors de l'enregistrement de la consommation.";
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
} else {
    $response["message"] = "Action non reconnue.";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
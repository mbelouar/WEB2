<?php
session_start();

// Define API_REQUEST constant to prevent any text output from db.php in AJAX calls
if (isset($_GET['api_request']) && $_GET['api_request'] === 'true') {
    define('API_REQUEST', true);
}

require_once '../BD/db.php';
require_once '../BD/Reclamation.php';
require_once '../BD/Client.php';

$reclamationModel = new Reclamation($pdo);
$clientModel = new Client($pdo);
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

// For get_details action, don't require client session
if ($action === 'get_details' || $action === 'update_status') {
    // Continue processing without client session check
} else if (!isset($_SESSION['client'])) {
    header("Location: ../IHM/client/connexion.php");
    exit;
}

$clientId = $_SESSION['client']['id'] ?? null;

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Set appropriate headers for JSON response
        header('Content-Type: application/json');
        
        $objet = $_POST['complaintType'] ?? null;
        $description = $_POST['complaintDetails'] ?? null;
        $photoPath = null;

        // Validation
        if (!$objet || !$description) {
            echo json_encode([
                'success' => false, 
                'message' => 'Le type de réclamation et la description sont requis.'
            ]);
            exit;
        }

        if (!$clientId) {
            echo json_encode([
                'success' => false, 
                'message' => 'Session client non trouvée. Veuillez vous reconnecter.'
            ]);
            exit;
        }

        // Vérifiez si les données sont reçues
        error_log("Reclamation - Objet: $objet, Description: $description, Client ID: $clientId");

        // Process uploaded photo if available
        if (isset($_FILES['complaintPhoto']) && $_FILES['complaintPhoto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "../uploads/complaints/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $filename = basename($_FILES['complaintPhoto']['name']);
            $targetPath = $uploadDir . time() . "_" . $filename;
            if (move_uploaded_file($_FILES['complaintPhoto']['tmp_name'], $targetPath)) {
                $photoPath = $targetPath;
                error_log("Photo uploaded successfully to: $photoPath");
            } else {
                error_log("Failed to upload photo: " . $_FILES['complaintPhoto']['error']);
            }
        }

        // Add the reclamation
        if ($reclamationModel->addReclamation($clientId, $objet, $description, $photoPath)) {
            $lastReclamation = $reclamationModel->getLastReclamationByClient($clientId);
            
            if ($lastReclamation) {
                echo json_encode(['success' => true, 'reclamation' => $lastReclamation]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Réclamation ajoutée mais impossible de la récupérer.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de la réclamation.']);
        }
    } catch (Exception $e) {
        error_log("Exception in reclamation add: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
    }
    exit;
}

// Get complaint details
if ($action === 'get_details' && isset($_GET['id'])) {
    $complaintId = $_GET['id'];
    
    try {
        // Get complaint details from database
        $sql = "SELECT r.*, c.nom as client_nom, c.prenom as client_prenom 
                FROM Reclamation r
                JOIN Client c ON r.client_id = c.id
                WHERE r.id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$complaintId]);
        $complaint = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($complaint) {
            echo json_encode([
                'success' => true, 
                'complaint' => $complaint
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Réclamation non trouvée'
            ]);
        }
    } catch (Exception $e) {
        error_log("Error getting complaint details: " . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur lors de la récupération des détails'
        ]);
    }
    exit;
}

// Update complaint status
if ($action === 'update_status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaintId = $_POST['id'] ?? null;
    $newStatus = $_POST['status'] ?? null;
    $response = $_POST['response'] ?? null;
    $sendEmail = isset($_POST['sendEmail']) && $_POST['sendEmail'] === '1';
    
    if (!$complaintId || !$newStatus || !$response) {
        echo json_encode([
            'success' => false, 
            'message' => 'Paramètres manquants'
        ]);
        exit;
    }
    
    try {
        // Check if the reponse column exists
        $hasReponseColumn = false;
        $stmt = $pdo->query("PRAGMA table_info(Reclamation)");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $column) {
            if ($column['name'] === 'reponse') {
                $hasReponseColumn = true;
                break;
            }
        }
        
        // If reponse column doesn't exist, add it
        if (!$hasReponseColumn) {
            $pdo->exec("ALTER TABLE Reclamation ADD COLUMN reponse TEXT");
            $pdo->exec("ALTER TABLE Reclamation ADD COLUMN date_traitement TEXT");
        }
        
        // Update complaint status
        $sql = "UPDATE Reclamation SET statut = ?, reponse = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$newStatus, $response, $complaintId]);
        
        if ($result) {
            // Update date_traitement
            $sql = "UPDATE Reclamation SET date_traitement = datetime('now') WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$complaintId]);
            
            // Get updated complaint
            $sql = "SELECT r.*, c.nom as client_nom, c.prenom as client_prenom, c.email as client_email 
                    FROM Reclamation r
                    JOIN Client c ON r.client_id = c.id
                    WHERE r.id = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$complaintId]);
            $complaint = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Send email notification if requested
            if ($sendEmail && $complaint && !empty($complaint['client_email'])) {
                // Email sending logic would go here
                // For now, just log it
                error_log("Would send email to: {$complaint['client_email']} about complaint status: $newStatus");
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Statut mis à jour avec succès',
                'complaint' => $complaint
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur lors de la mise à jour du statut'
            ]);
        }
    } catch (Exception $e) {
        error_log("Error updating complaint status: " . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
        ]);
    }
    exit;
}
?>